<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $baseBookings = Booking::with(['user', 'vehicle', 'serviceTypes'])->latest();
        $baseOrders = ServiceOrder::with(['customer', 'vehicle', 'booking.serviceTypes', 'payment'])->latest();

        if ($user->isRole('customer')) {
            $baseBookings->where('user_id', $user->id);
            $baseOrders->where('customer_id', $user->id);
        }

        if ($user->isRole('mechanic')) {
            $baseOrders->where(function ($query) use ($user) {
                $query->whereNull('mechanic_id')->orWhere('mechanic_id', $user->id);
            });
        }

        $paymentQuery = Payment::query();

        if ($user->isRole('customer')) {
            $paymentQuery->whereHas('serviceOrder', fn($query) => $query->where('customer_id', $user->id));
        }

        $pendingPaymentCount = (clone $paymentQuery)->where('status', 'pending')->count();
        $pendingPaymentAmount = (clone $paymentQuery)->where('status', 'pending')->sum('amount');
        
        $paidAmount = (clone $paymentQuery)
            ->where('status', 'paid')
            ->when(! $user->isRole('customer'), fn($query) => $query->whereDate('paid_at', today()))
            ->sum('amount');

        $bookingStatusCounts = Booking::query()
            ->when($user->isRole('customer'), fn($query) => $query->where('user_id', $user->id))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $serviceStatusCounts = ServiceOrder::query()
            ->when($user->isRole('customer'), fn($query) => $query->where('customer_id', $user->id))
            ->when($user->isRole('mechanic'), function ($query) use ($user) {
                $query->where(function ($inner) use ($user) {
                    $inner->whereNull('mechanic_id')->orWhere('mechanic_id', $user->id);
                });
            })
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $cashflow = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($user) {
                $date = today()->subDays($daysAgo);

                $total = Payment::query()
                    ->where('status', 'paid')
                    ->whereDate('paid_at', $date)
                    ->when($user->isRole('customer'), function ($query) use ($user) {
                        $query->whereHas('serviceOrder', fn($order) => $order->where('customer_id', $user->id));
                    })
                    ->sum('amount');

                return [
                    'label' => $date->format('d/m'),
                    'total' => $total,
                ];
            });

        $scrumStories = [
            ['id' => 'US-01', 'label' => 'Registrasi pelanggan', 'epic' => 'Manajemen Pengguna', 'status' => 'Selesai'],
            ['id' => 'US-03', 'label' => 'Booking servis', 'epic' => 'Manajemen Pengguna', 'status' => 'Selesai'],
            ['id' => 'US-05', 'label' => 'Input detail servis', 'epic' => 'Proses Transaksi', 'status' => 'Selesai'],
            ['id' => 'US-07', 'label' => 'Konfirmasi pembayaran', 'epic' => 'Proses Transaksi', 'status' => 'Selesai'],
            ['id' => 'US-11', 'label' => 'Laporan keuangan', 'epic' => 'Laporan & Analitik', 'status' => 'Berjalan'],
        ];

        $customerActiveBookingsCount = 0;
        $customerActiveServiceCount = 0;
        $customerInServiceOrders = collect();
        $unreadMessageCount = 0;

        if ($user->isRole('customer')) {
            $customerActiveBookingsCount = Booking::where('user_id', $user->id)
                ->whereIn('status', ['scheduled', 'accepted', 'in_progress'])
                ->count();

            // Servis berjalan = booking sedang dikerjakan mekanik (in_progress), hilang setelah rincian dikirim ke kasir
            $customerActiveServiceCount = Booking::where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->count();

            $customerInServiceOrders = Booking::with(['vehicle', 'serviceTypes'])
                ->where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->get();

            $unreadMessageCount = $user->messages()
                ->where('is_read', false)
                ->count();
        }
            
        // Mechanic specific metrics
        $mechanicAntreanCount = 0;
        $mechanicDikerjakanCount = 0;
        $mechanicSelesaiCount = 0;
        $mechanicDailyHistory = collect();
        
        if ($user->isRole('mechanic')) {
            $mechanicAntreanCount = Booking::where('mechanic_id', $user->id)->where('status', 'accepted')->count();
            
            $mechanicDikerjakanCount = Booking::where('mechanic_id', $user->id)
                ->where('status', 'in_progress')
                ->count();

            // Selesai = rincian servis sudah dikirim ke kasir (order dibuat) hari ini
            $mechanicSelesaiCount = ServiceOrder::where('mechanic_id', $user->id)
                ->whereDate('created_at', today())
                ->count();
                
            $mechanicDailyHistory = Booking::with(['user', 'vehicle', 'serviceTypes', 'serviceOrder'])
                ->where('mechanic_id', $user->id)
                ->whereDate('updated_at', today())
                ->get()
                ->sortBy(fn($b) => $b->status === 'completed' ? 1 : 0);
        }

        // Owner specific metrics
        $ownerTotalBooking = 0;
        $ownerCanceledBookingThisMonth = 0;
        $ownerNetIncomeToday = 0;
        
        if ($user->isRole('owner')) {
            $ownerTotalBooking = Booking::whereIn('status', ['scheduled', 'accepted', 'in_progress'])->count();
            $ownerCanceledBookingThisMonth = Booking::where('status', 'canceled')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count();
                
            $todaysPayments = Payment::with('serviceOrder')->where('status', 'paid')->whereDate('paid_at', today())->get();
            $ownerNetIncomeToday = $todaysPayments->sum(function (Payment $payment) {
                $order = $payment->serviceOrder;
                if (! $order) {
                    return 0;
                }

                return $order->laborShareBreakdown()['owner_total'];
            });
        }
        
        $cashierFinishedTodayCount = 0;
        $cashierIncomeToday = 0;
        if ($user->isRole('cashier')) {
            $cashierFinishedTodayCount = ServiceOrder::where('status', 'finished')
                ->whereDate('updated_at', today())
                ->count();
            
            $cashierIncomeToday = \App\Models\AccountActivity::where('user_id', $user->id)
                ->where('type', 'money_in')
                ->whereDate('created_at', today())
                ->sum('amount');
        }

        return view('dashboard', [
            'bookingCount' => $user->isRole('customer') ? $customerActiveBookingsCount : (clone $baseBookings)->count(),
            'serviceCount' => $user->isRole('customer') ? $customerActiveServiceCount : (clone $baseOrders)->count(),
            'pendingPaymentCount' => $pendingPaymentCount,
            'pendingPaymentAmount' => $pendingPaymentAmount,
            'paidAmount' => $paidAmount,
            'bookings' => $baseBookings->take(6)->get(),
            'orders' => $baseOrders->take(6)->get(),
            'customerInServiceOrders' => $customerInServiceOrders,
            'unreadMessageCount' => $unreadMessageCount,
            'bookingStatuses' => Booking::STATUSES,
            'serviceStatuses' => ServiceOrder::STATUSES,
            'bookingStatusCounts' => $bookingStatusCounts,
            'serviceStatusCounts' => $serviceStatusCounts,
            'cashflow' => $cashflow,
            'maxCashflow' => max($cashflow->max('total') ?? 0, 1),
            'serviceTypeCount' => ServiceType::count(),
            'scrumStories' => $scrumStories,
            
            'mechanicAntreanCount' => $mechanicAntreanCount ?? 0,
            'mechanicDikerjakanCount' => $mechanicDikerjakanCount ?? 0,
            'mechanicSelesaiCount' => $mechanicSelesaiCount ?? 0,
            'mechanicDailyHistory' => $mechanicDailyHistory ?? collect(),
            
            'ownerTotalBooking' => $ownerTotalBooking ?? 0,
            'ownerCanceledBookingThisMonth' => $ownerCanceledBookingThisMonth ?? 0,
            'ownerNetIncomeToday' => $ownerNetIncomeToday ?? 0,
            'cashierFinishedTodayCount' => $cashierFinishedTodayCount ?? 0,
            'cashierIncomeToday' => $cashierIncomeToday ?? 0,
        ]);
    }
}
