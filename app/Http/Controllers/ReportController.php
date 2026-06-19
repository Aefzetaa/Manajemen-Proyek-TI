<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ServiceType;
use App\Models\Payment;
use App\Models\AccountActivity;
use App\Models\SparePart;
use App\Models\Promotion;
use App\Services\ZoruAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function publicZoruAi(Request $request)
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'max:500'],
        ]);

        $prompt = trim(strip_tags($validated['prompt']));
        $q = Str::lower($prompt);

        $sensitivePattern = '/\b(kode|pin|password|owner|kasir|mekanik|gaji|laporan|keuangan|restock|database|endpoint|route|token|hidden|withdraw|ubah\s+harga|hapus|tambah\s+servis|master\s+data|akun\s+orang|saldo\s+akun)\b/u';
        if (preg_match($sensitivePattern, $q)) {
            return response()->json([
                'success' => true,
                'reply' => 'ZoruAi Assistant publik hanya membantu informasi layanan bengkel, promo, cara booking, pembayaran umum, ZeroPay secara umum, dan panduan web Milky Garage. Untuk kode, PIN, data akun, laporan, gaji, atau perintah internal, silakan login dengan role yang sesuai.',
            ]);
        }

        if (preg_match('/\b(layanan|servis|service|harga|biaya|tarif|berapa|oli|rem|tune)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => $this->publicServiceSummary(),
            ]);
        }

        if (preg_match('/\b(promo|diskon|potongan)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => $this->publicPromotionSummary(),
            ]);
        }

        if (preg_match('/\b(booking|pesan|jadwal|daftar|login|masuk|registrasi|buat akun)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => 'Untuk booking servis, buat akun atau masuk terlebih dahulu, lalu buka menu Booking Service. Setelah itu pilih layanan, kendaraan, tanggal, jam tersedia, dan tulis keluhan motor. Tim bengkel akan memproses jadwal sesuai status booking di sistem.',
            ]);
        }

        if (preg_match('/\b(panduan|cara pakai|web|website|sistem|menu|home)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => 'Panduan singkat Milky Garage: Home berisi pengenalan bengkel, Layanan menampilkan daftar servis, Promo menampilkan diskon aktif, Tentang Kami menjelaskan bengkel dan sistem, Masuk dipakai untuk akun yang sudah ada, dan Daftar dipakai untuk membuat akun pelanggan.',
            ]);
        }

        if (preg_match('/\b(zoru|zoruai|ai|assistant|asisten|kamu siapa|siapa kamu)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => 'Saya ZoruAi Assistant publik untuk Milky Garage. Saya membantu menjawab pertanyaan umum tentang bengkel, layanan, promo, alur booking, pembayaran umum, dan panduan penggunaan web.',
            ]);
        }

        if (preg_match('/\b(zeropay|pembayaran|bayar|tunai|saldo|topup|top-up)\b/u', $q)) {
            return response()->json([
                'success' => true,
                'reply' => 'Pembayaran di Milky Garage dapat dipahami secara umum melalui tagihan servis setelah pekerjaan diproses. ZeroPay adalah saldo akun untuk mempermudah pembayaran di Milky Garage. Detail saldo pribadi hanya bisa dilihat setelah login.',
            ]);
        }

        return response()->json([
            'success' => true,
            'reply' => 'Saya hanya bisa membantu seputar Milky Garage: layanan bengkel, promo, cara booking, pembayaran umum, ZeroPay secara umum, dan panduan web. Coba tanyakan "daftar layanan", "promo aktif", atau "cara booking".',
        ]);
    }

    private function publicServiceSummary(): string
    {
        $services = ServiceType::with('activePromotions')->orderBy('name')->get();

        if ($services->isEmpty()) {
            return 'Belum ada layanan bengkel yang tersedia saat ini.';
        }

        $lines = ['Daftar layanan Milky Garage:'];

        foreach ($services as $service) {
            $price = $service->hasActiveDiscount()
                ? 'Rp ' . number_format($service->discountedPrice(), 0, ',', '.') . ' dari Rp ' . number_format($service->base_price, 0, ',', '.') . ' (diskon ' . $service->discountPercent() . '%)'
                : 'Rp ' . number_format($service->base_price, 0, ',', '.');

            $lines[] = '- ' . $service->name . ': ' . $price . ', estimasi ' . $service->estimated_minutes . ' menit.';
        }

        return implode("\n", $lines);
    }

    private function publicPromotionSummary(): string
    {
        $promotions = Promotion::with('serviceType')->active()->latest()->get();

        if ($promotions->isEmpty()) {
            return 'Belum ada promo aktif saat ini. Anda tetap bisa melihat daftar layanan dan membuat booking setelah masuk atau daftar akun.';
        }

        $lines = ['Promo aktif Milky Garage:'];

        foreach ($promotions as $promo) {
            $service = $promo->serviceType?->name ?? 'Semua layanan';
            $until = $promo->ends_at ? ', berlaku sampai ' . $promo->ends_at->format('d/m/Y H:i') : '';
            $lines[] = '- ' . $promo->title . ': diskon ' . $promo->discount_percent . '% untuk ' . $service . $until . '.';
        }

        return implode("\n", $lines);
    }

    public function finance(Request $request): View|StreamedResponse
    {
        $date = $request->date('date')?->toDateString() ?? today()->toDateString();

        $payments = Payment::with(['serviceOrder.customer', 'serviceOrder.vehicle'])
            ->where('status', 'paid')
            ->whereDate('paid_at', $date)
            ->latest('paid_at')
            ->get();

        if ($request->query('export') === 'csv') {
            return $this->downloadCsv('laporan-keuangan-' . $date . '.csv', [
                ['Waktu', 'Pelanggan', 'Kendaraan', 'Metode', 'Jumlah'],
                ...$payments->map(fn(Payment $payment) => [
                    $payment->paid_at?->format('H:i'),
                    $payment->serviceOrder->customer->name,
                    $payment->serviceOrder->vehicle?->plate_number,
                    $payment->method,
                    $payment->amount,
                ])->all(),
            ]);
        }

        return view('reports.finance', [
            'date' => $date,
            'payments' => $payments,
            'totalRevenue' => $payments->sum('amount'),
            'methodTotals' => $payments
                ->groupBy(fn(Payment $payment) => $payment->method ?: 'Belum dicatat')
                ->map(fn($group) => $group->sum('amount')),
        ]);
    }

    public function cashier(Request $request): View|StreamedResponse
    {
        $date = $request->date('date')?->toDateString() ?? today()->toDateString();

        $payments = Payment::with(['serviceOrder.customer', 'serviceOrder.vehicle', 'cashier'])
            ->where('status', 'paid')
            ->whereDate('paid_at', $date)
            ->latest('paid_at')
            ->get();

        if ($request->query('export') === 'csv') {
            return $this->downloadCsv('laporan-transaksi-kasir-' . $date . '.csv', [
                ['Referensi', 'Pelanggan', 'Metode', 'Kasir', 'Jumlah'],
                ...$payments->map(fn(Payment $payment) => [
                    $payment->reference_no,
                    $payment->serviceOrder->customer->name,
                    $payment->method ?? '-',
                    $payment->cashier?->name ?? '-',
                    $payment->amount,
                ])->all(),
            ]);
        }

        return view('reports.cashier', [
            'date' => $date,
            'payments' => $payments,
            'totalCount' => $payments->count(),
            'totalRevenue' => $payments->sum('amount'),
        ]);
    }

    public function analytics(Request $request, ZoruAiService $zoruAi): View
    {
        $chats = collect();

        $serviceTypes = ServiceType::orderBy('name')->get();

        $quarterOptions = [
            1 => [
                'label' => 'Periode 1',
                'range' => 'Januari - Juni',
                'months' => range(1, 6),
            ],
            2 => [
                'label' => 'Periode 2',
                'range' => 'Juli - Desember',
                'months' => range(7, 12),
            ],
        ];

        $selectedQuarter = $request->integer('quarter', 1);
        if (! array_key_exists($selectedQuarter, $quarterOptions)) {
            $selectedQuarter = 1;
        }

        $trendYear = now()->year;
        $quarterMonths = $quarterOptions[$selectedQuarter]['months'];
        $startDate = \Carbon\Carbon::create($trendYear, $quarterMonths[0], 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($trendYear, $quarterMonths[5], 1)->endOfMonth();

        $query = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($request->filled('service_type_id')) {
            $typeId = $request->integer('service_type_id');
            $query->whereHas('serviceTypes', function ($q) use ($typeId) {
                $q->where('service_types.id', $typeId);
            });
        }

        $bookingData = $query
            ->selectRaw("DATE_FORMAT(booking_date, '%Y-%m') as month_key, COUNT(*) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $monthlyTrends = collect($quarterMonths)->map(function ($month) use ($bookingData, $trendYear) {
            $date = \Carbon\Carbon::create($trendYear, $month, 1);
            $monthKey = $date->format('Y-m');
            return [
                'month_name' => $date->translatedFormat('F'),
                'month_key' => $monthKey,
                'count' => $bookingData->get($monthKey, 0),
            ];
        });

        return view('reports.analytics', compact(
            'chats',
            'zoruAi',
            'serviceTypes',
            'monthlyTrends',
            'quarterOptions',
            'selectedQuarter',
            'trendYear'
        ));
    }

    public function zoruAi(Request $request, ZoruAiService $zoruAi)
    {
        // Detect AJAX custom action for salary reports
        if ($request->input('action') === 'get_salary_report') {
            $month = (int) $request->input('month', now()->month);
            $year = (int) $request->input('year', now()->year);
            $user = $request->user();
            
            $earned = \App\Models\AccountActivity::where('user_id', $user->id)
                ->where('type', 'money_in')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('amount');
                
            return response()->json([
                'success' => true,
                'earned' => $earned,
            ]);
        }

        if ($request->input('action') === 'topup_zeropay') {
            $validated = $request->validate([
                'amount' => ['required', 'integer', 'min:10000', 'max:10000000'],
                'method' => ['required', 'string', 'in:gopay,ovo,dana,shopeepay'],
            ]);

            $user = $request->user()->fresh();
            $amount = (int) $validated['amount'];

            if (! $user->canReceiveBalance($amount)) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'reply' => 'Saldo ZeroPay maksimal Rp ' . number_format($user::MAX_BALANCE, 0, ',', '.') . '. Kurangi nominal top-up agar tidak melewati batas.',
                    'balance' => $user->balance,
                    'amount' => $amount,
                    'transaction_type' => 'topup',
                ], 422);
            }

            $user->addBalance($amount);
            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'money_in',
                'description' => 'Top-up ZeroPay via ' . strtoupper($validated['method']),
                'amount' => $amount,
            ]);

            $fresh = $user->fresh();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'reply' => 'Top-up ZeroPay Rp ' . number_format($amount, 0, ',', '.') . ' berhasil ditambahkan via ' . strtoupper($validated['method']) . '.',
                'balance' => $fresh->balance,
                'amount' => $amount,
                'transaction_type' => 'topup',
            ]);
        }

        if ($request->input('action') === 'withdraw_zeropay') {
            $validated = $request->validate([
                'amount' => ['required', 'integer', 'min:1000'],
                'wallet' => ['required', 'string', 'max:40'],
                'pin' => ['required', 'string', 'min:4', 'max:12'],
            ]);

            $user = $request->user()->fresh();
            $amount = (int) $validated['amount'];
            $wallet = strtoupper(preg_replace('/[^a-zA-Z0-9_ -]/', '', $validated['wallet']) ?: 'TUJUAN');

            if ((float) $user->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'reply' => 'Saldo ZeroPay Anda belum cukup untuk nominal penarikan tersebut.',
                    'balance' => $user->balance,
                    'amount' => $amount,
                    'transaction_type' => 'withdraw',
                ], 422);
            }

            if (! $this->verifyWithdrawPin($user, (string) $validated['pin'])) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'reply' => 'PIN verifikasi belum sesuai.',
                    'balance' => $user->balance,
                    'amount' => $amount,
                    'transaction_type' => 'withdraw',
                ], 422);
            }

            $user->decrement('balance', $amount);
            AccountActivity::create([
                'user_id' => $user->id,
                'type' => 'money_out',
                'description' => 'Tarik dana ZeroPay ke ' . $wallet,
                'amount' => -abs($amount),
            ]);

            $fresh = $user->fresh();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'reply' => 'Tarik dana ZeroPay Rp ' . number_format($amount, 0, ',', '.') . ' menuju ' . $wallet . ' berhasil diproses.',
                'balance' => $fresh->balance,
                'amount' => $amount,
                'transaction_type' => 'withdraw',
            ]);
        }
        $prompt = $request->input('prompt', '');

        $restockPreview = $zoruAi->restockPreview($prompt, $request->user());
        $action = null;

        if ($restockPreview !== null) {
            $token = (string) Str::uuid();
            // Ensure restock preview is an array before merging/storing
            $restockPreviewArray = is_array($restockPreview) ? $restockPreview : (array) $restockPreview;

            Session::put("zoruai_restock.{$token}", array_merge(
                $restockPreviewArray,
                ['user_id' => $request->user()->id]
            ));

            $action = [
                'type' => 'restock_sparepart',
                'token' => $token,
                ...$restockPreviewArray,
                'balance' => $request->user()->balance,
            ];
        } else {
            $cleanedPrompt = Str::lower(trim($prompt));
            if ($cleanedPrompt === 'ubah harga servis' || $cleanedPrompt === 'ubah harga') {
                $action = ['type' => 'update_price_form'];
            } elseif ($cleanedPrompt === 'diskon servis' || $cleanedPrompt === 'diskon') {
                $action = ['type' => 'apply_discount_form'];
            } elseif ($cleanedPrompt === 'restock sparepart' || $cleanedPrompt === 'restock') {
                $action = ['type' => 'restock_part_form'];
            } elseif ($cleanedPrompt === 'gaji karyawan' || $cleanedPrompt === 'atur gaji' || $cleanedPrompt === 'gaji') {
                $action = ['type' => 'set_salary_form'];
            } elseif ($cleanedPrompt === 'tambah servis' || $cleanedPrompt === 'tambah jenis servis' || $cleanedPrompt === 'buat servis') {
                $action = ['type' => 'add_service_form'];
            } elseif ($cleanedPrompt === 'hapus servis' || $cleanedPrompt === 'hapus jenis servis') {
                $action = ['type' => 'delete_service_form'];
            } elseif ($cleanedPrompt === 'tambah sparepart' || $cleanedPrompt === 'tambah spare part' || $cleanedPrompt === 'buat sparepart') {
                $action = ['type' => 'add_spare_part_form'];
            } elseif ($cleanedPrompt === 'hapus sparepart' || $cleanedPrompt === 'hapus spare part') {
                $action = ['type' => 'delete_spare_part_form'];
            } elseif ($cleanedPrompt === 'top-up saldo' || $cleanedPrompt === 'top up saldo' || $cleanedPrompt === 'isi saldo' || $cleanedPrompt === 'topup' || $cleanedPrompt === 'top up') {
                $action = ['type' => 'topup_form'];
            } elseif ($cleanedPrompt === 'tarik dana' || $cleanedPrompt === 'tarik saldo' || $cleanedPrompt === 'withdraw') {
                $action = ['type' => 'withdraw_form'];
            } elseif ($cleanedPrompt === 'booking' || $cleanedPrompt === 'pesan servis' || $cleanedPrompt === 'booking service baru' || $cleanedPrompt === 'booking baru' || $cleanedPrompt === 'jadwal') {
                $action = ['type' => 'booking_form'];
            } elseif ($cleanedPrompt === 'acc booking' || $cleanedPrompt === 'terima booking') {
                $user = $request->user();
                if ($user->isRole('mechanic')) {
                    $bookingsToday = \App\Models\Booking::with(['user', 'vehicle', 'serviceTypes'])
                        ->whereDate('booking_date', today())
                        ->get();
                    
                    $bookingsData = [];
                    foreach ($bookingsToday as $booking) {
                        $bookingsData[] = [
                            'id' => $booking->id,
                            'customer_name' => $booking->user->username ?? $booking->user->name ?? '-',
                            'plate_number' => $booking->vehicle->plate_number ?? '-',
                            'brand' => $booking->vehicle->brand ?? '-',
                            'model' => $booking->vehicle->model ?? '-',
                            'services' => $booking->serviceTypes->pluck('name')->all(),
                            'mechanic_id' => $booking->mechanic_id,
                            'status' => $booking->status,
                        ];
                    }
                    
                    $action = [
                        'type' => 'acc_booking_form',
                        'bookings' => $bookingsData,
                    ];
                }
            } elseif ($cleanedPrompt === 'gaji saya' || $cleanedPrompt === 'laporan gaji' || $cleanedPrompt === 'rincian gaji') {
                $user = $request->user();
                if ($user->isRole('cashier') || $user->isRole('mechanic')) {
                    $action = [
                        'type' => 'salary_report_form',
                        'role' => $user->role,
                    ];
                }
            }
        }

        $silentActionTypes = [
            'update_price_form',
            'apply_discount_form',
            'restock_part_form',
            'set_salary_form',
            'add_service_form',
            'delete_service_form',
            'add_spare_part_form',
            'delete_spare_part_form',
            'topup_form',
            'withdraw_form',
            'booking_form',
            'salary_report_form',
            'acc_booking_form',
        ];

        $actionType = $action['type'] ?? null;
        $reply = in_array($actionType, $silentActionTypes, true)
            ? ''
            : $zoruAi->reply($prompt, $request->user());

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'action' => $action,
            'balance' => $request->user()->fresh()->balance,
        ], 200);
    }

    private function verifyWithdrawPin($user, string $pin): bool
    {
        if (! $user->withdraw_pin) {
            $defaultPin = '1234';
            if ($user->isRole('owner')) {
                $defaultPin = '0104';
            } elseif ($user->isRole('cashier')) {
                $defaultPin = '0000';
            } elseif ($user->isRole('mechanic')) {
                $defaultPin = '1111';
            }

            return $pin === $defaultPin || $pin === '1234';
        }

        return Hash::check($pin, $user->withdraw_pin);
    }
    public function zoruAiRestock(Request $request)
    {
        abort_unless($request->user()->isRole('owner'), 403);

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $sessionKey = "zoruai_restock.{$validated['token']}";
        $payload = Session::pull($sessionKey, []);
        if (! is_array($payload) || ($payload['user_id'] ?? null) !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu restock sudah kedaluwarsa. Kirim ulang perintah restock ke ZoruAi.',
            ], 422);
        }

        $result = DB::transaction(function () use ($request, $payload) {
            $owner = $request->user()->fresh();
            $part = SparePart::lockForUpdate()->find($payload['spare_part_id']);

            if (! $part) {
                return [
                    'success' => false,
                    'message' => 'Sparepart tidak ditemukan di master data.',
                    'status' => 404,
                ];
            }

            $qty = (int) $payload['qty'];
            $unitPrice = (int) $payload['unit_price'];
            $total = (int) $payload['total'];

            if ($owner->balance < $total) {
                return [
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi.',
                    'status' => 422,
                ];
            }

            $owner->decrement('balance', $total);
            $part->increment('stock', $qty);

            AccountActivity::create([
                'user_id' => $owner->id,
                'type' => 'money_out',
                'description' => 'Restock via ZoruAi ' . $part->name . ' (' . $qty . ' pcs @ Rp ' . number_format($unitPrice, 0, ',', '.') . ')',
                'amount' => -$total,
            ]);

            $freshPart = $part->fresh();
            $freshOwner = $owner->fresh();

            return [
                'success' => true,
                'message' => 'Restock berhasil. Stok ' . $freshPart->name . ' sekarang ' . $freshPart->stock . ' unit. Saldo ZeroPay owner tersisa Rp ' . number_format($freshOwner->balance, 0, ',', '.') . '.',
                'stock' => $freshPart->stock,
                'balance' => $freshOwner->balance,
                'status' => 200,
            ];
        });

        return response()->json($result, $result['status'] ?? 200);
    }

    public function zoruAiCancelRestock(Request $request)
    {
        abort_unless($request->user()->isRole('owner'), 403);

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $sessionKey = "zoruai_restock.{$validated['token']}";
        $payload = Session::pull($sessionKey, []);

        if (! is_array($payload) || ($payload['user_id'] ?? null) !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi restock sudah tidak aktif.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi restock dibatalkan.',
            'balance' => $request->user()->fresh()->balance,
        ]);
    }

    private function downloadCsv(string $filename, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}


