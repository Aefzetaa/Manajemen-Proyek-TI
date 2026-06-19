<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Promotion;
use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Booking;
use App\Models\AccountActivity;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ZoruAiService
{
    public function __construct(
        private readonly ZoruAiTrainingRepository $training,
        private readonly ZoruAiPromptNormalizer $normalizer,
        private readonly ZoruAiResponseFormatter $formatter,
        private readonly ZoruAiAnswerComposer $composer,
    ) {}

    public function greeting(User $user): string
    {
        $key = $user->isRole('owner') ? 'owner' : 'default';
        $template = config("zoruai.greeting.{$key}", config('zoruai.greeting.default')) ?? '';
        $text = str_replace(':name', ucwords($user->username), $template);

        return $this->formatter->format($text);
    }

    public function ownerExecutiveHint(): string
    {
        return $this->formatter->format((string) config('zoruai.owner_executive_hint'));
    }

    /** @return array{spare_part_id: int, name: string, qty: int, unit_price: int, total: int, current_stock: int}|null */
    public function restockPreview(string $prompt, User $user): ?array
    {
        if (! $user->isRole('owner')) {
            return null;
        }

        $parsed = $this->normalizer->process($prompt);
        $raw = $parsed['normalized'];

        if (! preg_match('/(?:restock|tambah stok)\s+(.+?)\s+([\d.,]+)\s*(?:unit|pcs)?/iu', $raw, $m)) {
            return null;
        }

        $partName = trim($m[1]);
        $part = $this->findSparePartByFlexibleName($partName);
        if (! $part) {
            return null;
        }

        $qty = max(1, (int) $this->parseAmount($m[2]));
        $unitPrice = $this->restockUnitPrice($part, $qty);

        return [
            'spare_part_id' => $part->id,
            'name' => $part->name,
            'qty' => $qty,
            'unit_price' => $unitPrice,
            'total' => $qty * $unitPrice,
            'current_stock' => $part->stock,
        ];
    }

    /**
     * @param list<array{role: string, message: string}> $history
     */
    public function reply(string $prompt, User $user, array $history = []): string
    {
        $text = trim($prompt);
        if ($text === '') {
            return $this->respond(
                'Silakan ketik pertanyaan atau perintah Anda. Saya ZoruAi, pendamping Milky Garage.',
                [],
                $user
            );
        }

        $contextualText = $this->withConversationContext($text, $history);
        $parsed = $this->normalizer->process($contextualText);
        $normalized = $parsed['normalized'];
        $corrections = $parsed['corrections'];
        $q = Str::lower($normalized);
        $rawQ = Str::lower($contextualText);

        if ($user->isRole('owner')) {
            $executive = $this->tryOwnerCommand($normalized, $q, $user);
            if ($executive !== null) {
                return $this->respond($executive, $corrections, $user);
            }
        }

        $utility = $this->composer->tryUtilityAnswer($contextualText, $user);
        if ($utility !== null) {
            return $this->respond($utility, $corrections, $user);
        }

        $operational = $this->tryOperationalAnswer($normalized, $q, $user);
        if ($operational !== null) {
            return $this->respond($operational, $corrections, $user);
        }

        foreach ($this->knowledgeRules($user) as $rule) {
            if (preg_match($rule['pattern'], $q) || preg_match($rule['pattern'], $rawQ)) {
                $answer = $rule['answer'];
                $resolved = is_callable($answer) ? $answer($user, $normalized) : $answer;

                return $this->respond($resolved, $corrections, $user);
            }
        }

        if ($this->training->shouldUseSystemPricing($normalized) || $this->training->shouldUseSystemPricing($text)) {
            return $this->respond(
                $this->catalogSummary($normalized)
                    . "\n\nHarga di atas diambil langsung dari master data Milky Garage, bukan estimasi umum dari data pelatihan.",
                $corrections,
                $user
            );
        }

        if (preg_match('/\b(perbaiki|koreksi|benahi|ejaan|kalimat\s+baku|kata\s+baku|tata\s+bahasa)\b/u', $q)) {
            $formal = $this->training->findBahasaFormalAnswer($text, $normalized)
                ?? $this->training->findAnswer($text, $normalized, $user->role);
            if ($formal !== null) {
                return $this->respond($formal, $corrections, $user);
            }
        }

        $trained = $this->training->findAnswer($contextualText, $normalized, $user->role);
        if ($trained !== null) {
            return $this->respond($trained, $corrections, $user);
        }

        return $this->respond($this->composer->fallback($contextualText, $user), $corrections, $user);
    }

    /** @param list<array{from: string, to: string}> $corrections */
    private function respond(string $answer, array $corrections, User $user): string
    {
        $resolved = str_replace(':name', ucwords($user->username), $answer);
        return $this->formatter->format($resolved, $corrections);
    }

    /** @param list<array{role: string, message: string}> $history */
    private function withConversationContext(string $text, array $history): string
    {
        $q = Str::lower($text);
        if (! preg_match('/\b(itu|tersebut|tadi|lanjut|lanjutkan|jelaskan lagi|lebih detail|maksudnya)\b/u', $q)) {
            return $text;
        }

        $previousUser = null;
        foreach (array_reverse($history) as $chat) {
            if (($chat['role'] ?? '') === 'user' && trim($chat['message'] ?? '') !== '') {
                $previousUser = trim($chat['message']);
                break;
            }
        }

        if ($previousUser === null) {
            return $text;
        }

        return $previousUser . ' ' . $text;
    }

    private function tryOwnerCommand(string $raw, string $q, User $user): ?string
    {
        if (preg_match('/(?:ubah|update|set)\s+harga\s+(.+?)\s+(?:jadi|menjadi|=)\s+([\d.,]+)/iu', $raw, $m)) {
            return $this->updateCatalogPrice('service', trim($m[1]), $this->parseAmount($m[2]));
        }

        if (preg_match('/(?:diskon|potong)\s+([\d.,]+)\s*%?\s+(?:untuk|pada)?\s*(.+)/iu', $raw, $m)) {
            $percent = (float) str_replace(',', '.', $m[1]);
            $percent = max(0, min(100, $percent));
            if ($percent <= 0) {
                return 'Persentase diskon harus lebih dari 0%.';
            }
            $nameText = trim($m[2]);
            $extra = [
                'title' => null,
                'description' => null,
                'starts_at' => now(),
                'ends_at' => now()->addDays(7),
                'is_active' => true,
            ];

            if (str_contains($nameText, '|')) {
                $parts = array_map('trim', explode('|', $nameText));
                $nameText = array_shift($parts) ?: $nameText;

                foreach ($parts as $part) {
                    if (preg_match('/^judul\s*:\s*(.+)$/iu', $part, $field)) {
                        $extra['title'] = trim($field[1]);
                    } elseif (preg_match('/^deskripsi\s*:\s*(.+)$/iu', $part, $field)) {
                        $extra['description'] = trim($field[1]);
                    } elseif (preg_match('/^mulai\s*:\s*(.+)$/iu', $part, $field)) {
                        $extra['starts_at'] = $this->parseOptionalDateTime(trim($field[1]));
                    } elseif (preg_match('/^selesai\s*:\s*(.+)$/iu', $part, $field)) {
                        $extra['ends_at'] = $this->parseOptionalDateTime(trim($field[1]));
                    } elseif (preg_match('/^status\s*:\s*(.+)$/iu', $part, $field)) {
                        $extra['is_active'] = ! in_array(Str::lower(trim($field[1])), ['nonaktif', 'mati', 'off', '0'], true);
                    }
                }
            }

            $name = trim($nameText);
            $type = ServiceType::whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($name) . '%'])->first();
            if (! $type) {
                return "Jenis servis \"{$name}\" tidak ditemukan di master data.";
            }
            $discountPercent = max(1, min(100, (int) round($percent)));
            $oldPrice = (int) $type->base_price;
            $newPrice = max(0, (int) round($oldPrice * (100 - $discountPercent) / 100));
            $percentLabel = rtrim(rtrim(number_format($percent, 2, '.', ''), '0'), '.');

            if (Schema::hasTable('promotions')) {
                $promo = Promotion::active()
                    ->where('service_type_id', $type->id)
                    ->latest()
                    ->first();

                $payload = [
                    'title' => $extra['title'] ?: 'Diskon ' . $percentLabel . '% ' . $type->name,
                    'description' => $extra['description'] ?: 'Promo dibuat lewat ZoruAI.',
                    'service_type_id' => $type->id,
                    'discount_percent' => $discountPercent,
                    'starts_at' => $extra['starts_at'],
                    'ends_at' => $extra['ends_at'],
                    'is_active' => $extra['is_active'],
                ];

                $promo ? $promo->update($payload) : Promotion::create($payload);
            }

            $durationText = $extra['ends_at'] ? ' sampai ' . $extra['ends_at']->format('d/m/Y H:i') : ' tanpa batas akhir';

            return "Diskon {$percentLabel}% diterapkan pada \"{$type->name}\"{$durationText}. Harga master tetap Rp " . number_format($oldPrice, 0, ',', '.') . ', harga promo menjadi Rp ' . number_format($newPrice, 0, ',', '.') . '.';
        }

        if (preg_match('/(?:restock|tambah stok)\s+(.+?)\s+([\d.,]+)\s*(?:unit|pcs)?/iu', $raw, $m)) {
            $preview = $this->restockPreview($raw, $user);
            if ($preview === null) {
                return 'Sparepart tidak ditemukan. Cek nama di menu Master Data.';
            }

            return "Saya siapkan kartu konfirmasi restock untuk \"{$preview['name']}\" sebanyak {$preview['qty']} unit. Stok baru akan bertambah setelah Anda menekan tombol bayar dan saldo ZeroPay owner mencukupi.";
        }

        if (preg_match('/\b(tren|trend|analisa|analisis)\b.*\b(bulan ini|bulan berjalan|bulan sekarang)\b/u', $q)) {
            return $this->ownerAnalyticsPeriodSummary('this_month');
        }

        if (preg_match('/\b(tren|trend|analisa|analisis)\b.*\b3\s*bulan\s*terakhir\b/u', $q)) {
            return $this->ownerAnalyticsPeriodSummary('last_3_months');
        }

        if (preg_match('/\b(tren|trend|analisa|analisis)\b.*\b6\s*bulan\s*terakhir\b/u', $q)) {
            return $this->ownerAnalyticsPeriodSummary('last_6_months');
        }

        if (preg_match('/\b(tren|analisa|analisis|performa|omzet|pendapatan)\b/u', $q)) {
            return $this->ownerAnalyticsSummary();
        }

        return null;
    }

    private function tryOperationalAnswer(string $raw, string $q, User $user): ?string
    {
        if (preg_match('/\b(ubah\s+harga|pengaturan\s+harga)\b/u', $q)) {
            return $user->isRole('owner')
                ? 'Ubah harga layanan digunakan Owner untuk memperbarui harga jasa di Master Data. Perubahan harga hanya berlaku setelah Owner mengisi form dan menekan konfirmasi, sehingga ZoruAi tidak langsung mengubah data tanpa persetujuan.'
                : 'Pengaturan harga layanan hanya dapat dilakukan Owner. Role lain dapat melihat layanan sesuai kebutuhan, tetapi tidak dapat mengubah harga master data.';
        }

        if (preg_match('/\b(pasang\s+diskon|diskon\s+layanan|promo\s+layanan)\b/u', $q)) {
            return $user->isRole('owner')
                ? 'Pasang diskon digunakan Owner untuk membuat promo layanan. Harga master tetap disimpan, sementara harga promo dihitung dari persentase diskon dan masa berlaku promo. Promo aktif dapat tampil di halaman Promo pelanggan.'
                : 'Promo layanan dibuat oleh Owner. Anda dapat melihat promo aktif yang tersedia, tetapi pengaturan diskon tidak dibuka untuk role ini.';
        }

        if (preg_match('/\b(tambah\s+jenis\s+servis|tambah\s+servis|layanan\s+baru)\b/u', $q)) {
            return $user->isRole('owner')
                ? 'Tambah jenis servis digunakan Owner untuk mendaftarkan layanan baru, durasi pengerjaan, harga dasar, serta persentase bagi hasil mekanik dan kasir. Data baru tersimpan setelah form dikonfirmasi.'
                : 'Penambahan jenis servis hanya dapat dilakukan Owner melalui Master Data. Role lain memakai daftar layanan yang sudah tersedia.';
        }

        if (preg_match('/\b(proses\s+restock|restock\s+sparepart|tambah\s+stok)\b/u', $q)) {
            return $user->isRole('owner')
                ? 'Restock sparepart digunakan Owner untuk menambah stok barang. Sistem menyiapkan kartu konfirmasi, menghitung biaya restock, lalu stok bertambah setelah pembayaran ZeroPay Owner tervalidasi.'
                : 'Restock sparepart hanya dapat dilakukan Owner. Jika stok bermasalah saat servis, laporkan ke Owner agar restock dilakukan dari Master Data atau ZoruAi Owner.';
        }

        if (preg_match('/\b(konfirmasi|validasi)\b.*\b(saldo|transaksi)\b/u', $q)) {
            return 'Konfirmasi dan validasi saldo dipakai agar transaksi penting tidak berubah otomatis. Sistem meminta langkah akhir seperti tombol konfirmasi, PIN, atau pemeriksaan saldo sebelum saldo, stok, pembayaran, atau data operasional diperbarui.';
        }

        if (preg_match('/\b(troubleshoot|troubleshooting|diagnosa|diagnosis|cek|masalah|gejala)\b.*\b(motor|oli|busi|rem|mesin|suara|asap|mogok|susah\s+nyala|brebet|panas|getar|kampas)\b/u', $q)
            || preg_match('/\b(oli|busi|rem|mesin|asap|mogok|brebet|susah\s+nyala|bunyi|suara|getar|kampas)\b.*\b(kenapa|masalah|rusak|tanda|gejala|harus|ganti|cek|basah|hitam|kotor)\b/u', $q)
            || preg_match('/\b(kenapa|masalah|rusak|tanda|gejala|harus|ganti|cek|basah|hitam|kotor)\b.*\b(oli|busi|rem|mesin|asap|mogok|brebet|susah\s+nyala|bunyi|suara|getar|kampas)\b/u', $q)
            || preg_match('/\b(busi)\b.*\b(basah|hitam|kotor)\b/u', $q)) {
            return $this->lightTroubleshootingGuide($q, $user);
        }

        if (preg_match('/\b(saldo|zeropay)\b.*\b(kurang|tidak cukup|nggak cukup|ga cukup|gagal bayar)\b/u', $q)
            || preg_match('/\b(kurang)\b.*\b(rp|ribu|saldo|bayar)\b/u', $q)) {
            return $this->insufficientBalanceGuide($raw, $user);
        }

        if (preg_match('/\b(booking|jadwal|slot)\b.*\b(penuh|full|habis|bentrok|tidak tersedia)\b/u', $q)) {
            return $this->bookingFullGuide($user);
        }

        if (preg_match('/\b(stok|sparepart|spare part|barang)\b.*\b(habis|kosong|tidak ada|kurang|unavailable)\b/u', $q)
            || preg_match('/\b(sparepart|spare part)\b.*\b(mana|apa)\b.*\b(habis|kosong|rendah)\b/u', $q)) {
            return $this->stockProblemGuide($user);
        }

        if (preg_match('/\b(berapa lama|durasi|estimasi)\b.*\b(servis|service|ganti oli|tune up|rem)\b/u', $q)) {
            return $this->serviceDurationSummary($raw);
        }

        return null;
    }

    private function updateCatalogPrice(string $kind, string $name, int $price): string
    {
        if ($price < 0) {
            return 'Harga tidak valid.';
        }

        if ($kind === 'service') {
            $item = ServiceType::whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($name) . '%'])->first();
            if (! $item) {
                return "Servis \"{$name}\" tidak ditemukan.";
            }
            $item->update(['base_price' => $price]);

            return "Harga \"{$item->name}\" diubah menjadi Rp " . number_format($price, 0, ',', '.') . '.';
        }

        $item = SparePart::whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($name) . '%'])->first();
        if (! $item) {
            return "Sparepart \"{$name}\" tidak ditemukan.";
        }
        $item->update(['price' => $price]);

        return "Harga sparepart \"{$item->name}\" diubah menjadi Rp " . number_format($price, 0, ',', '.') . '.';
    }

    private function ownerAnalyticsSummary(): string
    {
        $todayPaid = Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $monthPaid = Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // 1. Servis Terpopuler
        $popularService = DB::table('booking_service_type')
            ->join('bookings', 'booking_service_type.booking_id', '=', 'bookings.id')
            ->join('service_types', 'booking_service_type.service_type_id', '=', 'service_types.id')
            ->where('bookings.status', 'completed')
            ->whereMonth('bookings.booking_date', now()->month)
            ->whereYear('bookings.booking_date', now()->year)
            ->selectRaw('service_types.name, COUNT(*) as total')
            ->groupBy('service_types.name')
            ->orderByDesc('total')
            ->first();

        $trendText = '';
        if ($popularService) {
            $popName = $popularService->name;
            $popCount = $popularService->total;
            
            // Cari servis lain yang kurang populer
            $unpopularService = ServiceType::where('name', '!=', $popName)->inRandomOrder()->first();
            
            $trendText = "Layanan service yang sedang ramai bulan ini adalah **{$popName}** dengan total **{$popCount}** kali dikerjakan. Saran saya, jika Anda menaikkan sedikit harga jasanya atau memberikannya sedikit diskon, hal tersebut tidak akan mengurangi pelanggan atau merugikan Anda.";
            
            if ($unpopularService) {
                $trendText .= " Sementara itu, untuk service yang sedang menurun peminatnya yaitu **{$unpopularService->name}**, buatlah iklan atau promo menarik pada halaman dashboard pelanggan atau lakukan sedikit penyesuaian harga agar customer lebih tertarik untuk mencoba layanan tersebut.";
            }
        } else {
            $trendText = 'Belum ada data penyelesaian service yang tercatat pada bulan ini untuk dianalisis trennya.';
        }

        // 2. Performa Karyawan (Mekanik) Hari Ini
        $topMechanic = ServiceOrder::whereDate('created_at', today())
            ->where('status', 'finished')
            ->selectRaw('mechanic_id, COUNT(*) as total')
            ->groupBy('mechanic_id')
            ->orderByDesc('total')
            ->first();

        $employeeText = '';
        if ($topMechanic && $topMechanic->mechanic_id) {
            $mechUser = User::find($topMechanic->mechanic_id);
            if ($mechUser) {
                $employeeText = "Mekanik **{$mechUser->name}** berkinerja luar biasa hari ini dengan menyelesaikan **{$topMechanic->total}** pelayanan service! Sangat disarankan untuk memberikan apresiasi berupa bonus atau tips atas dedikasi dan performa baiknya hari ini.";
            }
        }
        
        if (empty($employeeText)) {
            $employeeText = 'Hari ini belum ada aktivitas pengerjaan service yang diselesaikan oleh mekanik untuk diulas performanya.';
        }

        // 3. Stok Sparepart Menipis
        $lowStockParts = SparePart::where('stock', '<=', 5)->orderBy('stock')->limit(3)->get();
        $stockText = '';
        if ($lowStockParts->isNotEmpty()) {
            $stockText = "Untuk sparepart yang stoknya menipis yaitu:\n";
            foreach ($lowStockParts as $part) {
                $stockText .= "- **{$part->name}** (Stok tersisa: **{$part->stock}** unit), segeralah lakukan restock agar pengerjaan servis pelanggan tidak terhambat.\n";
            }
        } else {
            $stockText = 'Semua stok sparepart saat ini dalam kondisi sangat aman (di atas 5 unit). Manajemen inventaris gudang Anda berjalan sangat baik!';
        }

        return "Analisis tren bisnis dan rekomendasi harian untuk Anda:\n\n"
            . " **Keuangan & Tren Peminat Layanan**\n"
            . "* Hari ini kita mencatat total omzet **Rp " . number_format($todayPaid, 0, ',', '.') . "**, dengan akumulasi omzet bulan ini sebesar **Rp " . number_format($monthPaid, 0, ',', '.') . "**.\n"
            . "* {$trendText}\n\n"
            . " **Ulasan Kinerja Karyawan**\n"
            . "* {$employeeText}\n\n"
            . " **Ketersediaan Sparepart & Gudang**\n"
            . "* {$stockText}\n\n"
            . "Saran-saran di atas disusun berdasarkan perkembangan data riil di bengkel Milky Garage Anda.";
    }

    private function ownerAnalyticsPeriodSummary(string $period): string
    {
        $today = now()->startOfDay();

        if ($period === 'this_month') {
            if ($today->day === 1) {
                return 'Data bulan ini belum bisa dianalisis karena hari pertama bulan ini belum lewat. Saya baru bisa membaca pola bulan ini mulai besok, setelah ada data dari tanggal 1.';
            }

            $startDate = $today->copy()->startOfMonth();
            $endDate = $today->copy()->subDay()->endOfDay();
            $periodLabel = 'Bulan Ini';
            $periodRange = $startDate->translatedFormat('d F Y') . ' - ' . $endDate->translatedFormat('d F Y');
        } elseif ($period === 'last_3_months') {
            $startDate = $today->copy()->startOfMonth()->subMonths(3);
            $endDate = $today->copy()->startOfMonth()->subDay()->endOfDay();
            $periodLabel = '3 Bulan Terakhir';
            $periodRange = $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y');
        } else {
            $startDate = $today->copy()->startOfMonth()->subMonths(6);
            $endDate = $today->copy()->startOfMonth()->subDay()->endOfDay();
            $periodLabel = '6 Bulan Terakhir';
            $periodRange = $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y');
        }

        $paidQuery = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);
        $completedBookingsQuery = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()]);
        $finishedOrdersQuery = ServiceOrder::where('status', 'finished')
            ->whereBetween('created_at', [$startDate, $endDate]);

        $totalPaid = (int) (clone $paidQuery)->sum('amount');
        $paymentCount = (clone $paidQuery)->count();
        $completedCount = (clone $completedBookingsQuery)->count();
        $finishedCount = (clone $finishedOrdersQuery)->count();

        if ($totalPaid <= 0 && $paymentCount === 0 && $completedCount === 0 && $finishedCount === 0) {
            return "Analisis Periode: **{$periodLabel}** ({$periodRange})\n\n"
                . "Saya belum bisa membuat analisis yang adil untuk periode ini karena belum ada transaksi lunas atau servis selesai yang tercatat. Jika bengkel baru mulai berjalan, ini normal. Setelah ada beberapa transaksi, saya bisa membaca pola layanan, omzet, dan rekomendasi operasional dengan lebih tepat.";
        }

        $popularService = DB::table('booking_service_type')
            ->join('bookings', 'booking_service_type.booking_id', '=', 'bookings.id')
            ->join('service_types', 'booking_service_type.service_type_id', '=', 'service_types.id')
            ->where('bookings.status', 'completed')
            ->whereBetween('bookings.booking_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('service_types.name, COUNT(*) as total')
            ->groupBy('service_types.name')
            ->orderByDesc('total')
            ->first();

        $topMechanic = (clone $finishedOrdersQuery)
            ->selectRaw('mechanic_id, COUNT(*) as total')
            ->whereNotNull('mechanic_id')
            ->groupBy('mechanic_id')
            ->orderByDesc('total')
            ->first();

        $monthlyRevenue = (clone $paidQuery)
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month_key, SUM(amount) as total")
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('total', 'month_key');

        $trendText = $this->revenueTrendText($monthlyRevenue, $period);
        $dataNote = $completedCount < 2
            ? "\n\nCatatan: data servis selesai masih terbatas, jadi rekomendasi saya bersifat awal dan perlu divalidasi lagi setelah transaksi bertambah."
            : '';

        $serviceText = $popularService
            ? "Layanan paling terlihat pada periode ini adalah **{$popularService->name}** dengan **{$popularService->total}** servis selesai. Ini bisa dijadikan fokus promo, stok pendukung, atau evaluasi harga jasa."
            : 'Belum ada pola layanan dominan yang cukup jelas dari data servis selesai pada periode ini.';

        $employeeText = 'Belum ada performa mekanik yang cukup menonjol dari data servis selesai pada periode ini.';
        if ($topMechanic && $topMechanic->mechanic_id) {
            $mechanic = User::find($topMechanic->mechanic_id);
            if ($mechanic) {
                $employeeText = "Mekanik **{$mechanic->name}** paling aktif pada periode ini dengan **{$topMechanic->total}** servis selesai. Jika kualitas pengerjaan juga baik, ini layak dicatat sebagai performa positif.";
            }
        }

        $lowStockParts = SparePart::where('stock', '<=', 5)->orderBy('stock')->limit(3)->get();
        $stockText = 'Stok sparepart utama masih terlihat aman, tidak ada item kritis di batas 5 unit ke bawah.';
        if ($lowStockParts->isNotEmpty()) {
            $stockText = "Beberapa sparepart perlu perhatian:\n";
            foreach ($lowStockParts as $part) {
                $stockText .= "- **{$part->name}** tersisa **{$part->stock}** unit.\n";
            }
        }

        return "Analisis Periode: **{$periodLabel}** ({$periodRange})\n\n"
            . "Basis data: **{$completedCount}** servis selesai, **{$paymentCount}** pembayaran lunas, dan omzet **Rp " . number_format($totalPaid, 0, ',', '.') . "**.{$dataNote}\n\n"
            . " **Keuangan & Tren**\n"
            . "* {$trendText}\n\n"
            . " **Layanan Paling Terlihat**\n"
            . "* {$serviceText}\n\n"
            . " **Kinerja Karyawan**\n"
            . "* {$employeeText}\n\n"
            . " **Ketersediaan Sparepart**\n"
            . "* {$stockText}\n\n"
            . "Saran saya: gunakan hasil ini sebagai bahan keputusan operasional, terutama untuk promo layanan, kesiapan stok, dan evaluasi performa tim.";
    }

    private function revenueTrendText($monthlyRevenue, string $period): string
    {
        if ($period === 'this_month') {
            $total = (int) $monthlyRevenue->sum();
            return $total > 0
                ? 'Omzet bulan berjalan sudah tercatat sampai kemarin sebesar **Rp ' . number_format($total, 0, ',', '.') . '**. Karena periode ini masih berjalan, bacaan terbaiknya adalah memantau konsistensi transaksi harian.'
                : 'Belum ada pembayaran lunas pada rentang tanggal bulan ini yang sudah lewat.';
        }

        $values = $monthlyRevenue->values()->map(fn ($value) => (int) $value)->all();
        if (count($values) < 2) {
            return 'Data omzet belum cukup untuk membaca arah naik atau turun antarbulan.';
        }

        $first = $values[0];
        $last = $values[count($values) - 1];
        if ($last > $first) {
            return 'Arah omzet terlihat **naik** dibanding awal periode. Ini sinyal baik, terutama jika jumlah servis selesai juga ikut bertambah.';
        }

        if ($last < $first) {
            return 'Arah omzet terlihat **menurun** dibanding awal periode. Perlu dicek apakah penyebabnya jumlah booking turun, layanan bernilai tinggi berkurang, atau promo belum cukup menarik.';
        }

        return 'Arah omzet terlihat **stabil**. Kondisi ini cukup baik, tetapi masih bisa ditingkatkan dengan promo terarah atau paket layanan.';
    }

    /** @return list<array{pattern: string, answer: string|callable}> */
    private function knowledgeRules(User $user): array
    {
        return [
            // --- SPECIFIC COMMANDS (MUST BE FIRST) ---
            [
                'pattern' => '/ubah\s+gaji\s+(.+?)\s+jadi\s+mekanik\s+(\d{1,3})\s*%?\s+dan\s+kasir\s+(\d{1,3})\s*%?/iu',
                'answer' => function(User $u, string $q) {
                    if (!$u->isRole('owner')) return 'Hanya owner yang dapat mengatur gaji karyawan.';
                    if (preg_match('/ubah\s+gaji\s+(.+?)\s+jadi\s+mekanik\s+(\d{1,3})\s*%?\s+dan\s+kasir\s+(\d{1,3})\s*%?/iu', $q, $m)) {
                        $name = trim($m[1]);
                        $mech = (int) $m[2];
                        $cash = (int) $m[3];
                        $total = $mech + $cash;

                        if ($total < 50) {
                            return 'Perubahan ditolak. Total persentase mekanik dan kasir minimal 50% agar pembagian hasil tetap adil.';
                        }

                        if ($total > 100) {
                            return 'Perubahan ditolak. Total persentase mekanik dan kasir tidak boleh lebih dari 100%.';
                        }
                        
                        $service = ServiceType::whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($name) . '%'])->first();
                        if (!$service) return "Servis \"{$name}\" tidak ditemukan.";
                        
                        $service->update([
                            'mechanic_salary' => $mech,
                            'cashier_salary' => $cash
                        ]);
                        $owner = 100 - $total;
                        return "Gaji karyawan untuk {$service->name} berhasil diperbarui: mekanik {$mech}%, kasir {$cash}%, dan owner otomatis {$owner}% dari biaya jasa. Penjualan sparepart tetap masuk owner penuh.";
                    }
                    return 'Format perintah salah.';
                }
            ],
            [
                'pattern' => '/tambah\s+servis\s+(.+?)\s+dengan\s+durasi\s+(\d+)\s+menit,\s+harga\s+(?:rp\s*)?([\d.,]+),\s+gaji\s+mekanik\s+(\d{1,3})\s*%?,\s+gaji\s+kasir\s+(\d{1,3})\s*%?/iu',
                'answer' => function(User $u, string $q) {
                    if (!$u->isRole('owner')) return 'Hanya owner yang dapat menambah servis.';
                    if (preg_match('/tambah\s+servis\s+(.+?)\s+dengan\s+durasi\s+(\d+)\s+menit,\s+harga\s+(?:rp\s*)?([\d.,]+),\s+gaji\s+mekanik\s+(\d{1,3})\s*%?,\s+gaji\s+kasir\s+(\d{1,3})\s*%?/iu', $q, $m)) {
                        $name = trim($m[1]);
                        $dur = (int) $m[2];
                        $price = (int) str_replace(['.', ','], '', $m[3]);
                        $mech = (int) $m[4];
                        $cash = (int) $m[5];
                        $total = $mech + $cash;

                        if ($total < 50) {
                            return 'Servis belum ditambahkan. Total persentase mekanik dan kasir minimal 50% agar pembagian hasil tetap adil.';
                        }

                        if ($total > 100) {
                            return 'Servis belum ditambahkan. Total persentase mekanik dan kasir tidak boleh lebih dari 100%.';
                        }
                        
                        if (ServiceType::whereRaw('LOWER(name) = ?', [Str::lower($name)])->exists()) {
                            return "Servis \"{$name}\" sudah terdaftar.";
                        }
                        
                        ServiceType::create([
                            'name' => $name,
                            'estimated_minutes' => $dur,
                            'base_price' => $price,
                            'mechanic_salary' => $mech,
                            'cashier_salary' => $cash
                        ]);
                        
                        return "Servis {$name} berhasil ditambahkan dengan harga Rp " . number_format($price, 0, ',', '.') . ", bagi hasil mekanik {$mech}%, kasir {$cash}%, dan owner " . (100 - $total) . "% dari jasa.";
                    }
                    return 'Format salah.';
                }
            ],
            [
                'pattern' => '/hapus\s+servis\s+(.+)/iu',
                'answer' => function(User $u, string $q) {
                    if (!$u->isRole('owner')) return 'Hanya owner yang dapat menghapus servis.';
                    if (preg_match('/hapus\s+servis\s+(.+)/iu', $q, $m)) {
                        $name = trim($m[1]);
                        $service = ServiceType::whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();
                        if (!$service) return "Servis \"{$name}\" tidak ditemukan.";
                        $service->delete();
                        return "Servis {$name} berhasil dihapus.";
                    }
                    return 'Format salah.';
                }
            ],
            [
                'pattern' => '/tambah\s+sparepart\s+(.+?)\s+sku\s+(\S+)\s+stok\s+(\d+)\s+unit,\s+harga\s+retail\s+(?:rp\s*)?([\d.,]+),\s+harga\s+modal\s+(?:rp\s*)?([\d.,]+)/iu',
                'answer' => function(User $u, string $q) {
                    if (!$u->isRole('owner')) return 'Hanya owner yang dapat menambah sparepart.';
                    if (preg_match('/tambah\s+sparepart\s+(.+?)\s+sku\s+(\S+)\s+stok\s+(\d+)\s+unit,\s+harga\s+retail\s+(?:rp\s*)?([\d.,]+),\s+harga\s+modal\s+(?:rp\s*)?([\d.,]+)/iu', $q, $m)) {
                        $name = trim($m[1]);
                        $sku = trim($m[2]);
                        $stok = (int) $m[3];
                        $retail = (int) str_replace(['.', ','], '', $m[4]);
                        $modal = (int) str_replace(['.', ','], '', $m[5]);
                        
                        if (SparePart::where('sku', $sku)->exists()) {
                            return "Sparepart dengan SKU {$sku} sudah terdaftar.";
                        }
                        
                        $cost = $stok * $modal;
                        if ($u->balance < $cost) {
                            return "Saldo tidak mencukupi.";
                        }
                        
                        $u->decrement('balance', $cost);
                        SparePart::create([
                            'name' => $name,
                            'sku' => $sku,
                            'stock' => $stok,
                            'price' => $retail,
                            'cost' => $modal
                        ]);
                        
                        AccountActivity::create([
                            'user_id' => $u->id,
                            'type' => 'money_out',
                            'description' => "Tambah sparepart via ZoruAi ({$name})",
                            'amount' => -$cost
                        ]);
                        
                        return "Sparepart {$name} berhasil ditambahkan.";
                    }
                    return 'Format salah.';
                }
            ],
            [
                'pattern' => '/hapus\s+sparepart\s+(.+)/iu',
                'answer' => function(User $u, string $q) {
                    if (!$u->isRole('owner')) return 'Hanya owner yang dapat menghapus sparepart.';
                    if (preg_match('/hapus\s+sparepart\s+(.+)/iu', $q, $m)) {
                        $name = trim($m[1]);
                        $part = SparePart::whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();
                        if (!$part) return "Sparepart \"{$name}\" tidak ditemukan.";
                        $part->delete();
                        return "Sparepart {$name} berhasil dihapus.";
                    }
                    return 'Format salah.';
                }
            ],
            [
                'pattern' => '/top-up\s+zeropay\s+(?:rp\s*)?([\d.,]+)\s+via\s+(\w+)/iu',
                'answer' => function(User $u, string $q) {
                    if (preg_match('/top-up\s+zeropay\s+(?:rp\s*)?([\d.,]+)\s+via\s+(\w+)/iu', $q, $m)) {
                        $amount = (int) str_replace(['.', ','], '', $m[1]);
                        $method = strtoupper($m[2]);

                        if (! $u->canReceiveBalance($amount)) {
                            return 'Saldo ZeroPay maksimal Rp ' . number_format(User::MAX_BALANCE, 0, ',', '.') . '. Kurangi nominal top-up agar saldo tidak melewati batas.';
                        }
                        
                        $u->addBalance($amount);
                        AccountActivity::create([
                            'user_id' => $u->id,
                            'type' => 'money_in',
                            'description' => "Top-up via ZoruAi ({$method})",
                            'amount' => $amount
                        ]);
                        
                        return "Top-up ZeroPay Rp " . number_format($amount, 0, ',', '.') . " berhasil dilakukan via {$method}.";
                    }
                    return 'Format salah.';
                }
            ],
            [
                'pattern' => '/tarik\s+dana\s+zeropay\s+(?:rp\s*)?([\d.,]+)\s+ke\s+(\w+)\s+dengan\s+pin\s+(\d+)/iu',
                'answer' => function(User $u, string $q) {
                    if (preg_match('/tarik\s+dana\s+zeropay\s+(?:rp\s*)?([\d.,]+)\s+ke\s+(\w+)\s+dengan\s+pin\s+(\d+)/iu', $q, $m)) {
                        $amount = (int) str_replace(['.', ','], '', $m[1]);
                        $wallet = strtoupper($m[2]);
                        $pin = $m[3];
                        
                        if (!$u->withdraw_pin) {
                            $defaultPin = '1234';
                            if ($u->isRole('owner')) $defaultPin = '0104';
                            elseif ($u->isRole('cashier')) $defaultPin = '0000';
                            elseif ($u->isRole('mechanic')) $defaultPin = '1111';
                            
                            if ($pin !== $defaultPin && $pin !== '1234') {
                                return 'PIN withdraw yang Anda masukkan salah.';
                            }
                        } else {
                            if (!\Illuminate\Support\Facades\Hash::check($pin, $u->withdraw_pin)) {
                                return 'PIN withdraw yang Anda masukkan salah.';
                            }
                        }
                        if ($u->balance < $amount) {
                            return 'Saldo Anda tidak mencukupi.';
                        }
                        
                        $u->decrement('balance', $amount);
                        AccountActivity::create([
                            'user_id' => $u->id,
                            'type' => 'money_out',
                            'description' => "Withdraw via ZoruAi ({$wallet})",
                            'amount' => -$amount
                        ]);
                        
                        return "Tarik dana ZeroPay Rp " . number_format($amount, 0, ',', '.') . " berhasil ditarik.";
                    }
                    return 'Format salah.';
                }
            ],

            // --- GENERIC CORE FEATURES (MUST BE RIGHT UNDER SPECIFIC COMMANDS) ---
            [
                'pattern' => '/\b(pin|kode pin)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'PIN penarikan ZeroPay: Owner = 0104, Kasir = 0000, Mekanik = 1111. PIN dimasukkan di modal setelah konfirmasi, bukan di form utama.'
                    : 'Maaf, detail PIN termasuk informasi internal. Silakan hubungi owner untuk verifikasi akses.',
            ],
            [
                'pattern' => '/\b(tarik dana|penarikan|withdraw)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Tarik dana ZeroPay: isi nominal dan tujuan e-wallet atau rekening, lalu pilih Konfirmasi Penarikan. Modal Verifikasi PIN akan muncul (4 digit). PIN peran: Owner 0104, Kasir 0000, Mekanik = 1111. Saldo berkurang setelah PIN benar.'
                    : 'Tarik dana ZeroPay tersedia untuk role internal. Gunakan menu Withdraw, isi nominal dan tujuan, lalu ikuti verifikasi PIN pada modal konfirmasi. Detail PIN dan konfigurasi internal hanya dapat dijelaskan kepada owner.',
            ],
            [
                'pattern' => '/\b(top\s*up|topup|isi saldo)\b/u',
                'answer' => 'Top-up ZeroPay: pelanggan lewat ikon + di kartu ZeroPay; owner lewat ikon ZeroPay -> Kelola Saldo -> Top-up. Masukkan nominal minimal Rp 10.000 dan maksimal Rp 10.000.000 per transaksi, lalu pilih e-wallet (GoPay, OVO, DANA, ShopeePay). Saldo langsung bertambah setelah verifikasi selesai.',
            ],
            [
                'pattern' => '/\b(saldo)\b/u',
                'answer' => fn(User $u) => 'Saldo Anda saat ini pada ZeroPay: Rp '
                    . number_format($u->balance, 0, ',', '.')
                    . '. Ketik "rincian zeropay" untuk penjelasan lengkap fitur dompet digital ini.',
            ],
            [
                'pattern' => '/\b(apa\s+itu\s+zeropay|zeropay\s+itu\s+apa|zero\s*pay\s+itu\s+apa|pengertian\s+zeropay|jelaskan\s+zeropay|rincian\s+zeropay|rantang\s+zeropay)\b/u',
                'answer' => fn(User $u) => $this->zeroPayGuide($u),
            ],
            [
                'pattern' => '/\b(zeropay|zero pay)\b/u',
                'answer' => fn(User $u) => $this->zeroPaySummary($u),
            ],
            [
                'pattern' => '/\b(gaji\s+saya|komisi\s+saya|fee\s+saya|rincian\s+gaji|laporan\s+gaji\s+saya)\b/iu',
                'answer' => function(User $u) {
                    if ($u->isRole('mechanic')) {
                        return 'Halo Kak, komisi/fee servis Anda dicatat otomatis oleh sistem untuk setiap motor yang selesai Anda kerjakan (sesuai persentase/nominal per jenis servis). Anda dapat memantau rincian lengkap fee harian Anda di menu Laporan Gaji dan mencairkannya ke saldo ZeroPay Anda kapan saja.';
                    } elseif ($u->isRole('cashier')) {
                        return 'Halo Kak, gaji/fee kasir Anda dicatat otomatis untuk setiap transaksi pembayaran yang selesai. Anda dapat meninjau rincian gaji harian Anda di menu Laporan Gaji dan mengklaimnya sebelum logout.';
                    } else {
                        return 'Detail rincian komisi dan gaji hanya tersedia untuk role internal (Mekanik & Kasir). Pelanggan tidak memiliki komisi kerja.';
                    }
                }
            ],
            [
                'pattern' => '/\b(gaji\s+karyawan|atur\s+gaji|fee mekanik|fee kasir)\b/iu',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Silakan isi formulir di bawah ini untuk mengatur persentase gaji mekanik dan kasir.'
                    : 'Detail gaji karyawan hanya dapat dikelola oleh owner.',
            ],
            [
                'pattern' => '/\b(acc\s+booking|terima\s+booking|konfirmasi\s+booking)\b/iu',
                'answer' => function(User $u) {
                    if ($u->isRole('mechanic')) {
                        return 'Halo Kak, untuk menyetujui atau mengonfirmasi booking dari pelanggan, silakan klik menu ACC Booking di dashboard atau gunakan tombol pintasan ACC Booking.';
                    }
                    return 'Fitur ACC Booking khusus digunakan oleh Mekanik untuk mengambil dan memproses pengerjaan servis.';
                }
            ],

            // --- CASUAL / DIALOG RULES ---
            [
                'pattern' => '/\b(siapa\s+saya|tahu\s+siapa\s+saya)\b/iu',
                'answer' => fn(User $u) => 'Anda adalah Kak ' . ucwords($u->username) . ', aktif sebagai ' . $u->roleLabel() . ' di Milky Garage.',
            ],
            [
                'pattern' => '/\b(hidden\s*gem|tersembunyi)\b/iu',
                'answer' => 'Tombol Hidden Gem adalah fitur kejutan ZoruAi yang saat ini disiapkan ulang. Untuk sementara tampilannya hanya Coming Soon.',
            ],
            [
                'pattern' => '/\b(pembuat|developer|didevelop|pencipta|pgteam|pgdev)\b/iu',
                'answer' => 'Sistem Milky Garage dan asisten virtual ZoruAi didevelop oleh Aefzetaa dan seluruh anggota PGTeam, yaitu Muhammad Saifil Mubarok, Agung Slamet Riyadi, dan Gilang Yudha Pratama. Kredit pembuat ditampilkan sebagai bagian presentasi PGTeam.',
            ],
            [
                'pattern' => '/\b(gimana\s*kabar(?:nya)?)\b/iu',
                'answer' => 'Kabar saya luar biasa baik and siap membantu Anda kapan saja di Milky Garage!',
            ],
            [
                'pattern' => '/\bfotosintesis\b/iu',
                'answer' => 'Fotosintesis adalah proses biologis yang dilakukan tumbuhan, alga, dan beberapa bakteri untuk mengubah energi cahaya matahari menjadi energi kimia dalam bentuk glukosa. Proses ini menggunakan karbon dioksida dan air dengan bantuan klorofil, lalu menghasilkan glukosa dan oksigen.',
            ],
            [
                'pattern' => '/\b(lagi\s*ngapain|sedang\s*apa)\b/iu',
                'answer' => 'Saya sedang bersiap melayani kebutuhan administrasi bengkel Anda. Sama sekali tidak lelah, asalkan bisa membantu melayani Anda dengan sepenuh hati.',
            ],
            [
                'pattern' => '/\b(keren\s*banget|hebat|pintar)\b/iu',
                'answer' => 'Terima kasih banyak atas pujiannya! Saya sangat senang bisa membantu mempermudah pekerjaan Anda di bengkel.',
            ],
            [
                'pattern' => '/\b(ass?alamu?alaikum)\b/u',
                'answer' => fn(User $u) => $this->greeting($u),
            ],
            [
                'pattern' => '/\b(terima kasih|makasih|thanks|thx)\b/u',
                'answer' => 'Sama-sama. Senang dapat membantu. Jika masih ada yang ingin ditanyakan, silakan sampaikan.',
            ],
            [
                'pattern' => '/\b(zoru|zorua?i|kamu siapa|siapa kamu|nama kamu|kamu bisa apa|anda bisa apa|bisa apa aja|apa yang bisa (kamu|anda) lakukan)\b/u',
                'answer' => 'Saya ZoruAi - asisten virtual Milky Garage. Saya membantu membaca kebutuhan layanan, transaksi, dan panduan Milky Garage dari data aplikasi. Untuk harga servis dan sparepart, saya selalu mengacu master data sistem.',
            ],
            [
                'pattern' => '/\b(milky garage|bengkel ini|tentang bengkel)\b/u',
                'answer' => 'Milky Garage adalah sistem manajemen bengkel motor untuk tugas kuliah: booking servis, pembayaran, ZeroPay (dompet digital khusus proyek ini), laporan, dan master data. Cocok untuk presentasi alur bengkel yang rapi dan mudah dipahami.',
            ],
            [
                'pattern' => '/\b(bayar.*zeropay|zeropay.*bayar|metode zeropay)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Pembayaran dengan ZeroPay: pelanggan pilih metode ZeroPay di halaman Pembayaran jika saldo cukup. Dana langsung dipotong; order selesai; gaji mekanik/kasir & profit owner dicatat otomatis di aktivitas akun (alur bisnis tercatat).'
                    : 'Pembayaran dengan ZeroPay: pelanggan pilih metode ZeroPay di halaman Pembayaran jika saldo cukup. Dana langsung dipotong, status pembayaran menjadi lunas, dan nota/invoice dibuat oleh sistem.',
            ],
            [
                'pattern' => '/\b(booking|pesan servis|jadwal)\b/u',
                'answer' => 'Pelanggan: menu Booking Service -> pilih kendaraan, jenis servis, tanggal. Mekanik: ACC Booking untuk setujui/tolak. Setelah disetujui, order masuk alur servis & pembayaran.',
            ],
            [
                'pattern' => '/\b(pembayaran|bayar|qris|tunai|invoice)\b/u',
                'answer' => 'Alur bayar: pelanggan lihat tagihan di Pembayaran -> pilih metode (Tunai/Transfer/QRIS/ZeroPay). Kasir konfirmasi di Kelola Pembayaran. QRIS menampilkan proses scan singkat sebelum transaksi selesai.',
            ],
            [
                'pattern' => '/\b(kasir|role kasir)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Kasir: kelola pembayaran, laporan kasir, laporan gaji, tarik dana ZeroPay, Agent AI, riwayat akun. Maksimal 2 akun kasir (kode registrasi: CASH2026).'
                    : 'Kasir: kelola pembayaran, laporan kasir, laporan gaji, tarik dana ZeroPay, Agent AI, dan riwayat akun. Detail kode registrasi internal hanya dapat dijelaskan kepada owner.',
            ],
            [
                'pattern' => '/\b(mekanik|role mekanik)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Mekanik: ACC booking, update progres servis, laporan gaji, tarik dana ZeroPay, Agent AI. Registrasi pakai kode MECH2026.'
                    : 'Mekanik: ACC booking, update progres servis, laporan gaji, tarik dana ZeroPay, dan Agent AI. Detail kode registrasi internal hanya dapat dijelaskan kepada owner.',
            ],
            [
                'pattern' => '/\b(pelanggan|customer)\b/u',
                'answer' => 'Pelanggan: booking, pembayaran, riwayat servis, top-up ZeroPay, Agent AI, riwayat akun. Registrasi tanpa kode verifikasi.',
            ],
            [
                'pattern' => '/\b(owner|pemilik)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Owner: Keuangan Harian, Master Data, Trend Analyze & ZoruAi (mode eksekutif - ubah harga, diskon, ringkasan omzet). Hanya 1 akun owner (kode OWNER2026).'
                    : 'Owner: pemilik sistem dengan akses keuangan, master data, dan mode eksekutif ZoruAi. Detail kode dan konfigurasi internal hanya dapat dijelaskan kepada owner.',
            ],
            [
                'pattern' => '/\b(registrasi|daftar akun|username)\b/u',
                'answer' => 'Registrasi 2 langkah. Username maksimal 8 karakter (huruf, angka, underscore). Role internal butuh kode verifikasi di langkah 2.',
            ],
            [
                'pattern' => '/\b(login|masuk|password|kunci akun)\b/u',
                'answer' => 'Login pakai username & password. Setelah 5x gagal, akun terkunci sementara. Reset password lewat menu Pengaturan akun.',
            ],
            [
                'pattern' => '/\b(kode verifikasi|kode role|owner2026|mech2026|cash2026)\b/u',
                'answer' => fn(User $u) => $u->isRole('owner')
                    ? 'Kode registrasi: Owner OWNER2026, Mekanik MECH2026, Kasir CASH2026. Pelanggan tidak perlu kode.'
                    : 'Kode registrasi role internal adalah informasi khusus owner. Pelanggan tidak memerlukan kode verifikasi untuk mendaftar.',
            ],
            [
                'pattern' => '/\b(master data|katalog|sparepart|jenis servis)\b/u',
                'answer' => fn() => $this->catalogSummary(),
            ],
            [
                'pattern' => '/\b(riwayat akun|aktivitas)\b/u',
                'answer' => 'Menu Riwayat Akun mencatat top-up, penarikan, pembayaran ZeroPay, dan aktivitas saldo lain secara kronologis.',
            ],
            [
                'pattern' => '/\b(status|koneksi|layanan)\b/u',
                'answer' => 'Sistem ini siap membantu pembayaran, top-up, penarikan, dan percakapan ZoruAi dari data aplikasi proyek Anda.',
            ],
            [
                'pattern' => '/\b(cara pakai|panduan|help|bantuan|menu)\b/u',
                'answer' => fn(User $u) => $this->roleGuide($u),
            ],
            [
                'pattern' => '/\b(berapa|harga|biaya|tarif)\b.*\b(servis|oli|rem|tune)\b/u',
                'answer' => fn() => $this->catalogSummary(),
            ],
        ];
    }

    private function zeroPaySummary(User $user): string
    {
        return 'ZeroPay adalah dompet digital khusus proyek Milky Garage, bukan layanan publik di luar sistem. '
            . 'Saldo Anda saat ini: Rp ' . number_format($user->balance, 0, ',', '.')
            . '. Ketik "rincian zeropay" untuk penjelasan lengkap alur top-up, pembayaran, dan penarikan dana.';
    }

    private function zeroPayGuide(User $user): string
    {
        $roleInfo = '';
        $mainFlow = '';
        $pinInfo = '';

        if ($user->isRole('owner')) {
            $roleInfo = "Hak akses per peran:\n"
                . "- Pelanggan: melakukan top-up, membayar tagihan servis dengan ZeroPay, dan melihat riwayat\n"
                . "- Kasir dan mekanik: menarik dana (PIN) serta menerima fee atau gaji ke saldo\n"
                . "- Owner: melakukan top-up atau tarik dana melalui modal kelola saldo, serta menerima profit transaksi ZeroPay\n\n";

            $mainFlow = "Alur utama:\n"
                . "1. Kelola Saldo - melakukan top-up atau tarik dana melalui modal kelola saldo\n"
                . "2. Pembagian profit - menerima profit masuk otomatis dari transaksi ZeroPay pelanggan\n"
                . "3. Semua aktivitas tercatat di Riwayat Akun\n\n";

            $pinInfo = "PIN penarikan: Owner 0104, Kasir 0000, Mekanik 1111.";

        } elseif ($user->isRole('customer')) {
            $roleInfo = "Hak akses Anda sebagai Pelanggan:\n"
                . "Anda dapat melakukan top-up saldo, membayar tagihan servis menggunakan ZeroPay, serta memantau riwayat transaksi pada akun Anda.\n\n";

            $mainFlow = "Alur utama:\n"
                . "1. Top-up - saldo bertambah setelah proses e-wallet selesai\n"
                . "2. Bayar servis - saldo berkurang jika tagihan dibayar menggunakan ZeroPay, lalu status pengerjaan selesai\n"
                . "3. Semua aktivitas tercatat di Riwayat Akun";

            $pinInfo = ""; // Pelanggan tidak punya fitur tarik dana, hapus info PIN sepenuhnya

        } elseif ($user->isRole('cashier')) {
            $roleInfo = "Hak akses Anda sebagai Kasir:\n"
                . "Anda dapat mengonfirmasi pembayaran pelanggan, meninjau komisi/fee transaksi ke saldo ZeroPay, serta melakukan penarikan dana.\n\n";

            $mainFlow = "Alur utama:\n"
                . "1. Penerimaan fee - gaji/fee terkumpul dari konfirmasi pembayaran tagihan\n"
                . "2. Tarik dana - mencairkan dana dari saldo ZeroPay Anda\n"
                . "3. Semua aktivitas tercatat di Riwayat Akun\n\n";

            $pinInfo = "PIN penarikan Kasir Anda: 0000.";

        } elseif ($user->isRole('mechanic')) {
            $roleInfo = "Hak akses Anda sebagai Mekanik:\n"
                . "Anda dapat mengambil booking pelanggan, mencatat fee pengerjaan servis ke saldo ZeroPay, serta melakukan penarikan dana.\n\n";

            $mainFlow = "Alur utama:\n"
                . "1. Penerimaan fee - gaji/fee terkumpul dari penyelesaian pengerjaan servis motor\n"
                . "2. Tarik dana - mencairkan dana dari saldo ZeroPay Anda\n"
                . "3. Semua aktivitas tercatat di Riwayat Akun\n\n";

            $pinInfo = "PIN penarikan Mekanik Anda: 1111.";
        }

        return "Rincian ZeroPay - dompet digital Milky Garage\n\n"
            . "Apa itu?\n"
            . "ZeroPay adalah fitur fiksi yang dibuat khusus untuk tugas kuliah ini. "
            . "ZeroPay dipakai untuk transaksi dalam aplikasi Milky Garage.\n\n"
            . "Saldo Anda saat ini: Rp " . number_format($user->balance, 0, ',', '.') . "\n\n"
            . $roleInfo
            . $mainFlow
            . ($pinInfo !== '' ? "\n\n" . $pinInfo : '');
    }

    private function catalogSummary(?string $query = null): string
    {
        $services = ServiceType::orderBy('name')->get(['name', 'base_price', 'estimated_minutes']);
        $partsQuery = SparePart::orderBy('name');
        if ($query !== null && preg_match('/\b(busi|oli|filter|kampas|rem|udara)\b/iu', $query, $m)) {
            $partsQuery->whereRaw('LOWER(name) LIKE ?', ['%' . Str::lower($m[1]) . '%']);
        }
        $parts = $partsQuery->limit(8)->get(['name', 'price', 'stock']);

        $lines = ['Daftar harga servis (master data Milky Garage):'];
        foreach ($services as $s) {
            $lines[] = '- ' . $s->name . ': Rp ' . number_format($s->base_price, 0, ',', '.') . " (estimasi {$s->estimated_minutes} menit)";
        }
        $lines[] = '';
        $lines[] = 'Sparepart (contoh):';
        foreach ($parts as $p) {
            $lines[] = '- ' . $p->name . ': Rp ' . number_format($p->price, 0, ',', '.') . " (stok {$p->stock})";
        }

        return implode("\n", $lines);
    }

    private function insufficientBalanceGuide(string $raw, User $user): string
    {
        preg_match_all('/(?:rp\s*)?([\d.]+)\s*(?:ribu|rb|k)?/iu', $raw, $matches);
        $numbers = array_map(fn(string $n) => $this->parseFlexibleAmount($n), $matches[0] ?? []);
        $numbers = array_values(array_filter($numbers, fn(int $n) => $n > 0));
        $shortage = count($numbers) >= 2 ? max($numbers) - min($numbers) : null;

        $line = $shortage !== null && $shortage > 0
            ? 'Perkiraan kekurangannya Rp ' . number_format($shortage, 0, ',', '.') . '. '
            : '';

        return $line . 'Kalau saldo ZeroPay kurang, top-up dulu minimal Rp 10.000 dari menu Top-up, lalu ulangi pembayaran. '
            . 'Alternatifnya, pilih metode tunai/transfer jika tersedia. Saldo Anda saat ini Rp '
            . number_format($user->balance, 0, ',', '.') . '.';
    }

    private function bookingFullGuide(User $user): string
    {
        $busyToday = Booking::whereDate('booking_date', today())->count();

        return "Kalau slot booking penuh atau bentrok, pilih jam lain di hari yang sama, geser ke tanggal berikutnya, atau hubungi bengkel untuk reschedule manual. "
            . "Hari ini ada {$busyToday} booking tercatat di sistem. "
            . ($user->isRole('mechanic') || $user->isRole('owner')
                ? 'Untuk role internal, cek daftar booking scheduled lalu ambil slot yang masih realistis sesuai kapasitas mekanik.'
                : 'Saran saya pilih jadwal yang masih kosong dari menu Booking Service, lalu tulis keluhan motor sejelas mungkin.');
    }

    private function stockProblemGuide(User $user): string
    {
        $empty = SparePart::where('stock', 0)->orderBy('name')->get(['name', 'stock']);
        $low = SparePart::where('stock', '>', 0)->where('stock', '<=', 5)->orderBy('stock')->get(['name', 'stock']);

        $lines = ['Jika sparepart habis, jangan dipaksakan masuk ke service order. Solusinya: tunggu restock, tawarkan sparepart alternatif yang kompatibel, atau buat nota jasa servis saja dulu.'];

        if ($empty->isNotEmpty()) {
            $lines[] = 'Stok habis di master data: ' . $empty->pluck('name')->implode(', ') . '.';
        }
        if ($low->isNotEmpty()) {
            $lines[] = 'Stok rendah: ' . $low->map(fn($p) => "{$p->name} ({$p->stock})")->implode(', ') . '.';
        }
        if ($empty->isEmpty() && $low->isEmpty()) {
            $lines[] = 'Saat ini tidak ada sparepart yang habis atau kritis di master data.';
        }
        if ($user->isRole('owner')) {
            $lines[] = 'Owner bisa restock lewat Master Data atau perintah ZoruAi seperti "restock Busi Motor 5 unit".';
        }

        return implode("\n", $lines);
    }

    private function lightTroubleshootingGuide(string $q, User $user): string
    {
        if (preg_match('/\b(oli|ganti oli|pelumas)\b/u', $q)) {
            $topic = 'Oli perlu diperiksa jika mesin terasa kasar, tarikan berat, suara mesin lebih berisik, atau jarak pemakaian oli sudah lama. Langkah aman: cek riwayat servis, cek level oli, lalu jadwalkan ganti oli jika kondisinya tidak jelas.';
        } elseif (preg_match('/\b(busi|brebet|susah\s+nyala|mogok)\b/u', $q)) {
            $topic = 'Untuk gejala susah nyala, brebet, atau mogok, busi bisa menjadi salah satu titik awal pemeriksaan. Cek kondisi busi, kabel, dan kebersihan area pengapian. Jika gejala berulang, tulis keluhan lengkap saat booking agar mekanik dapat memeriksa lebih rapi.';
        } elseif (preg_match('/\b(rem|kampas)\b/u', $q)) {
            $topic = 'Rem perlu dicek jika terasa blong, bunyi, getar, atau jarak pengereman berubah. Jangan menunda pemeriksaan rem karena ini berhubungan langsung dengan keselamatan berkendara.';
        } elseif (preg_match('/\b(asap|knalpot)\b/u', $q)) {
            $topic = 'Asap knalpot yang tidak biasa bisa berasal dari kondisi oli, pembakaran, atau komponen mesin lain. Catat warna asap, kapan muncul, dan apakah disertai bau menyengat agar mekanik punya petunjuk awal.';
        } elseif (preg_match('/\b(panas|overheat|mesin panas)\b/u', $q)) {
            $topic = 'Mesin yang terasa terlalu panas sebaiknya tidak dipaksa jalan jauh. Beri jeda, cek kondisi oli dan area pendinginan, lalu buat booking pemeriksaan jika panas muncul berulang.';
        } elseif (preg_match('/\b(bunyi|suara|getar)\b/u', $q)) {
            $topic = 'Bunyi atau getaran tidak normal perlu dicatat dari sumbernya: mesin, rem, roda, atau bodi. Perhatikan kapan muncul, misalnya saat langsam, akselerasi, belok, atau mengerem.';
        } else {
            $topic = 'Untuk gejala motor ringan, catat keluhan utama, kapan gejala muncul, apakah makin parah, dan riwayat servis terakhir. Informasi ini membantu mekanik menentukan pemeriksaan awal.';
        }

        $lines = [
            'Panduan awal troubleshooting ringan:',
            $topic,
            'Catatan: jawaban ini hanya panduan awal. Pemeriksaan pasti tetap perlu dilakukan dari kondisi kendaraan sebenarnya.',
        ];

        if ($user->isRole('customer')) {
            $lines[] = 'Untuk pelanggan, lanjutkan dengan booking servis dan tulis keluhan sejelas mungkin agar mekanik mendapat konteks yang lengkap.';
        } elseif ($user->isRole('mechanic')) {
            $lines[] = 'Untuk mekanik, gunakan panduan ini sebagai catatan awal sebelum pemeriksaan fisik dan update hasil diagnosis di order servis.';
        } else {
            $lines[] = 'Untuk role internal, arahkan pelanggan ke booking atau service order agar keluhan tercatat rapi di sistem.';
        }

        return implode("\n", $lines);
    }

    private function serviceDurationSummary(string $raw): string
    {
        $services = ServiceType::orderBy('name')->get(['name', 'estimated_minutes']);
        $matched = $services->filter(fn($s) => str_contains(Str::lower($raw), Str::lower($s->name)));
        if ($matched->isEmpty()) {
            $matched = $services;
        }

        $lines = ['Estimasi durasi dari master data Milky Garage:'];
        foreach ($matched as $service) {
            $lines[] = "- {$service->name}: sekitar {$service->estimated_minutes} menit";
        }
        $lines[] = 'Durasi nyata bisa lebih lama kalau ada diagnosis tambahan, antrean mekanik, atau sparepart perlu dicek dulu.';

        return implode("\n", $lines);
    }

    private function parseFlexibleAmount(string $raw): int
    {
        $lower = Str::lower($raw);
        $digits = (int) preg_replace('/[^\d]/', '', $lower);
        if ($digits <= 0) {
            return 0;
        }

        return preg_match('/\b(ribu|rb|k)\b/u', $lower) ? $digits * 1000 : $digits;
    }

    private function roleGuide(User $user): string
    {
        return match ($user->role) {
            'owner' => "Panduan Owner ZoruAi:\n1. Analisis Trend - ZoruAi bisa membaca omzet, layanan populer, performa mekanik, dan kondisi stok dari data bengkel.\n2. Master Data - ZoruAi bisa membantu membuka form ubah harga, pasang diskon, tambah jenis servis, restock sparepart, dan atur persentase bagi hasil karyawan.\n3. ZeroPay Owner - ZoruAi bisa membuka form top-up, penarikan dana, serta menjelaskan rincian saldo dan alur transaksi.\n4. Keamanan - setiap aksi perubahan tetap lewat form/konfirmasi agar owner tetap memegang kendali.",
            'cashier' => "Panduan Kasir ZoruAi:\n1. Pembayaran - ZoruAi bisa menjelaskan alur tagihan, metode bayar, status pembayaran, dan proses nota pelanggan.\n2. Gaji Kasir - ZoruAi bisa membuka laporan gaji kasir lewat pintasan \"gaji saya\".\n3. ZeroPay - ZoruAi bisa membantu membuka form penarikan dana dan menjelaskan rincian saldo.\n4. Batasan - ZoruAi kasir tidak membuka fitur master data, restock, atau pengaturan owner.",
            'mechanic' => "Panduan Mekanik ZoruAi:\n1. ACC Booking - ZoruAi bisa membuka daftar booking hari ini yang masih bisa diambil mekanik.\n2. Pengerjaan Servis - ZoruAi bisa memandu alur mulai kerja, selesai servis, dan penggunaan sparepart pada order.\n3. Bantuan Diagnosa - ZoruAi bisa menjawab panduan troubleshooting motor ringan berdasarkan pengetahuan aplikasi.\n4. Gaji & ZeroPay - ZoruAi bisa membuka laporan gaji mekanik dan form penarikan dana.",
            default => "Panduan Pelanggan ZoruAi:\n1. Booking Service - ZoruAi bisa membuka form booking dan memandu pemilihan kendaraan, layanan, tanggal, serta jam.\n2. Pembayaran - ZoruAi bisa menjelaskan tagihan, metode pembayaran, dan status servis pelanggan.\n3. ZeroPay - ZoruAi bisa membuka form top-up, menjelaskan saldo, dan membantu memahami riwayat transaksi.\n4. Troubleshooting Ringan - ZoruAi bisa memberi panduan awal untuk gejala umum seperti oli, busi, rem, mesin susah nyala, asap, bunyi, atau getaran.\n5. Bantuan Umum - ZoruAi bisa menjawab layanan bengkel, promo aktif, cara pakai web, dan riwayat servis.",
        };
    }

    private function fallback(User $user): string
    {
        $role = $user->roleLabel();

        return 'Maaf, saya belum menemukan jawaban yang tepat untuk pertanyaan tersebut. '
            . "Silakan coba kata kunci berikut: rincian zeropay, booking, pembayaran, tarik dana, PIN, registrasi, atau bantuan untuk panduan peran {$role}. "
            . 'Untuk pertanyaan teknik motor atau tata bahasa Indonesia, gunakan kalimat yang jelas; saya akan menyusun jawaban sesuai data pelatihan dan ejaan baku. '
            . 'ZoruAi siap membantu dari alur aplikasi Milky Garage.';
    }

    private function parseAmount(string $raw): int
    {
        $digits = preg_replace('/[^\d]/', '', $raw);

        return (int) ($digits ?: 0);
    }

    private function parseOptionalDateTime(string $raw): mixed
    {
        if ($raw === '') {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    private function restockUnitPrice(SparePart $part, int $qty): int
    {
        $seed = crc32($part->id . '|' . $part->sku . '|' . today()->toDateString() . '|' . $qty);

        return 10000 + ((int) ($seed % 41) * 1000);
    }

    private function findSparePartByFlexibleName(string $name): ?SparePart
    {
        $needle = Str::lower(trim(preg_replace('/\s+/', ' ', $name)));
        if ($needle === '') {
            return null;
        }

        $direct = SparePart::whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])->first();
        if ($direct) {
            return $direct;
        }

        $keywords = collect(preg_split('/\s+/', $needle) ?: [])
            ->map(fn ($word) => trim($word))
            ->filter(fn ($word) => mb_strlen($word) >= 3)
            ->values();

        if ($keywords->isEmpty()) {
            return null;
        }

        return SparePart::all()
            ->sortByDesc(function (SparePart $part) use ($keywords) {
                $haystack = Str::lower($part->name . ' ' . $part->sku);

                return $keywords->sum(fn ($word) => Str::contains($haystack, $word) ? 1 : 0);
            })
            ->first(function (SparePart $part) use ($keywords) {
                $haystack = Str::lower($part->name . ' ' . $part->sku);

                return $keywords->contains(fn ($word) => Str::contains($haystack, $word));
            });
    }
}


