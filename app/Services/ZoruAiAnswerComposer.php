<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class ZoruAiAnswerComposer
{
    public function tryUtilityAnswer(string $query, User $user): ?string
    {
        $q = Str::lower($query);

        if ($this->looksAmbiguous($q)) {
            return 'Halo :name! Saya sangat ingin membantu Anda dengan tepat. Agar pikiran Anda tenang dan jawabannya pas, bisakah Anda memperjelas apakah yang dimaksud adalah tentang ZeroPay, booking servis, atau master data Milky Garage? Mari kita selesaikan bersama dengan senang hati.';
        }

        if ($this->asksCurrentInformation($q)) {
            return 'Terima kasih banyak atas pertanyaannya, :name. Saya fokus pada data yang tersedia di Milky Garage. Untuk seluruh operasional bengkel seperti ZeroPay, booking servis, dan master data, saya siap memandu Anda kapan saja.';
        }

        if ($this->asksPersonalFeeling($q)) {
            return 'Terima kasih atas perhatian Anda yang begitu hangat dan tulus, :name. Saya hadir sebagai pendamping layanan Milky Garage. Saya akan menjawab dengan hangat, jelas, dan sopan agar pengelolaan bengkel terasa lebih tenang.';
        }

        $calculation = $this->tryPercentageCalculation($q);
        if ($calculation !== null) {
            return $calculation;
        }

        return null;
    }

    public function fallback(string $query, User $user): string
    {
        $q = Str::lower($query);
        $topics = $this->suggestedTopics($q, $user);

        if (preg_match('/\b(motor|mesin|oli|rem|ban|aki|busi|servis|service|rusak|mogok|bunyi)\b/u', $q)) {
            $intro = "Tenang :name, tidak perlu khawatir mengenai masalah kendala motor Anda. Di Milky Garage, keselamatan dan kenyamanan berkendara Anda adalah prioritas utama kami.\n\n"
                . "Meskipun saat ini saya belum menemukan catatan spesifik untuk pertanyaan tersebut, mari kita selesaikan bersama dengan memeriksa panduan praktis berikut:";
        } elseif (preg_match('/\b(zero|zeropay|saldo|topup|bayar|pembayaran|withdraw|tarik|duit|uang)\b/u', $q)) {
            $intro = "Terkait transaksi keuangan Anda, :name, keamanan dan transparansi adalah fokus utama Milky Garage demi kenyamanan Anda.\n\n"
                . "Meskipun detail pembayaran tersebut belum teridentifikasi sempurna saat ini, berikut beberapa panduan transaksi yang bisa saya bantu:";
        } else {
            $intro = "Halo :name! Terima kasih atas sapaan hangat Anda. Sebagai asisten luring di Milky Garage, saya senang sekali menemani Anda hari ini.\n\n"
                . "Saya belum menemukan kecocokan catatan panduan untuk topik tersebut, tetapi jangan khawatir, mari periksa panduan bantuan berikut:";
        }

        return $intro . "\n\n"
            . $this->formatBullets($topics)
            . "\n\n**Tips Hangat**: Anda bisa mencoba menuliskan pertanyaan dengan kata kunci yang lebih spesifik seperti \"cara booking servis\", \"rincian zeropay\", atau \"siapa pembuat sistem\" agar saya bisa merespons dengan lebih presisi. Tetap tenang :name, kami selalu di sini untuk melayani Anda dengan penuh dedikasi!";
    }

    /** @return list<string> */
    private function suggestedTopics(string $q, User $user): array
    {
        if (preg_match('/\b(motor|mesin|oli|rem|ban|aki|busi|servis|service)\b/u', $q)) {
            return [
                'Diagnosa ringan masalah motor',
                'Perawatan oli, rem, aki, ban, busi, dan rantai',
                'Estimasi umum servis motor dari data training bengkel',
            ];
        }

        if (preg_match('/\b(zero|zeropay|saldo|topup|bayar|pembayaran|withdraw|tarik)\b/u', $q)) {
            return [
                'Penjelasan ZeroPay',
                'Alur top-up dan pembayaran servis',
                $user->isRole('owner') ? 'Detail internal penarikan dan konfigurasi owner' : 'Panduan umum tanpa membuka informasi internal owner',
            ];
        }

        if ($user->isRole('owner')) {
            return [
                'Ringkasan omzet dan performa bengkel',
                'Master data servis dan sparepart',
                'Perintah owner seperti ubah harga, diskon, dan restock',
            ];
        }

        return [
            'Booking servis dan status pengerjaan',
            'Pembayaran, invoice, dan riwayat akun',
            'Pertanyaan bengkel motor atau bahasa Indonesia sederhana',
        ];
    }

    /** @param list<string> $items */
    private function formatBullets(array $items): string
    {
        return implode("\n", array_map(fn(string $item) => '- ' . $item, $items));
    }

    private function looksAmbiguous(string $q): bool
    {
        return (bool) preg_match('/^(itu|ini|yang tadi|bagaimana dengan itu|gimana dengan itu|lanjut|lanjutkan|maksudnya)\tab*$/u', trim($q))
            || (bool) preg_match('/^(itu|ini|yang tadi|bagaimana dengan itu|gimana dengan itu|lanjut|lanjutkan|maksudnya)\??$/u', trim($q));
    }

    private function asksCurrentInformation(string $q): bool
    {
        if (preg_match('/\b(booking|servis|service|zeropay|saldo|harga|pembayaran|omzet|pendapatan|master\s*data)\b/u', $q)) {
            return false;
        }

        return (bool) preg_match('/\b(berita|pemilu|cuaca|saham|crypto|film|terbaru|real\s*time|update|lagi hits|menang pemilu)\b/u', $q)
            || (bool) preg_match('/\b(siapa|apa|berapa)\b.*\b(hari ini|kemarin|sekarang)\b/u', $q);
    }

    private function asksPersonalFeeling(string $q): bool
    {
        return (bool) preg_match('/\b(kamu|anda)\b.*\b(sedih|senang|kesepian|takut|marah|punya perasaan|merasa)\b/u', $q);
    }

    private function tryPercentageCalculation(string $q): ?string
    {
        if (! preg_match('/([\d.,]+)\s*%\s*(?:dari|x|kali)\s*([\d.,]+)/u', $q, $m)) {
            return null;
        }

        $percent = (float) str_replace(',', '.', $m[1]);
        $base = (float) str_replace(',', '.', $m[2]);
        $result = $base * ($percent / 100);
        $formattedBase = $this->formatNumber($base);
        $formattedResult = $this->formatNumber($result);

        return "{$percent}% dari {$formattedBase} adalah {$formattedResult}.\n\nRumusnya: {$formattedBase} x {$percent}/100 = {$formattedResult}.";
    }

    private function formatNumber(float $value): string
    {
        $decimals = fmod($value, 1.0) === 0.0 ? 0 : 2;

        return number_format($value, $decimals, ',', '.');
    }
}

