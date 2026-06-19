<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ZoruAiPromptNormalizer
{
    /** @var array<string, string>|null */
    private static ?array $typoMap = null;

    /** @var list<string>|null */
    private static ?array $vocabulary = null;


    /**
     * @return array{normalized: string, corrections: list<array{from: string, to: string}>}
     */
    public function process(string $prompt): array
    {
        $original = trim($prompt);
        if ($original === '') {
            return ['normalized' => '', 'corrections' => []];
        }

        $corrections = [];
        $original = $this->normalizeJoinedDomainWords($original, $corrections);
        $parts = preg_split('/(\s+)/u', $original, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$original];
        $normalizedParts = [];

        foreach ($parts as $part) {
            if (preg_match('/^\s+$/u', $part)) {
                $normalizedParts[] = $part;
                continue;
            }

            $token = $part;
            $lower = Str::lower($token);
            $fixed = $this->lookup($lower);

            if ($fixed !== null && $fixed !== $lower) {
                $corrections[] = ['from' => $lower, 'to' => $fixed];
                $token = str_contains($fixed, ' ')
                    ? $fixed
                    : $this->preserveCase($token, $fixed);
            }

            $normalizedParts[] = $token;
        }

        $normalized = trim(implode('', $normalizedParts));
        $normalized = preg_replace('/\s+/u', ' ', $normalized) ?? $normalized;

        return [
            'normalized' => $normalized,
            'corrections' => $this->uniqueCorrections($corrections),
        ];
    }

    public function normalize(string $prompt): string
    {
        return $this->process($prompt)['normalized'];
    }

    /** @param list<array{from: string, to: string}> $corrections */
    public function hasCorrections(array $corrections): bool
    {
        return $corrections !== [];
    }

    private function isCorrectWord(string $word, array $map): bool
    {
        return in_array($word, $this->domainVocabulary(), true) || in_array($word, $map, true);
    }

    private function lookup(string $lower): ?string
    {
        $map = $this->typoMap();

        if (isset($map[$lower])) {
            return $map[$lower];
        }

        if ($this->isProtectedWord($lower) || $this->isCorrectWord($lower, $map)) {
            return null;
        }

        // Try stripping Indonesian suffixes
        $stemmed = $this->stripIndonesianSuffixes($lower);
        if ($stemmed !== $lower) {
            if (isset($map[$stemmed])) {
                return $map[$stemmed];
            }
            if ($this->isProtectedWord($stemmed) || $this->isCorrectWord($stemmed, $map)) {
                return $stemmed;
            }
        }

        if (strlen($lower) < 5) {
            return null;
        }

        return $this->fuzzyMatch($lower);
    }

    /**
     * @param list<array{from: string, to: string}> $corrections
     */
    private function normalizeJoinedDomainWords(string $text, array &$corrections): string
    {
        $joined = [
            'gantioli' => 'ganti oli',
            'gantoli' => 'ganti oli',
            'gntioli' => 'ganti oli',
            'hargaoli' => 'harga oli',
            'brape' => 'berapa',
            'brapa' => 'berapa',
            'brapeh' => 'berapa',
            'zeropai' => 'zeropay',
            'zeropey' => 'zeropay',
            'topup' => 'top up',
            'isiulang' => 'isi ulang',
            'sparepartnya' => 'sparepart nya',
            'servisringan' => 'servis ringan',
            'tuneup' => 'tune up',
            'perbaikanrem' => 'perbaikan rem',
            'bookingpenuh' => 'booking penuh',
            'saldokurang' => 'saldo kurang',
            'stokhabis' => 'stok habis',
        ];

        foreach ($joined as $from => $to) {
            $pattern = '/\b' . preg_quote($from, '/') . '\b/iu';
            if (preg_match($pattern, $text)) {
                $corrections[] = ['from' => $from, 'to' => $to];
                $text = preg_replace($pattern, $to, $text) ?? $text;
            }
        }

        return $text;
    }

    private function isProtectedWord(string $word): bool
    {
        $protected = [
            'saya', 'kamu', 'anda', 'dia', 'kami', 'kita', 'mereka', 'ini', 'itu',
            'yang', 'dan', 'atau', 'dengan', 'untuk', 'dari', 'pada', 'ke', 'di',
            'apa', 'siapa', 'kapan', 'dimana', 'kenapa', 'bagaimana', 'sudah', 'belum',
            'halo', 'hai', 'hi', 'hello', 'kabar', 'selamat', 'pagi', 'siang', 'sore', 'malam',
            'berapa', 'harga', 'biaya', 'tarif', 'servis', 'service', 'ringan',
            'bilang', 'kata', 'kalimat', 'motor', 'mesin', 'oli', 'rem', 'ban',
        ];

        return in_array($word, $protected, true);
    }

    private function fuzzyMatch(string $word): ?string
    {
        if (! preg_match('/^[a-z0-9]+$/', $word)) {
            return null;
        }

        $best = null;
        $bestDistance = 2;

        foreach ($this->vocabulary() as $candidate) {
            if (strlen($candidate) < 5 || $this->isProtectedWord($candidate)) {
                continue;
            }
            if (abs(strlen($candidate) - strlen($word)) > 1) {
                continue;
            }

            $distance = levenshtein($word, $candidate);
            if ($distance === 1) {
                $bestDistance = 1;
                $best = $candidate;
            }
        }

        return $best;
    }

    /** @return array<string, string> */
    private function typoMap(): array
    {
        if (self::$typoMap !== null) {
            return self::$typoMap;
        }

        self::$typoMap = Cache::remember('zoruai.typo.map.v3', 3600, function () {
            return $this->buildTypoMap();
        });

        return self::$typoMap;
    }

    /** @return list<string> */
    private function vocabulary(): array
    {
        if (self::$vocabulary !== null) {
            return self::$vocabulary;
        }

        self::$vocabulary = Cache::remember('zoruai.typo.vocabulary', 3600, function () {
            return array_values(array_unique(array_merge(
                array_keys($this->typoMap()),
                array_values($this->typoMap()),
                $this->domainVocabulary(),
            )));
        });

        return self::$vocabulary;
    }

    /** @return array<string, string> */
    private function buildTypoMap(): array
    {
        $map = $this->baseInformalMap();

        $path = config('zoruai.training_path') . '/bahasa_indonesia_training_data.json';
        if (is_file($path)) {
            $data = json_decode((string) file_get_contents($path), true);
            foreach ($data['kosakata_dan_definisi'] ?? [] as $row) {
                $correct = Str::lower($row['koreksi_typo'] ?? $row['kata_dasar'] ?? '');
                if ($correct === '') {
                    continue;
                }
                foreach ($row['variasi_typo'] ?? [] as $typo) {
                    $map[Str::lower($typo)] = $correct;
                }
            }
        }

        $biPath = config('zoruai.training_path') . '/training_bahasa_indonesia.json';
        if (is_file($biPath)) {
            $data = json_decode((string) file_get_contents($biPath), true);
            foreach ($data['data'] ?? [] as $row) {
                $this->mergeWordPairsFromCorrection($map, $row['input'] ?? '', $row['output'] ?? '');
            }
        }

        return $map;
    }

    /** @param array<string, string> $map */
    private function mergeWordPairsFromCorrection(array &$map, string $informal, string $formal): void
    {
        $pairs = [
            ['udah', 'sudah'], ['udh', 'sudah'], ['uda', 'sudah'],
            ['bilang', 'mengatakan'], ['bilng', 'mengatakan'],
            ['telat', 'terlambat'], ['tlat', 'terlambat'],
            ['dateng', 'datang'], ['dtang', 'datang'],
            ['aktifitas', 'aktivitas'], ['aktiftas', 'aktivitas'],
            ['sistimatis', 'sistematis'], ['sistimatif', 'sistematis'],
            ['praktek', 'praktik'], ['kwalitas', 'kualitas'], ['ijin', 'izin'],
            ['gnti', 'ganti'], ['ganty', 'ganti'], ['gant', 'ganti'],
            ['hrga', 'harga'], ['harg', 'harga'],
            ['brp', 'berapa'], ['berpa', 'berapa'],
            ['pembayran', 'pembayaran'], ['pembayarn', 'pembayaran'], ['byr', 'bayar'],
            ['servce', 'servis'], ['servis', 'servis'], ['srvis', 'servis'],
            ['mkanik', 'mekanik'], ['mekanik', 'mekanik'],
            ['kasir', 'kasir'], ['ksir', 'kasir'],
            ['zeropai', 'zeropay'], ['zeropay', 'zeropay'], ['zeropayy', 'zeropay'],
            ['topup', 'topup'], ['top up', 'top up'],
            ['anlisa', 'analisa'], ['analis', 'analisa'],
            ['pendpatn', 'pendapatan'], ['pendapatan', 'pendapatan'],
            ['omzet', 'omzet'], ['omzet', 'omzet'],
            ['stok', 'stok'], ['stock', 'stok'],
            ['diskon', 'diskon'], ['dskon', 'diskon'],
            ['bengkel', 'bengkel'], ['bengkl', 'bengkel'],
            ['master', 'master'], ['mster', 'master'],
            ['sparepart', 'sparepart'], ['spareprt', 'sparepart'],
            ['tarik', 'tarik'], ['trik', 'tarik'],
            ['penarikan', 'penarikan'], ['penarikan', 'penarikan'],
            ['registrasi', 'registrasi'], ['registrsi', 'registrasi'],
            ['booking', 'booking'], ['boking', 'booking'],
            ['pelanggan', 'pelanggan'], ['plnggan', 'pelanggan'],
            ['rincian', 'rincian'], ['rincan', 'rincian'], ['rantang', 'rincian'],
            ['jelaskan', 'jelaskan'], ['jelasin', 'jelaskan'], ['jlskan', 'jelaskan'],
            ['bagaimana', 'bagaimana'], ['gimana', 'bagaimana'], ['gmna', 'bagaimana'],
            ['kenapa', 'kenapa'], ['knpa', 'kenapa'],
            ['dimana', 'dimana'], ['dmn', 'dimana'],
            ['kapan', 'kapan'], ['kpn', 'kapan'],
            ['tolong', 'tolong'], ['tlng', 'tolong'],
            ['makasih', 'terima kasih'], ['thx', 'terima kasih'],
        ];

        foreach ($pairs as [$from, $to]) {
            if (str_contains(Str::lower($informal), $from)) {
                $map[$from] = $to;
            }
        }
    }

    /** @return array<string, string> */
    private function baseInformalMap(): array
    {
        return [
            'udah' => 'sudah', 'udh' => 'sudah', 'uda' => 'sudah', 'sdh' => 'sudah',
            'gak' => 'tidak', 'nggak' => 'tidak', 'enggak' => 'tidak', 'ga' => 'tidak',
            'ngga' => 'tidak', 'tak' => 'tidak', 'ndak' => 'tidak', 'kaga' => 'tidak', 'kagak' => 'tidak',
            'bilang' => 'mengatakan', 'bilng' => 'mengatakan',
            'telat' => 'terlambat', 'tlat' => 'terlambat',
            'dateng' => 'datang', 'dtang' => 'datang',
            'aktifitas' => 'aktivitas', 'sistimatis' => 'sistematis',
            'praktek' => 'praktik', 'kwalitas' => 'kualitas', 'ijin' => 'izin',
            'gnti' => 'ganti', 'ganty' => 'ganti', 'gant' => 'ganti',
            'hrga' => 'harga', 'harg' => 'harga', 'brp' => 'berapa', 'berpa' => 'berapa',
            'brape' => 'berapa', 'brapa' => 'berapa',
            'pembayran' => 'pembayaran', 'pembayarn' => 'pembayaran', 'byr' => 'bayar',
            'servce' => 'servis', 'srvis' => 'servis', 'service' => 'servis',
            'mkanik' => 'mekanik', 'ksir' => 'kasir',
            'zeropai' => 'zeropay', 'zeropayy' => 'zeropay', 'zeropey' => 'zeropay',
            'anlisa' => 'analisa', 'analis' => 'analisa',
            'pendpatn' => 'pendapatan',
            'dskon' => 'diskon', 'bengkl' => 'bengkel',
            'spareprt' => 'sparepart', 'boking' => 'booking',
            'plnggan' => 'pelanggan', 'rincan' => 'rincian', 'rantang' => 'rincian',
            'jelasin' => 'jelaskan', 'jlskan' => 'jelaskan',
            'gimana' => 'bagaimana', 'gmna' => 'bagaimana', 'gmana' => 'bagaimana', 'gmn' => 'bagaimana', 'bgm' => 'bagaimana', 'bgmana' => 'bagaimana',
            'knpa' => 'kenapa', 'knp' => 'kenapa', 'karna' => 'karena', 'krn' => 'karena', 'krna' => 'karena',
            'dmn' => 'dimana', 'kpn' => 'kapan',
            'tlng' => 'tolong', 'makasih' => 'terima kasih', 'thx' => 'terima kasih', 'thanks' => 'terima kasih', 'makasi' => 'terima kasih', 'mksh' => 'terima kasih',
            'tanya' => 'tanya', 'tny' => 'tanya', 'tnyakan' => 'tanyakan',
            'pengertian' => 'pengertian', 'pngertian' => 'pengertian',
            'penjelasan' => 'penjelasan', 'penjlasan' => 'penjelasan',
            'maksud' => 'maksud', 'mksud' => 'maksud',
            'arti' => 'arti', 'kta' => 'kata',
            'kalimat' => 'kalimat', 'klimat' => 'kalimat',
            'ejaan' => 'ejaan', 'bantu' => 'bantu', 'bntu' => 'bantu',
            'bantuan' => 'bantuan', 'bntuan' => 'bantuan',
            'kalo' => 'kalau', 'klo' => 'kalau', 'pake' => 'pakai', 'pke' => 'pakai',
            'buat' => 'untuk', 'bwt' => 'untuk', 'utk' => 'untuk',
            'dengan' => 'dengan', 'dgn' => 'dengan', 'dari' => 'dari', 'dr' => 'dari',
            'pada' => 'pada', 'pd' => 'pada', 'siapa' => 'siapa', 'sapa' => 'siapa', 'sp' => 'siapa',
            'bisa' => 'bisa', 'bs' => 'bisa', 'sangat' => 'sangat', 'sngat' => 'sangat', 'sngt' => 'sangat',
            'bgt' => 'sangat', 'banget' => 'sangat', 'bngt' => 'sangat',
            'apa' => 'apa', 'ap' => 'apa', 'pa' => 'apa',
            'gw' => 'saya', 'gue' => 'saya', 'gua' => 'saya', 'sy' => 'saya',
            'lu' => 'kamu', 'lo' => 'kamu', 'elu' => 'kamu', 'elo' => 'kamu', 'kmu' => 'kamu', 'km' => 'kamu',
            'belum' => 'belum', 'blm' => 'belum', 'belom' => 'belum',
            'hiddengem' => 'hidden gem', 'hiden gem' => 'hidden gem', 'hiden' => 'hidden gem',
            'smeuanya' => 'semuanya', 'rombak' => 'rombak', 'maish' => 'masih',
            'kayak' => 'seperti', 'sekring' => 'sekarang', 'sekrg' => 'sekarang',
            'sya' => 'saya', 'say' => 'saya', 'alihat' => 'lihat',
            'pintarkan' => 'pintar', 'pintar' => 'pintar',
            'atentram' => 'tenteram', 'dna' => 'dan', 'bertinglah' => 'bertingkah',
            'apapun' => 'apa pun',
        ];
    }

    /** @return list<string> */
    private function domainVocabulary(): array
    {
        return [
            'zeropay', 'topup', 'booking', 'pembayaran', 'servis', 'mekanik', 'kasir',
            'pelanggan', 'owner', 'saldo', 'tarik', 'penarikan', 'registrasi', 'master',
            'sparepart', 'analisa', 'pendapatan', 'omzet', 'diskon', 'stok', 'bengkel',
            'milky', 'garage', 'ganti', 'harga', 'pin', 'invoice', 'qris', 'tunai',
            'rincian', 'jelaskan', 'pengertian', 'bantuan', 'panduan', 'gaji',
        ];
    }

    private function preserveCase(string $original, string $fixed): string
    {
        if ($original === strtoupper($original)) {
            return strtoupper($fixed);
        }
        if ($original === ucfirst(Str::lower($original))) {
            return ucfirst($fixed);
        }

        return $fixed;
    }

    /** @param list<array{from: string, to: string}> $corrections
     * @return list<array{from: string, to: string}>
     */
    private function uniqueCorrections(array $corrections): array
    {
        $seen = [];
        $unique = [];
        foreach ($corrections as $item) {
            $key = $item['from'] . '→' . $item['to'];
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $item;
        }

        return $unique;
    }

    public function stripIndonesianSuffixes(string $word): string
    {
        $word = Str::lower($word);

        // 1. Strip particle suffixes: -lah, -kah, -pun
        if (strlen($word) > 5) {
            if (str_ends_with($word, 'lah') || str_ends_with($word, 'kah') || str_ends_with($word, 'pun')) {
                $word = substr($word, 0, -3);
            }
        }

        // 2. Strip possessive pronouns: -ku, -mu, -nya
        if (strlen($word) > 4) {
            if (str_ends_with($word, 'ku') || str_ends_with($word, 'mu')) {
                $word = substr($word, 0, -2);
            }
        }

        // Strip -nya suffix, ensuring it is not a protected root word like 'tanya', 'hanya', 'punya'
        if (strlen($word) > 5 && str_ends_with($word, 'nya')) {
            $protectedNya = ['tanya', 'hanya', 'punya', 'senyawa', 'nyawa', 'sunya'];
            if (!in_array($word, $protectedNya, true)) {
                $word = substr($word, 0, -3);
            }
        }

        return $word;
    }
}
