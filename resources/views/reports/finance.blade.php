@extends('layouts.app')

@section('title', 'Keuangan')
@section('eyebrow', 'Pendapatan dan jumlah transaksi')

@section('actions')
    <button class="button secondary no-print" onclick="window.print()">Export PDF</button>
    <a class="button secondary no-print" href="{{ route('reports.finance', ['date' => $date, 'export' => 'csv']) }}">Export Excel</a>
@endsection

@section('content')
    <section class="panel">
        <form method="GET" class="toolbar" style="margin-bottom:14px;">
            <input type="date" name="date" value="{{ $date }}">
            <button class="button secondary" type="submit">Tampilkan</button>
        </form>
        <div class="grid grid-2" style="margin-bottom:14px;">
            <div style="border:1px solid var(--line); border-radius:8px; padding:14px;">
                <div class="muted">Total Pendapatan</div>
                <div class="metric">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div style="border:1px solid var(--line); border-radius:8px; padding:14px;">
                <div class="muted">Jumlah Transaksi</div>
                <div class="metric">{{ $payments->count() }}</div>
            </div>
        </div>



        @if($methodTotals->isNotEmpty())
            @php($maxMethod = max($methodTotals->max(), 1))
            <div style="margin-bottom:24px;">
                <h2>Grafik Metode Pembayaran</h2>
                @foreach($methodTotals as $method => $amount)
                    <div class="bar-row">
                        <strong>{{ $method }}</strong>
                        <div class="bar"><span style="width: {{ ($amount / $maxMethod) * 100 }}%;"></span></div>
                        <span>Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="table-wrap">
            <table>
                <thead><tr><th>Waktu</th><th>Pelanggan</th><th>Kendaraan</th><th>Metode</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->paid_at?->format('H:i') }}</td>
                            <td>{{ $payment->serviceOrder->customer->name }}</td>
                            <td>{{ $payment->serviceOrder->vehicle?->plate_number }}</td>
                            <td>{{ $payment->method }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">Belum ada transaksi lunas pada tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@section('scripts')
@endsection

@endsection
