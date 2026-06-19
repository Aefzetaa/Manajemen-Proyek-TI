@extends('layouts.app')

@section('title', 'Dashboard')
@section('eyebrow', auth()->user()->isRole('customer') ? 'Area pelanggan Milky Garage' : (auth()->user()->isRole('mechanic') ? 'Area kerja mekanik' : (auth()->user()->isRole('cashier') ? 'Area kerja kasir' : 'Ringkasan operasional hari ini')))

@section('actions')
    @if(auth()->user()->isRole('customer'))
        <a class="button secondary" href="{{ route('messages.index') }}" style="position:relative; display:inline-flex; align-items:center; gap:6px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            Pesan
            @if($unreadMessageCount > 0)
                <span style="position:absolute; top:-6px; right:-6px; background:var(--danger); color:#fff; font-size:10px; font-weight:800; padding:2px 6px; border-radius:10px; line-height:1;">{{ $unreadMessageCount }}</span>
            @endif
        </a>
    @endif
@endsection

@section('content')

{{-- ========================================================= --}}
{{-- CUSTOMER DASHBOARD --}}
{{-- ========================================================= --}}
@if(auth()->user()->isRole('customer'))
    <div class="grid grid-3">
        <section class="panel metric-card">
            <div class="metric-label">Booking</div>
            <div class="metric">{{ $bookingCount }}</div>
            <div class="metric-note">Booking yang sedang dilakukan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Service</div>
            <div class="metric">{{ $serviceCount }}</div>
            <div class="metric-note">Unit yang sedang di service</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Total</div>
            <div class="metric">Rp {{ number_format($pendingPaymentAmount, 0, ',', '.') }}</div>
            <div class="metric-note">Total Pembayaran</div>
        </section>
    </div>

    @if($customerInServiceOrders->count() > 0)
        <section class="panel" style="margin-top:16px;">
            <div class="split">
                <h2>Service yang sedang dikerjakan</h2>
                <a class="button secondary small" href="{{ route('service-orders.index') }}">Lihat Detail</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Kendaraan</th><th>Layanan</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($customerInServiceOrders as $booking)
                            <tr>
                                <td>{{ $booking->vehicle?->plate_number ?? '-' }}</td>
                                <td>{{ $booking->serviceTypes->pluck('name')->join(', ') ?: '-' }}</td>
                                <td><span class="status {{ $booking->status }}">{{ $booking->statusLabel() }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <section class="panel" style="margin-top:16px;">
            <div class="split">
                <h2>Jadwal Booking Terbaru Anda</h2>
                <a class="button secondary small" href="{{ route('bookings.index') }}">Lihat Semua</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Kendaraan</th><th>Jadwal</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($bookings->whereIn('status', ['scheduled', 'accepted', 'in_progress']) as $booking)
                            <tr>
                                <td>{{ $booking->vehicle?->plate_number ?? '-' }}</td>
                                <td>{{ $booking->booking_date->format('d/m/Y') }} {{ substr($booking->booking_time, 0, 5) }}</td>
                                <td><span class="status {{ $booking->status }}">{{ $booking->statusLabel() }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">Belum ada booking aktif saat ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

{{-- ========================================================= --}}
{{-- MECHANIC DASHBOARD --}}
{{-- ========================================================= --}}
@elseif(auth()->user()->isRole('mechanic'))
    <div class="grid grid-3">
        <section class="panel metric-card">
            <div class="metric-label">Antrean</div>
            <div class="metric">{{ $mechanicAntreanCount ?? 0 }}</div>
            <div class="metric-note">unit yang akan dikerjakan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Dikerjakan</div>
            <div class="metric">{{ $mechanicDikerjakanCount ?? 0 }}</div>
            <div class="metric-note">sedang proses pengerjaan</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Selesai</div>
            <div class="metric">{{ $mechanicSelesaiCount ?? 0 }}</div>
            <div class="metric-note">Unit Beres</div>
        </section>
    </div>
    
    <section class="panel" style="margin-top:16px;">
        <div class="split">
            <h2>Pekerjaan Terbaru Anda</h2>
            <a class="button secondary small" href="{{ route('service-orders.index') }}">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelanggan</th><th>Kendaraan</th><th>Jenis Service</th><th>Jam Booking</th><th>Gaji (Rp)</th><th>Status Pekerjaan</th></tr></thead>
                <tbody>
                    @forelse($mechanicDailyHistory as $booking)
                        @php
                            $mechanicShare = $booking->serviceOrder
                                ? $booking->serviceOrder->laborShareBreakdown()['mechanic']
                                : 0;
                        @endphp
                        <tr>
                            <td>{{ $booking->user->username }}</td>
                            <td>{{ $booking->vehicle?->plate_number ?? '-' }}</td>
                            <td>{{ $booking->serviceTypes->pluck('name')->join(', ') }}</td>
                            <td>{{ substr($booking->booking_time, 0, 5) }}</td>
                            <td>Rp {{ number_format($mechanicShare, 0, ',', '.') }}</td>
                            <td><span class="status {{ $booking->status }}">{{ $booking->statusLabel() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted">Belum ada pekerjaan servis hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

{{-- ========================================================= --}}
{{-- CASHIER DASHBOARD --}}
{{-- ========================================================= --}}
@elseif(auth()->user()->isRole('cashier'))
    <div class="grid grid-4">
        <section class="panel metric-card">
            <div class="metric-label">Pendapatan Hari Ini</div>
            <div class="metric">Rp {{ number_format($cashierIncomeToday, 0, ',', '.') }}</div>
            <div class="metric-note">Gaji harian + profit servis</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Menunggu Bayar</div>
            <div class="metric">{{ $pendingPaymentCount }}</div>
            <div class="metric-note">Invoice belum dilunasi</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Lunas Hari Ini</div>
            <div class="metric">Rp {{ number_format($paidAmount, 0, ',', '.') }}</div>
            <div class="metric-note">Total transaksi lunas hari ini</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Kendaraan Selesai</div>
            <div class="metric">{{ $cashierFinishedTodayCount }}</div>
            <div class="metric-note">Selesai hari ini</div>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="split">
            <h2>Menunggu Konfirmasi Pembayaran</h2>
            <a class="button secondary small" href="{{ route('payments.index') }}">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelanggan</th><th>Kendaraan</th><th>Total</th><th>Metode</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($orders->filter(fn ($o) => $o->payment?->status === 'pending')->take(6) as $order)
                        <tr>
                            <td>{{ $order->customer->username }}</td>
                            <td>{{ $order->vehicle?->plate_number ?? '-' }}<br><span class="muted">{{ $order->booking?->serviceTypes->pluck('name')->join(', ') ?: '-' }}</span></td>
                            <td>Rp {{ number_format($order->payment?->amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $order->payment?->method ?: 'Belum dipilih' }}</td>
                            <td><span class="status {{ $order->payment?->status }}">{{ $order->payment?->status === 'paid' ? 'Lunas' : 'Pending' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Belum ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

{{-- ========================================================= --}}
{{-- OWNER DASHBOARD --}}
{{-- ========================================================= --}}
@else
    @php
        $totalCashflow7Days = collect($cashflow)->sum('total');
    @endphp
    <div class="grid grid-4">
        <section class="panel metric-card">
            <div class="metric-label">Total Booking</div>
            <div class="metric">{{ $ownerTotalBooking }}</div>
            <div class="metric-note">Jadwal yang belum selesai</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Booking Dibatalkan</div>
            <div class="metric">{{ $ownerCanceledBookingThisMonth }}</div>
            <div class="metric-note">Batal di bulan ini</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Pendapatan Hari Ini</div>
            <div class="metric">Rp {{ number_format($ownerNetIncomeToday, 0, ',', '.') }}</div>
            <div class="metric-note">Omset bersih</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Arus Kas 7 Hari Terakhir</div>
            <div class="metric">Rp {{ number_format($totalCashflow7Days, 0, ',', '.') }}</div>
            <div class="metric-note">Total omset 7 hari terakhir</div>
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <h2>Stock Sparepart</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama Sparepart</th><th>Sisa Stok</th><th>Status</th></tr></thead>
                <tbody>
                    @php
                        $lowStockSpareparts = \App\Models\SparePart::where('stock', '<', 5)->orderBy('stock', 'asc')->get();
                    @endphp
                    @forelse($lowStockSpareparts as $part)
                        <tr>
                            <td>{{ $part->name }}</td>
                            <td><strong>{{ $part->stock }}</strong></td>
                            <td>
                                @if($part->stock == 0)
                                    <span style="color:var(--danger); font-weight:bold;">HABIS</span>
                                @else
                                    <span style="color:var(--accent); font-weight:bold;">KRITIS</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">Semua stok sparepart aman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endif

@section('scripts')
@endsection

@endsection
