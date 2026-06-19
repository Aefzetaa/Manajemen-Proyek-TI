@extends('layouts.app')

@section('title', 'Invoice')
@section('eyebrow', $invoice->invoice_number)

@section('actions')
    <button class="button secondary no-print" onclick="window.print()">Cetak / Simpan PDF</button>
@endsection

@section('content')
    <section class="invoice">
        <div class="split">
            <div>
                <h2>Invoice Bengkel Motor</h2>
                <p class="muted">{{ $invoice->invoice_number }}</p>
            </div>
            <div style="text-align:right;">
                <strong>{{ $invoice->issued_at->format('d/m/Y H:i') }}</strong><br>
                <span class="muted">{{ $invoice->recipient_channel ?: 'Digital' }}</span>
            </div>
        </div>

        @php($order = $invoice->payment->serviceOrder)
        <div class="grid grid-2" style="margin-top:20px;">
            <div>
                <h3>Pelanggan</h3>
                <p>{{ $order->customer->name }}<br><span class="muted">{{ $order->customer->phone }}</span></p>
            </div>
            <div>
                <h3>Kendaraan</h3>
                <p>{{ $order->vehicle?->plate_number }}<br><span class="muted">{{ $order->vehicle?->brand }} {{ $order->vehicle?->model }}</span></p>
            </div>
        </div>

        <div class="table-wrap" style="margin-top:18px;">
            <table>
                <thead><tr><th>Item</th><th>Qty</th><th>Harga</th><th>Total</th></tr></thead>
                <tbody>
                    <tr>
                        <td>Biaya jasa</td>
                        <td>1</td>
                        <td>Rp {{ number_format($order->labor_cost, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($order->labor_cost, 0, ',', '.') }}</td>
                    </tr>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>Total Dibayar</strong></td>
                        <td><strong>Rp {{ number_format($invoice->payment->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
