<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\SparePart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = ServiceOrder::with(['booking.serviceTypes', 'customer', 'mechanic', 'vehicle', 'items', 'payment'])
            ->latest('serviced_at');

        if ($request->user()->isRole('customer')) {
            $query->where('customer_id', $request->user()->id);
            
            $year = $request->input('year', date('Y'));
            $query->whereYear('serviced_at', $year);
        } elseif ($request->user()->isRole('mechanic')) {
            $query->where('mechanic_id', $request->user()->id)
                  ->where('status', 'finished'); // Only finished jobs count for salary
                  
            $month = $request->input('month', date('Y-m'));
            $parts = explode('-', $month);
            if (count($parts) === 2) {
                $query->whereYear('serviced_at', $parts[0])
                      ->whereMonth('serviced_at', $parts[1]);
            }
        } elseif ($request->user()->isRole('cashier')) {
            // Cashier Laporan Gaji
            $query->whereRaw('1 = 0'); // empty the orders list for now, we'll use AccountActivity instead
        } else {
            if ($request->filled('from')) {
                $query->whereDate('serviced_at', '>=', $request->date('from'));
            }

            if ($request->filled('to')) {
                $query->whereDate('serviced_at', '<=', $request->date('to'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('service_type_id')) {
                $query->where('service_type_id', $request->integer('service_type_id'));
            }
        }

        $mechanicTotalSalary = 0;
        if ($request->user()->isRole('mechanic')) {
            $month = $request->input('month', date('Y-m'));
            $parts = explode('-', $month);
            if (count($parts) === 2) {
                $mechanicTotalSalary = \App\Models\AccountActivity::where('user_id', $request->user()->id)
                    ->where('type', 'money_in')
                    ->where('description', 'like', 'Gaji dari Servis %')
                    ->whereYear('created_at', $parts[0])
                    ->whereMonth('created_at', $parts[1])
                    ->sum('amount');
            }
        } elseif ($request->user()->isRole('cashier')) {
            $month = $request->input('month', date('Y-m'));
            $parts = explode('-', $month);
            if (count($parts) === 2) {
                $mechanicTotalSalary = \App\Models\AccountActivity::where('user_id', $request->user()->id)
                    ->where('type', 'money_in')
                    ->whereYear('created_at', $parts[0])
                    ->whereMonth('created_at', $parts[1])
                    ->sum('amount');
            }
        }

        return view('service-orders.index', [
            'orders' => $query->paginate(10)->withQueryString(),
            'statuses' => ServiceOrder::STATUSES,
            'serviceTypes' => ServiceType::orderBy('name')->get(),
            'mechanicTotalSalary' => $mechanicTotalSalary,
        ]);
    }

    public function create(Booking $booking): View
    {
        $booking->load(['user', 'vehicle', 'serviceTypes', 'serviceOrder']);

        abort_if($booking->serviceOrder, 409, 'Booking ini sudah memiliki detail servis.');

        return view('service-orders.create', [
            'booking' => $booking,
            'spareParts' => SparePart::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->serviceOrder()->exists(), 409, 'Booking ini sudah diproses.');

        $validated = $request->validate([
            'serviced_at' => ['required', 'date'],
            'complaint' => ['nullable', 'string', 'max:1000'],
            'diagnosis' => ['required', 'string', 'max:1500'],
            'parts' => ['nullable', 'array'],
            'parts.*' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $booking, $validated) {
            $booking->loadMissing('serviceTypes.activePromotions');

            $partsTotal = 0;
            $selectedParts = [];

            foreach (($validated['parts'] ?? []) as $partId => $quantity) {
                if ($quantity < 1) {
                    continue;
                }

                $part = SparePart::findOrFail($partId);

                if ($quantity > $part->stock) {
                    abort(422, 'Stok ' . $part->name . ' tidak cukup.');
                }

                $total = $part->price * $quantity;
                $partsTotal += $total;
                $selectedParts[] = compact('part', 'quantity', 'total');
            }

            $laborCost = $booking->serviceTypes->sum(fn (ServiceType $type) => $type->discountedPrice());

            $order = ServiceOrder::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->user_id,
                'mechanic_id' => $request->user()->id,
                'vehicle_id' => $booking->vehicle_id,
                'serviced_at' => $validated['serviced_at'],
                'complaint' => $booking->service_description,
                'diagnosis' => $validated['diagnosis'],
                'labor_cost' => $laborCost,
                'parts_total' => $partsTotal,
                'total_cost' => $laborCost + $partsTotal,
                'status' => 'waiting_cashier',
            ]);

            foreach ($selectedParts as $line) {
                $order->items()->create([
                    'spare_part_id' => $line['part']->id,
                    'description' => $line['part']->name,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['part']->price,
                    'total_price' => $line['total'],
                ]);

                $line['part']->decrement('stock', $line['quantity']);
            }

            // Payment is created later by cashier or we can create it now with pending
            Payment::create([
                'service_order_id' => $order->id,
                'reference_no' => 'PAY-' . now()->format('YmdHis') . '-' . $order->id,
                'amount' => $order->total_cost,
                'status' => 'pending',
            ]);

            $booking->update(['status' => 'completed']);
            
            // Notify Cashiers
            $cashiers = \App\Models\User::where('role', 'cashier')->get();
            foreach ($cashiers as $cashier) {
                \App\Models\Message::create([
                    'user_id' => $cashier->id,
                    'title' => 'Servis Selesai: ' . ($booking->vehicle?->plate_number ?? 'Tanpa Plat'),
                    'body' => 'Mekanik telah menyelesaikan servis untuk booking #' . $booking->id . '. Silakan proses ke tahap pembayaran.',
                ]);
            }
        });

        return redirect()->route('service-orders.index')->with('success', 'Detail servis berhasil disimpan. Menunggu rincian kasir.');
    }

    public function sendToCustomer(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        abort_unless($request->user()->hasAnyRole(['owner', 'cashier']), 403);
        abort_unless($serviceOrder->status === 'waiting_cashier', 403, 'Hanya bisa diproses saat status menunggu kasir.');

        $serviceOrder->update([
            'status' => 'waiting_approval',
        ]);

        return back()->with('success', 'Rincian biaya telah dikirim ke pelanggan.');
    }

    public function show(Request $request, ServiceOrder $serviceOrder): View
    {
        $this->authorizeView($request, $serviceOrder);

        return view('service-orders.show', [
            'order' => $serviceOrder->load(['customer', 'mechanic', 'vehicle', 'booking.serviceTypes', 'items', 'payment.invoice']),
        ]);
    }

    public function approve(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        abort_unless($request->user()->id === $serviceOrder->customer_id, 403);

        $serviceOrder->update([
            'status' => 'approved',
            'customer_approved_at' => now(),
        ]);

        return back()->with('success', 'Rincian biaya disetujui. Silakan lanjutkan pembayaran.');
    }

    public function approveAndPay(Request $request, ServiceOrder $serviceOrder): RedirectResponse
    {
        abort_unless($request->user()->id === $serviceOrder->customer_id, 403);
        abort_unless($serviceOrder->status === 'waiting_approval', 403, 'Rincian biaya sudah diproses.');

        $serviceOrder->update([
            'status' => 'approved',
            'customer_approved_at' => now(),
        ]);

        $payment = $serviceOrder->payment;
        abort_unless($payment, 404, 'Data pembayaran tidak ditemukan.');

        return app(PaymentController::class)->payCustomer(
            $request,
            $payment,
            app(\App\Services\PaymentDistributionService::class)
        );
    }

    private function authorizeView(Request $request, ServiceOrder $order): void
    {
        $user = $request->user();

        abort_unless(
            $user->hasAnyRole(['owner', 'mechanic', 'cashier']) || $order->customer_id === $user->id,
            403
        );
    }
}
