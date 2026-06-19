<?php

namespace App\Services;

use App\Models\AccountActivity;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\User;

/**
 * Menangani distribusi uang setelah pembayaran servis dikonfirmasi.
 *
 * Logika ini sebelumnya ditulis dua kali (di markPaid dan payCustomer).
 * Sekarang dipusatkan di sini agar perubahan aturan bisnis cukup diubah
 * di satu tempat dan berlaku untuk semua jalur pembayaran.
 */
class PaymentDistributionService
{
    /**
     * Distribusikan pembayaran: potong/catat uang masuk ke mekanik, kasir, dan owner.
     * Buat invoice dan kembalikan instance Invoice yang dibuat.
     *
     * @param  Payment       $payment       Payment yang baru saja lunas
     * @param  ServiceOrder  $order         Service order terkait (harus sudah di-load relasinya)
     * @param  User|null     $processingCashier  Kasir yang memproses (null jika customer yang bayar sendiri)
     * @param  string|null   $recipientChannel   Channel penerimaan nota (opsional)
     * @return Invoice
     */
    public function distribute(
        Payment $payment,
        ServiceOrder $order,
        ?User $processingCashier = null,
        ?string $recipientChannel = null
    ): Invoice {
        $shares = $order->laborShareBreakdown();

        // 1. Bagi hasil mekanik dari biaya jasa.
        $mechanicSalary = 0;
        if ($order->mechanic_id) {
                $mechanic = User::find($order->mechanic_id);
                if ($mechanic) {
                    $mechanicSalary = $shares['mechanic'];
                    $mechanic->addBalance($mechanicSalary);
                AccountActivity::create([
                    'user_id'     => $mechanic->id,
                    'type'        => 'money_in',
                    'description' => 'Bagi hasil mekanik dari jasa servis ' . ($order->customer?->username ?? '-'),
                    'amount'      => $mechanicSalary,
                ]);
            }
        }

        // 2. Bagi hasil kasir dari biaya jasa.
        $cashierSalary = 0;
        $cashier = $processingCashier ?? User::where('role', 'cashier')->first();
        if ($cashier) {
            // Gaji harian kasir tetap ada, hanya sekali per hari per kasir.
            $hasDailySalary = AccountActivity::where('user_id', $cashier->id)
                ->where('type', 'money_in')
                ->where('description', 'Gaji Harian Kasir')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (! $hasDailySalary) {
                $cashier->increment('unclaimed_salary', 50000);
                AccountActivity::create([
                    'user_id'     => $cashier->id,
                    'type'        => 'money_in',
                    'description' => 'Gaji Harian Kasir',
                    'amount'      => 50000,
                ]);
            }

            $cashierSalary = $shares['cashier'];
            $cashier->increment('unclaimed_salary', $cashierSalary);
            AccountActivity::create([
                'user_id'     => $cashier->id,
                'type'        => 'money_in',
                'description' => 'Bagi hasil kasir dari jasa servis ' . ($order->customer?->username ?? '-'),
                'amount'      => $cashierSalary,
            ]);
        }

        // 3. Owner menerima sisa jasa dan seluruh penjualan sparepart.
        $owner = User::where('role', 'owner')->first();
        if ($owner) {
            $netProfit = $shares['owner_total'];
            $owner->addBalance($netProfit);
            $methodLabel = $payment->method ?? 'Tidak diketahui';
            AccountActivity::create([
                'user_id'     => $owner->id,
                'type'        => 'money_in',
                'description' => 'Profit owner sisa jasa + sparepart servis ' . ($order->customer?->username ?? '-') . " ({$methodLabel})",
                'amount'      => $netProfit,
            ]);
        }

        // 4. Buat invoice
        $invoice = Invoice::firstOrCreate(
            ['payment_id' => $payment->id],
            [
                'invoice_number'    => 'INV-' . now()->format('YmdHis') . '-' . $payment->id,
                'recipient_channel' => $recipientChannel,
                'issued_at'         => now(),
            ]
        );

        return $invoice;
    }
}
