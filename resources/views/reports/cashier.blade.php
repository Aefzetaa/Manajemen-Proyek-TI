@extends('layouts.app')

@section('title', 'Kasir')
@section('eyebrow', 'Ringkasan transaksi kasir')

@section('actions')
    <a class="button secondary no-print" href="{{ route('reports.cashier', ['date' => $date, 'export' => 'csv']) }}">Export CSV</a>
    <button class="button secondary no-print" onclick="window.print()">Export PDF</button>
@endsection

@section('content')
    {{-- Auto-filter by date --}}
    <section class="panel" style="margin-bottom:16px;">
        <form method="GET" id="reportForm" class="toolbar">
            <label style="font-size:13px; color:var(--muted);">Tanggal:</label>
            <input type="date" name="date" value="{{ $date }}" onchange="document.getElementById('reportForm').submit()" style="max-width:200px;">
        </form>
    </section>

    {{-- Ringkasan Hari Ini --}}
    <div class="grid grid-2" style="margin-bottom:16px;">
        <section class="panel metric-card">
            <div class="metric-label">Total Transaksi</div>
            <div class="metric">{{ $totalCount }}</div>
            <div class="metric-note">Pembayaran lunas pada {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</div>
        </section>
        <section class="panel metric-card">
            <div class="metric-label">Total Pendapatan</div>
            <div class="metric" style="font-size:22px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="metric-note">Omzet lunas pada {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</div>
        </section>
    </div>

    {{-- Tabel Transaksi --}}
    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Referensi</th><th>Pelanggan</th><th>Kendaraan</th><th>Metode</th><th>Kasir</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->reference_no }}<br><span class="muted">{{ $payment->paid_at?->format('H:i') }}</span></td>
                            <td>{{ $payment->serviceOrder->customer->name }}</td>
                            <td>{{ $payment->serviceOrder->vehicle?->plate_number ?? '-' }}<br><span class="muted">{{ $payment->serviceOrder->serviceType?->name ?? '-' }}</span></td>
                            <td>{{ $payment->method ?? '-' }}</td>
                            <td>{{ $payment->cashier?->name ?? 'ZeroPay / Otomatis' }}</td>
                            <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted" style="text-align:center; padding:30px;">Belum ada transaksi lunas pada tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
