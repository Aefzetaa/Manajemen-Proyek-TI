<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Services\PaymentDistributionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['serviceOrder.customer', 'serviceOrder.vehicle', 'serviceOrder.serviceType', 'serviceOrder.items', 'invoice'])
            ->latest();

        if ($request->user()->isRole('customer')) {
            $query->whereHas('serviceOrder', fn($q) => $q->where('customer_id', $request->user()->id))
                  ->where('status', 'pending');
        } elseif ($request->user()->isRole('cashier')) {
            // Kasir melihat waiting_cashier ATAU (pending DAN method Tunai)
            $query->whereHas('serviceOrder', function($q) {
                      $q->whereNotIn('status', ['waiting_approval']);
                  })
                  ->where(function($q) {
                      $q->where(function($q2) {
                          $q2->whereHas('serviceOrder', fn($q3) => $q3->where('status', 'waiting_cashier'));
                      })->orWhere(function($q2) {
                          $q2->where('status', 'pending')->where(function($q3) {
                              $q3->where('method', 'Tunai')->orWhereNull('method');
                          });
                      });
                  });

            // Filter jenis servis (Matic / Manual / Kopling)
            if ($request->filled('service_type')) {
                $query->whereHas('serviceOrder.serviceType', fn($q) =>
                    $q->where('name', 'like', '%' . $request->input('service_type') . '%')
                );
            }
        } else {
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date('date'));
        }

        return view('payments.index', [
            'payments' => $query->paginate(20)->withQueryString(),
            'statuses' => Payment::STATUSES,
        ]);
    }

    public function markPaid(Request $request, Payment $payment, PaymentDistributionService $distribution): RedirectResponse
    {
        $payment->load(['serviceOrder.booking.serviceTypes', 'serviceOrder.customer']);

        if ($payment->status === 'paid') {
            return back()->with('success', 'Pembayaran ini sudah berstatus lunas.');
        }

        if ($payment->serviceOrder->status === 'waiting_cashier') {
            return back()->withErrors(['payment' => 'Rincian biaya belum dikirim ke pelanggan.']);
        }

        if ($payment->serviceOrder->status === 'waiting_approval') {
            return back()->withErrors(['payment' => 'Rincian biaya belum disetujui pelanggan.']);
        }

        $validated = $request->validate([
            'method'           => ['required', 'string', 'max:50'],
            'recipient_channel'=> ['nullable', 'string', 'max:50'],
            'cash_received'    => ['required', 'integer', 'min:' . $payment->amount],
        ], [
            'cash_received.required' => 'Masukkan jumlah uang yang diterima dari pelanggan.',
            'cash_received.min'      => 'Uang diterima minimal Rp ' . number_format($payment->amount, 0, ',', '.') . ' (sesuai tagihan).',
        ]);

        $payment->update([
            'cashier_id' => $request->user()->id,
            'method'     => $validated['method'],
            'status'     => 'paid',
            'paid_at'    => now(),
        ]);

        $payment->serviceOrder()->update(['status' => 'finished']);

        $distribution->distribute(
            $payment,
            $payment->serviceOrder,
            $request->user(),                     // kasir yang sedang login
            $validated['recipient_channel'] ?? null
        );

        return back()->with('show_nota_cashier', $payment->id);
    }

    public function payCustomer(Request $request, Payment $payment, PaymentDistributionService $distribution): RedirectResponse
    {
        $payment->load(['serviceOrder.booking.serviceTypes', 'serviceOrder.customer']);

        abort_unless($request->user()->id === $payment->serviceOrder->customer_id, 403);

        if ($payment->status === 'paid') {
            return back()->with('success', 'Pembayaran ini sudah berstatus lunas.');
        }

        if ($payment->serviceOrder->status === 'waiting_cashier') {
            return back()->withErrors(['payment' => 'Kasir belum mengirim rincian biaya.']);
        }

        if ($payment->serviceOrder->status === 'waiting_approval') {
            return back()->withErrors(['payment' => 'Anda harus menyetujui rincian biaya terlebih dahulu.']);
        }

        $validated = $request->validate([
            'method' => ['required', 'string', 'in:ZeroPay,Tunai'],
        ]);

        if ($validated['method'] === 'ZeroPay') {
            $user = $request->user();
            if ($user->balance < $payment->amount) {
                return back()->withErrors(['payment' => 'Saldo ZeroPay Anda tidak mencukupi. Silakan Top-Up atau gunakan metode Tunai.']);
            }

            $user->decrement('balance', $payment->amount);

            \App\Models\AccountActivity::create([
                'user_id'     => $user->id,
                'type'        => 'money_out',
                'description' => 'Status Service',
                'amount'      => -abs($payment->amount),
            ]);

            $payment->update([
                'method'  => 'ZeroPay',
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            $payment->serviceOrder()->update(['status' => 'finished']);

            $invoice = $distribution->distribute($payment, $payment->serviceOrder);

            Message::create([
                'user_id' => $user->id,
                'title'   => 'Nota Servis (Lunas via ZeroPay)',
                'body'    => route('payments.invoice', $invoice),
                'is_read' => false,
            ]);

            return back()->with('show_zeropay_receipt', $payment->id);
        }

        // Tunai: tandai metode, kasir yang akan konfirmasi lunas
        $payment->update(['method' => 'Tunai']);
        return back()->with('success', 'Metode Tunai dipilih. Silakan serahkan uang kepada kasir di bengkel untuk diselesaikan.');
    }

    public function invoice(Invoice $invoice): View
    {
        $invoice->load(['payment.serviceOrder.customer', 'payment.serviceOrder.vehicle', 'payment.serviceOrder.items']);

        $user = request()->user();
        abort_unless(
            $user->hasAnyRole(['owner', 'cashier']) || $invoice->payment->serviceOrder->customer_id === $user->id,
            403
        );

        return view('payments.invoice', [
            'invoice' => $invoice,
        ]);
    }

    public function giveNota(Request $request, Payment $payment): \Illuminate\Http\JsonResponse
    {
        abort_unless($request->user()->isRole('cashier'), 403);
        $payment->load('serviceOrder.customer');
        
        $invoice = $payment->invoice;

        \App\Models\Message::create([
            'user_id' => $payment->serviceOrder->customer_id,
            'title' => 'Nota Servis',
            'body' => route('payments.invoice', $invoice),
            'is_read' => false,
        ]);

        return response()->json(['success' => true]);
    }

    private function serviceCustomerLabel(ServiceOrder $order): string
    {
        $order->loadMissing('customer');

        return $order->customer?->username ?? '-';
    }
}
