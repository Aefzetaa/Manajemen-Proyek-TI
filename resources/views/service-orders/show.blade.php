@extends('layouts.app')

@section('title', 'Detail Servis')
@section('eyebrow', 'Rincian pekerjaan, biaya, dan pembayaran')

@section('actions')
    @if($order->payment?->invoice)
        <a class="button secondary" href="{{ route('payments.invoice', $order->payment->invoice) }}">Lihat Invoice</a>
    @endif
@endsection

@section('content')
    <div class="grid grid-2">
        <section class="panel">
            <h2>Informasi Servis</h2>
            <div class="stack">
                <div><strong>Pelanggan:</strong> {{ $order->customer->name }}</div>
                <div><strong>Kendaraan:</strong> {{ $order->vehicle?->plate_number }} - {{ $order->vehicle?->brand }} {{ $order->vehicle?->model }}</div>
                <div><strong>Jenis Servis:</strong> {{ $order->booking?->serviceTypes->pluck('name')->join(', ') ?: '-' }}</div>
                <div><strong>Mekanik:</strong> {{ $order->mechanic?->name ?? '-' }}</div>
                <div><strong>Status:</strong> <span class="status {{ $order->status }}">{{ $order->statusLabel() }}</span></div>
                <div><strong>Keluhan:</strong><br>{{ $order->complaint }}</div>
                <div><strong>Diagnosis:</strong><br>{{ $order->diagnosis }}</div>
            </div>
        </section>

        <section class="panel">
            <h2>Rincian Biaya</h2>
            <div class="table-wrap">
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
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($order->total_cost, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(auth()->id() === $order->customer_id && $order->status === 'waiting_approval' && $order->payment?->status === 'pending')
                <form method="POST" action="{{ route('service-orders.approve-pay', $order) }}" class="stack" style="margin-top:16px; gap:12px;" id="approvePayForm" onsubmit="return handleApprovePay(event, this);">
                    @csrf
                    @method('PATCH')
                    <p class="muted" style="margin:0; font-size:13px;">Setujui rincian biaya sekaligus pilih metode pembayaran.</p>
                    <label class="field">
                        <span>Metode Pembayaran</span>
                        <select name="method" required style="width:100%;">
                            <option value="">— Pilih metode —</option>
                            <option value="ZeroPay">ZeroPay (Saldo: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }})</option>
                            <option value="Tunai">Tunai di Bengkel</option>
                        </select>
                    </label>
                    <button class="button" type="submit">Setujui &amp; Bayar</button>
                </form>
            @elseif(auth()->id() === $order->customer_id && $order->status === 'approved' && $order->payment?->method === 'Tunai' && $order->payment?->status === 'pending')
                <p class="muted" style="margin-top:14px; font-size:13px;">Pembayaran tunai — silakan serahkan uang ke kasir untuk dikonfirmasi lunas.</p>
            @endif
        </section>
    </div>

    <section class="panel" style="margin-top:16px;">
        <div class="panel-header">
            <div>
                <h2 class="panel-title">Status Pembayaran</h2>
                <p class="muted" style="margin:6px 0 0;">ZeroPay lunas langsung. Tunai: serahkan uang ke kasir setelah memilih metode.</p>
            </div>
            @if($order->payment)
                <span class="status {{ $order->payment->status }}">{{ $order->payment->statusLabel() }}</span>
            @endif
        </div>

        @if($order->payment)
            <div class="grid grid-3">
                <div>
                    <div class="muted">Referensi</div>
                    <strong>{{ $order->payment->reference_no }}</strong>
                </div>
                <div>
                    <div class="muted">Metode Pembayaran</div>
                    <strong>{{ $order->payment->method ?: 'Belum Dipilih' }}</strong>
                </div>
                <div>
                    <div class="muted">Waktu Lunas</div>
                    <strong>{{ $order->payment->paid_at?->format('d/m/Y H:i') ?: '-' }}</strong>
                </div>
            </div>
        @else
            <p class="muted">Belum ada data pembayaran untuk order servis ini.</p>
        @endif
    </section>

    <!-- CUSTOM MODAL CONFIRMATION FOR TUNAI PAYMENT -->
    <div id="tunaiConfirmModal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(8px); display:none; align-items:center; justify-content:center; z-index:99999;">
        <div style="background:var(--panel-solid); border:2px solid var(--primary); padding:32px; border-radius:16px; max-width:440px; width:100%; text-align:center; box-shadow:var(--shadow);">
            <div style="font-size:50px; margin-bottom:16px;">💵</div>
            <h2 style="margin:0 0 12px 0; font-size:20px; font-weight:800; color:var(--ink);">Konfirmasi Pembayaran</h2>
            <p class="muted" style="font-size:14.5px; line-height:1.6; margin-bottom:24px; color:#c5c5c5;">
                Setujui biaya dan pilih pembayaran tunai? Silakan bayar ke kasir di bengkel.
            </p>
            <div style="display:flex; gap:12px; justify-content:center;">
                <button type="button" class="button" id="tunaiConfirmBtn" style="flex:1;">Setuju</button>
                <button type="button" class="button secondary" id="tunaiCancelBtn" style="flex:1;">Batal</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let tunaiConfirmed = false;
    function simulateTopupStylePayment(form) {
        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                form,
                title: 'Memproses Pembayaran',
                subtitle: 'Tagihan servis sedang diproses melalui ZeroPay.',
                status: 'Validasi saldo pelanggan...',
                duration: 1400
            });
            return;
        }

        form.submit();
    }

    function handleApprovePay(e, form) {
        const method = form.querySelector('select[name="method"]').value;
        if (method === 'ZeroPay') {
            e.preventDefault();
            simulateTopupStylePayment(form);
            return false;
        }
        if (method === 'Tunai') {
            if (!tunaiConfirmed) {
                e.preventDefault();
                const modal = document.getElementById('tunaiConfirmModal');
                modal.style.display = 'flex';
                
                document.getElementById('tunaiConfirmBtn').onclick = function() {
                    tunaiConfirmed = true;
                    modal.style.display = 'none';
                    form.submit();
                };
                document.getElementById('tunaiCancelBtn').onclick = function() {
                    modal.style.display = 'none';
                };
                return false;
            }
        }
        return true;
    }
</script>
@endsection
