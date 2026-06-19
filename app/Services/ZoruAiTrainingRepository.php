<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ZoruAiTrainingRepository
{
    /** @var list<array{keywords: list<string>, output: string, priority: int, visibility?: string}>|null */
    private static ?array $index = null;

    public function findAnswer(string $query, ?string $normalizedQuery = null, ?string $role = null): ?string
    {
        $normalizedQuery ??= $query;
        $best = $this->matchBest($normalizedQuery, $role);
        if ($best !== null) {
            return $best;
        }

        if ($normalizedQuery !== $query) {
            return $this->matchBest($query, $role);
        }

        return null;
    }

    public function findBahasaFormalAnswer(string $query, ?string $normalizedQuery = null): ?string
    {
        $normalizedQuery ??= $query;
        $tokens = $this->tokenize($normalizedQuery);
        if ($tokens === []) {
            return null;
        }

        $bestScore = 0.0;
        $bestOutput = null;

        foreach ($this->entries() as $entry) {
            if (($entry['priority'] ?? 0) < 2) {
                continue;
            }
            $score = $this->score($tokens, $entry['keywords']);
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestOutput = $entry['output'];
            }
        }

        $threshold = (float) config('zoruai.bahasa_formal_threshold', 0.3);

        return $bestScore >= $threshold ? $bestOutput : null;
    }

    public function shouldUseSystemPricing(string $query): bool
    {
        $q = Str::lower($query);

        if (preg_match('/\b(pin|kode\s*pin|kode\s*verifikasi|kode\s*role|saldo)\b/u', $q)) {
            return false;
        }

        if (preg_match('/\b(milky|zeropay|master\s*data|katalog|sistem|bengkel\s*(ini|kami)?)\b/u', $q)) {
            return (bool) preg_match('/\b(harga|biaya|tarif|berapa)\b/u', $q);
        }

        return (bool) preg_match(
            '/\b(berapa|harga|biaya|tarif)\b.*\b(servis|oli|ganti|rem|tune|sparepart|spare\s*part)\b/u',
            $q
        ) || (bool) preg_match(
            '/\b(servis|oli|ganti\s*oli|sparepart)\b.*\b(harga|biaya|tarif|berapa)\b/u',
            $q
        );
    }

    private function matchBest(string $query, ?string $role = null): ?string
    {
        $query = Str::lower(trim($query));
        if ($query === '') {
            return null;
        }

        $tokens = $this->tokenize($query);
        if ($tokens === []) {
            return null;
        }

        $bestScore = 0.0;
        $bestPriority = -1;
        $bestOutput = null;
        $secondScore = 0.0;
        $secondPriority = -1;

        foreach ($this->entries() as $entry) {
            if (! $this->isVisibleToRole($entry, $role)) {
                continue;
            }

            $score = $this->score($tokens, $entry['keywords']);
            $priority = $entry['priority'] ?? 0;
            if ($score > $bestScore || ($score === $bestScore && $priority > $bestPriority)) {
                $secondScore = $bestScore;
                $secondPriority = $bestPriority;
                $bestScore = $score;
                $bestPriority = $priority;
                $bestOutput = $entry['output'];
                continue;
            }

            if ($score > $secondScore) {
                $secondScore = $score;
                $secondPriority = $priority;
            }
        }

        $threshold = (float) config('zoruai.training_match_threshold', 0.35);

        if ($bestScore < $threshold) {
            return null;
        }

        if ($bestScore < 0.68 && $secondScore > 0 && ($bestScore - $secondScore) < 0.12) {
            // Hanya anggap bentrok (ambigu) jika entri terbaik berprioritas rendah (<= 2),
            // atau jika entri kedua memiliki prioritas yang setara/lebih tinggi dari entri terbaik.
            if ($bestPriority <= 2 || $secondPriority >= $bestPriority) {
                return null;
            }
        }

        return $bestOutput;
    }

    /** @return list<array{keywords: list<string>, output: string, priority: int}> */
    private function entries(): array
    {
        if (self::$index !== null) {
            return self::$index;
        }

        self::$index = Cache::remember('zoruai.training.index.v12', 3600, function () {
            return $this->buildIndex();
        });

        return self::$index;
    }

    /** @return list<array{keywords: list<string>, output: string, priority: int}> */
    private function buildIndex(): array
    {
        $entries = [];
        $base = config('zoruai.training_path');

        $this->loadBengkelMotor($base . '/training_data_bengkel_motor.json', $entries);
        $this->loadMilkyGarageSystem($base . '/training_data_milky_garage_system.json', $entries);
        $this->loadDialogJson($base . '/training_dialog_examples_milky_garage.json', $entries, 6);
        $this->loadDialogJson($base . '/training_error_handling.json', $entries, 7);
        $this->loadPromptUnderstanding($base . '/ai_training_data.json', $entries);
        $this->loadBahasaIndonesiaJson($base . '/training_bahasa_indonesia.json', $entries);
        $this->loadBahasaIndonesiaStructured($base . '/bahasa_indonesia_training_data.json', $entries);
        $this->loadKbbiJsonl($base . '/kbbi_training_data.jsonl', $entries);
        $this->loadDialogJsonl($base . '/dialog_percakapan_ai_indonesia.jsonl', $entries);

        return $entries;
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadMilkyGarageSystem(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $prompt = trim(($row['instruction'] ?? '') . ' ' . ($row['input'] ?? ''));
            $output = trim($row['output'] ?? '');
            $this->pushEntry($entries, [
                'keywords' => $this->tokenize($prompt),
                'output' => $output,
                'priority' => 5,
                'visibility' => $row['visibility'] ?? 'all',
            ]);
        }
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadDialogJson(string $path, array &$entries, int $priority): void
    {
        if (! is_file($path)) {
            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $messages = $row['messages'] ?? [];
            $user = '';
            $assistant = '';
            foreach ($messages as $message) {
                if (($message['role'] ?? '') === 'user' && $user === '') {
                    $user = trim($message['content'] ?? '');
                }
                if (($message['role'] ?? '') === 'assistant' && $assistant === '') {
                    $assistant = trim($message['content'] ?? '');
                }
            }

            $context = trim(($row['context'] ?? '') . ' ' . ($row['error_type'] ?? '') . ' ' . ($row['system_integration'] ?? ''));
            $this->pushEntry($entries, [
                'keywords' => $this->tokenize(trim($user . ' ' . $context)),
                'output' => $assistant,
                'priority' => $priority,
                'visibility' => $row['role'] ?? 'all',
            ]);
        }
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadPromptUnderstanding(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (! is_array($data)) {
            return;
        }

        foreach ($data['training_examples'] ?? [] as $row) {
            $prompt = trim(($row['category'] ?? '') . ' ' . ($row['prompt'] ?? ''));
            $output = trim($row['expected_response'] ?? '');
            $this->pushEntry($entries, [
                'keywords' => $this->tokenize($prompt),
                'output' => $output,
                'priority' => 1,
                'visibility' => $row['visibility'] ?? 'all',
            ]);
        }
    }

    /**
     * @param list<array{keywords: list<string>, output: string, priority: int}> $entries
     * @param array{keywords: list<string>, output: string, priority?: int, visibility?: string} $entry
     */
    private function pushEntry(array &$entries, array $entry): void
    {
        if ($entry['keywords'] === [] || trim($entry['output']) === '') {
            return;
        }
        $entries[] = [
            'keywords' => $entry['keywords'],
            'output' => trim($this->cleanText($entry['output'])),
            'priority' => $entry['priority'] ?? 0,
            'visibility' => $entry['visibility'] ?? 'all',
        ];
    }

    /** @param array{visibility?: string} $entry */
    private function isVisibleToRole(array $entry, ?string $role): bool
    {
        $visibility = $entry['visibility'] ?? 'all';

        if ($visibility === 'all' || $visibility === '') {
            return true;
        }

        $allowed = array_map('trim', explode('|', $visibility));

        return $role !== null && in_array($role, $allowed, true);
    }

    private function cleanText(string $text): string
    {
        $text = strtr($text, [
            'â€”' => '-',
            'â€“' => '-',
            'â€¢' => '-',
            'â†’' => '->',
            'â‰¤' => '<=',
            'â‰¥' => '>=',
            'â€˜' => "'",
            'â€™' => "'",
            'â€œ' => '"',
            'â€' => '"',
            'â€¦' => '...',
            'ðŸ¤–' => 'ZoruAi',
        ]);
        $text = strtr($text, [
            'â€”' => '-',
            'â€“' => '-',
            'â€¢' => '-',
            'â†’' => '->',
            'â‰¤' => '<=',
            'â‰¥' => '>=',
        ]);
        $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/[ \t]*\n[ \t]*/u', "\n", $text) ?? $text;
        $text = preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text;

        return trim($text);
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadBengkelMotor(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $rows = json_decode((string) file_get_contents($path), true);
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $prompt = trim(($row['instruction'] ?? '') . ' ' . ($row['input'] ?? ''));
            $output = trim($row['output'] ?? '');
            $this->pushEntry($entries, [
                'keywords' => $this->tokenize($prompt),
                'output' => $output,
                'priority' => 1,
            ]);
        }
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadBahasaIndonesiaJson(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $data = json_decode((string) file_get_contents($path), true);
        $rows = $data['data'] ?? [];
        if (! is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $instruction = trim($row['instruksi'] ?? '');
            $input = trim($row['input'] ?? '');
            $output = trim($row['output'] ?? '');
            $penjelasan = trim($row['penjelasan'] ?? '');
            if ($output === '') {
                continue;
            }
            $fullOutput = $penjelasan !== ''
                ? $output . "\n\n" . $penjelasan
                : $output;

            $this->pushEntry($entries, [
                'keywords' => $this->tokenize($instruction . ' ' . $input),
                'output' => $fullOutput,
                'priority' => 1,
            ]);

            if ($input !== '') {
                $this->pushEntry($entries, [
                    'keywords' => array_values(array_unique(array_merge(
                        $this->tokenize($input),
                        $this->tokenize($instruction),
                    ))),
                    'output' => $fullOutput,
                    'priority' => 3,
                ]);
            }
        }
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadBahasaIndonesiaStructured(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (! is_array($data)) {
            return;
        }

        foreach ($data['kosakata_dan_definisi'] ?? [] as $row) {
            $word = $row['kata_dasar'] ?? '';
            $output = "**{$word}** (" . ($row['kelas_kata'] ?? '') . ")\n\n"
                . ($row['definisi'] ?? '')
                . "\n\nSinonim: " . implode(', ', $row['sinonim'] ?? [])
                . "\nAntonim: " . implode(', ', $row['antonim'] ?? []);
            $prompts = array_merge(
                ["apa arti {$word}", "makna kata {$word}", "sinonim {$word}", "antonim {$word}"],
                $row['variasi_typo'] ?? []
            );
            foreach ($prompts as $p) {
                $this->pushEntry($entries, [
                    'keywords' => $this->tokenize($p),
                    'output' => $output,
                    'priority' => 2,
                ]);
            }
        }

        foreach ($data['tata_bahasa_struktur_kalimat'] ?? [] as $row) {
            $topic = $row['kategori'] ?? '';
            $output = trim(($row['penjelasan'] ?? '') . "\n\n" . ($row['kalimat_output'] ?? ''));
            $prompt = trim($topic . ' ' . ($row['kalimat_input'] ?? ''));
            if ($prompt !== '' && $output !== '') {
                $this->pushEntry($entries, [
                    'keywords' => $this->tokenize($prompt),
                    'output' => $output,
                    'priority' => 2,
                ]);
            }
        }

        foreach ($data['konteks_penggunaan_kata'] ?? [] as $row) {
            $word = $row['kata'] ?? '';
            $lines = [($row['cara_membedakan'] ?? '')];
            foreach ($row['makna_berdasarkan_konteks'] ?? [] as $ctx) {
                $lines[] = '• ' . ($ctx['konteks'] ?? '') . ' → ' . ($ctx['makna'] ?? '');
            }
            $output = trim(implode("\n", array_filter($lines)));
            $prompt = "makna kata {$word} dalam konteks";
            if ($word !== '' && $output !== '') {
                $this->pushEntry($entries, [
                    'keywords' => $this->tokenize($prompt),
                    'output' => $output,
                    'priority' => 2,
                ]);
            }
        }
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadKbbiJsonl(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return;
        }

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $row = json_decode($line, true);
            $messages = $row['messages'] ?? [];
            $user = '';
            $assistant = '';
            foreach ($messages as $msg) {
                if (($msg['role'] ?? '') === 'user') {
                    $user = trim($msg['content'] ?? '');
                }
                if (($msg['role'] ?? '') === 'assistant') {
                    $assistant = trim($msg['content'] ?? '');
                }
            }
            if ($user !== '' && $assistant !== '') {
                $this->pushEntry($entries, [
                    'keywords' => $this->tokenize($user),
                    'output' => $assistant,
                    'priority' => 2,
                ]);
            }
        }

        fclose($handle);
    }

    /** @param list<array{keywords: list<string>, output: string, priority: int}> $entries */
    private function loadDialogJsonl(string $path, array &$entries): void
    {
        if (! is_file($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return;
        }

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $row = json_decode($line, true);
            if (! is_array($row)) {
                continue;
            }

            $messages = $row['messages'] ?? [];
            $user = '';
            $assistant = '';
            foreach ($messages as $msg) {
                if (($msg['role'] ?? '') === 'user' && $user === '') {
                    $user = trim($msg['content'] ?? '');
                }
                if (($msg['role'] ?? '') === 'assistant' && $assistant === '') {
                    $assistant = trim($msg['content'] ?? '');
                }
            }

            if ($user !== '' && $assistant !== '') {
                $this->pushEntry($entries, [
                    'keywords' => $this->tokenize($user),
                    'output' => $this->personalizeDialogOutput($assistant),
                    'priority' => 4,
                ]);
            }
        }

        fclose($handle);
    }

    private function personalizeDialogOutput(string $output): string
    {
        $output = $this->cleanText($output);
        $output = str_replace('AI Asisten', 'ZoruAi', $output);
        $output = str_replace('asisten AI', 'ZoruAi', $output);
        $output = str_replace('Aku adalah ZoruAi', 'Saya ZoruAi', $output);

        return $output;
    }

    /** @return list<string> */
    private function tokenize(string $text): array
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? $text;
        $parts = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $stopwords = [
            'yang', 'dan', 'atau', 'di', 'ke', 'dari', 'pada', 'untuk', 'dengan', 'ini', 'itu',
            'apa', 'bagaimana', 'tolong', 'mohon', 'the', 'a', 'an',
            'adalah', 'akan', 'bisa', 'dapat', 'harus', 'juga', 'saja', 'oleh',
            'saya', 'anda', 'kamu', 'aku', 'dia', 'mereka', 'kami', 'kita',
        ];

        $normalizer = app(ZoruAiPromptNormalizer::class);
        $tokens = [];
        foreach ($parts as $part) {
            $part = $normalizer->stripIndonesianSuffixes($part);
            if (strlen($part) < 2 || in_array($part, $stopwords, true)) {
                continue;
            }
            $tokens[] = $part;
        }

        return array_values(array_unique($tokens));
    }

    private function getKeywordWeight(string $keyword): float
    {
        $keyword = Str::lower($keyword);

        $high = [
            'zeropay', 'hiddengem', 'aefzetaa', 'booking', 'withdraw', 'gantioli',
            'overhaul', 'restock', 'pencipta', 'pembuat', 'developer', 'owner2026',
            'mech2026', 'cash2026', 'pgteam', 'pgdev'
        ];

        $med = [
            'saldo', 'gaji', 'omzet', 'diskon', 'owner', 'mekanik', 'kasir',
            'pelanggan', 'sparepart', 'servis', 'service', 'oli', 'rem',
            'busi', 'aki', 'ban', 'bengkel', 'milky', 'garage', 'analisa',
            'analisis', 'performa', 'pendapatan', 'tarik', 'penarikan',
            'registrasi', 'login', 'daftar', 'akun'
        ];

        $low = [
            'berapa', 'harga', 'biaya', 'tarif', 'cara', 'bisa', 'bantu',
            'apa', 'gimana', 'ada', 'kamu', 'anda', 'siapa', 'kenapa',
            'dimana', 'kapan', 'bagaimana', 'tolong', 'halo', 'hai'
        ];

        if (in_array($keyword, $high, true)) {
            return 3.0;
        }
        if (in_array($keyword, $med, true)) {
            return 1.5;
        }
        if (in_array($keyword, $low, true)) {
            return 0.8;
        }

        return 1.0;
    }

    /** @param list<string> $queryTokens @param list<string> $entryKeywords */
    private function score(array $queryTokens, array $entryKeywords): float
    {
        if ($entryKeywords === []) {
            return 0.0;
        }

        $entryKeywords = array_values(array_unique($entryKeywords));
        $queryTokens = array_values(array_unique($queryTokens));

        $totalEntryWeight = 0.0;
        foreach ($entryKeywords as $keyword) {
            $totalEntryWeight += $this->getKeywordWeight($keyword);
        }

        $totalQueryWeight = 0.0;
        foreach ($queryTokens as $token) {
            $totalQueryWeight += $this->getKeywordWeight($token);
        }

        $matchedWeight = 0.0;
        $matchedEntryIndices = [];

        foreach ($queryTokens as $token) {
            $foundIndex = array_search($token, $entryKeywords, true);
            if ($foundIndex !== false) {
                if (! in_array($foundIndex, $matchedEntryIndices, true)) {
                    $matchedWeight += $this->getKeywordWeight($entryKeywords[$foundIndex]);
                    $matchedEntryIndices[] = $foundIndex;
                }
                continue;
            }

            foreach ($entryKeywords as $index => $keyword) {
                if (in_array($index, $matchedEntryIndices, true)) {
                    continue;
                }
                if (
                    strlen($token) >= 4
                    && strlen($keyword) >= 4
                    && abs(strlen($token) - strlen($keyword)) <= 2
                    && (str_contains($keyword, $token) || str_contains($token, $keyword))
                ) {
                    $matchedWeight += $this->getKeywordWeight($keyword);
                    $matchedEntryIndices[] = $index;
                    break;
                }
            }
        }

        if ($matchedWeight === 0.0) {
            return 0.0;
        }

        $hits = count($matchedEntryIndices);
        $queryCount = count($queryTokens);

        if ($queryCount >= 4 && $hits < 2) {
            return 0.0;
        }

        if ($queryCount >= 7 && $hits < 3) {
            return 0.0;
        }

        $coverage = $matchedWeight / max(1.0, $totalQueryWeight);
        $specificity = $matchedWeight / max(1.0, $totalEntryWeight);

        if ($queryCount <= 2) {
            return ($coverage * 0.3) + ($specificity * 0.7);
        }

        return ($coverage * 0.7) + ($specificity * 0.3);
    }
}
