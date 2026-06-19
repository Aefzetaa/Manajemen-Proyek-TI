@extends('layouts.app')

@section('title', 'Pembayaran')
@section('eyebrow', auth()->user()->isRole('customer') ? 'Tagihan servis Anda' : 'Kelola pembayaran pelanggan')

@section('content')

{{-- ===== CASHIER VIEW ===== --}}
@if(auth()->user()->isRole('cashier'))
    {{-- Filter Jenis Servis (auto-submit) --}}
    <section class="panel" style="margin-bottom:16px;">
        <form method="GET" id="filterForm" class="toolbar">
            <label style="font-size:13px; color:var(--muted);">Filter Jenis:</label>
            <select name="service_type" onchange="document.getElementById('filterForm').submit()" style="max-width:200px;">
                <option value="">Semua Jenis</option>
                <option value="Matic"   @selected(request('service_type') === 'Matic')>Matic</option>
                <option value="Manual"  @selected(request('service_type') === 'Manual')>Manual</option>
                <option value="Kopling" @selected(request('service_type') === 'Kopling')>Kopling</option>
            </select>
            @if(request('service_type'))
                <a class="button secondary small" href="{{ route('payments.index') }}">Reset</a>
            @endif
        </form>
    </section>

    {{-- List Pembayaran Tunai Pending --}}
    <div class="stack">
        @forelse($payments as $payment)
            @php $order = $payment->serviceOrder; @endphp
            <div class="panel" style="padding:20px;" id="card-{{ $payment->id }}">
                <div style="display:grid; grid-template-columns:1fr auto; gap:16px; align-items:flex-start;">
                    {{-- Info Servis --}}
                    <div>
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px; flex-wrap:wrap;">
                            <strong style="font-size:16px;">{{ $order->customer->name }}</strong>
                            <span class="status {{ $order->status }}">{{ $order->statusLabel() }}</span>
                            <span class="muted" style="font-size:12px;">{{ $payment->reference_no }}</span>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(3,auto); gap:8px 24px; font-size:13px;">
                            <div><span class="muted">Kendaraan:</span> <strong>{{ $order->vehicle?->plate_number ?? '-' }}</strong></div>
                            <div><span class="muted">Jenis Servis:</span> <strong>{{ $order->serviceType?->name ?? '-' }}</strong></div>
                            <div><span class="muted">Mekanik:</span> <strong>{{ $order->mechanic?->name ?? '-' }}</strong></div>
                            <div><span class="muted">Ongkos Jasa:</span> <strong>Rp {{ number_format($order->labor_cost, 0, ',', '.') }}</strong></div>
                            <div><span class="muted">Sparepart:</span> <strong>Rp {{ number_format($order->parts_total, 0, ',', '.') }}</strong></div>
                            <div><span class="muted">Tanggal Servis:</span> <strong>{{ $order->serviced_at->format('d/m/Y') }}</strong></div>
                        </div>
                        @if($order->diagnosis)
                            <div style="margin-top:8px; font-size:13px; color:var(--muted);">Diagnosis: {{ Str::limit($order->diagnosis, 80) }}</div>
                        @endif
                    </div>

                    {{-- Total + Tombol --}}
                    <div style="text-align:right;">
                        <div style="font-size:12px; color:var(--muted); margin-bottom:2px;">Total Tagihan</div>
                        <div style="font-size:26px; font-weight:900; color:var(--primary);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        
                        @if($order->status === 'waiting_cashier')
                            <form method="POST" action="{{ route('service-orders.send-to-customer', $order) }}" style="margin-top:10px;">
                                @csrf
                                @method('PATCH')
                                <button class="button small" type="submit">Kirim ke Pelanggan</button>
                            </form>
                        @else
                            <button class="button small" style="margin-top:10px;" onclick="toggleForm('form-{{ $payment->id }}', this)">
                                Lunas
                            </button>
                        @endif
                    </div>
                </div>

                @if($order->status !== 'waiting_cashier')
                {{-- Form Konfirmasi (tersembunyi, muncul saat diklik) --}}
                <div id="form-{{ $payment->id }}" style="display:none; margin-top:18px; border-top:1px solid var(--line); padding-top:18px;">
                    <form method="POST" action="{{ route('payments.paid', $payment) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="method" value="Tunai">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                            <div class="field">
                                <label>Uang Diterima (Rp)</label>
                                <input type="number" name="cash_received" placeholder="Masukkan jumlah uang..."
                                    min="{{ $payment->amount }}" required
                                    oninput="calcChange(this, {{ $payment->amount }}, 'kembalian-{{ $payment->id }}')"
                                    style="font-size:15px; font-weight:700;">
                            </div>
                            <div class="field">
                                <label>Kembalian</label>
                                <div id="kembalian-{{ $payment->id }}" style="padding:10px 11px; border-radius:8px; border:1px solid var(--line); background:var(--bg); font-size:15px; font-weight:800; color:var(--muted); min-height:42px; display:flex; align-items:center;">
                                    
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <button class="button" type="submit">Lunas</button>
                            <button type="button" class="button secondary" onclick="toggleForm('form-{{ $payment->id }}', null)">Batal</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        @empty
            <div class="panel" style="text-align:center; padding:50px; color:var(--muted);">
                <strong style="font-size:18px;">Tidak ada pembayaran tunai yang menunggu</strong>
                <p style="font-size:14px; margin-top:6px;">Semua tagihan sudah lunas atau pelanggan memilih ZeroPay.</p>
            </div>
        @endforelse
    </div>

    @if($payments->hasPages())
        <div class="pagination" style="margin-top:14px;">{{ $payments->links() }}</div>
    @endif

{{-- ===== CUSTOMER VIEW ===== --}}
@else
    <section class="panel">
        @unless(auth()->user()->isRole('customer'))
            <form method="GET" class="toolbar" style="margin-bottom:14px;">
                <input type="date" name="date" value="{{ request('date') }}">
                <select name="status">
                    <option value="">Semua status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="button secondary" type="submit">Filter</button>
                <a class="button secondary" href="{{ route('payments.index') }}">Reset</a>
            </form>
        @endunless

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Referensi</th>
                        @unless(auth()->user()->isRole('customer'))
                            <th>Pelanggan</th>
                        @endunless
                        <th>Kendaraan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->reference_no }}<br><span class="muted">{{ $payment->created_at->format('d/m/Y H:i') }}</span></td>
                            @unless(auth()->user()->isRole('customer'))
                                <td>{{ $payment->serviceOrder->customer->name }}</td>
                            @endunless
                            <td>{{ $payment->serviceOrder->vehicle?->plate_number }}<br><span class="muted">{{ $payment->serviceOrder->serviceType?->name }}</span></td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td><span class="status {{ $payment->status }}">{{ $payment->statusLabel() }}</span></td>
                            <td>
                                @if($payment->status === 'pending')
                                    @if($payment->serviceOrder->status === 'waiting_cashier')
                                        <span class="muted">Menunggu kasir mengirim rincian biaya</span>
                                    @elseif($payment->serviceOrder->status === 'waiting_approval')
                                        @if(auth()->user()->isRole('customer'))
                                            <a class="button small" href="{{ route('service-orders.show', $payment->serviceOrder) }}">Setujui &amp; Bayar</a>
                                        @else
                                            <span class="muted">Menunggu persetujuan pelanggan</span>
                                        @endif
                                    @elseif(auth()->user()->isRole('customer') && $payment->method === 'Tunai')
                                        <span class="muted">Menunggu kasir (Tunai)</span>
                                    @endif
                                @elseif($payment->invoice)
                                    <a class="button secondary small" href="{{ route('payments.invoice', $payment->invoice) }}">Invoice</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="muted">Belum ada pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $payments->links() }}</div>
    </section>
@endif

@endsection

@section('scripts')
<script>
    function toggleForm(formId, btn) {
        const form = document.getElementById(formId);
        const isHidden = form.style.display === 'none';
        form.style.display = isHidden ? 'block' : 'none';
        if (btn) btn.textContent = isHidden ? 'Tutup' : 'Konfirmasi Pembayaran';
    }

    function calcChange(input, total, elId) {
        const received = parseInt(input.value) || 0;
        const el = document.getElementById(elId);
        if (received >= total) {
            const change = received - total;
            el.textContent = 'Rp ' + change.toLocaleString('id-ID');
            el.style.color = 'var(--ok)';
            el.style.borderColor = 'var(--ok)';
        } else if (received > 0) {
            el.textContent = 'Kurang Rp ' + (total - received).toLocaleString('id-ID');
            el.style.color = 'var(--danger)';
            el.style.borderColor = 'var(--danger)';
        } else {
            el.textContent = '';
            el.style.color = 'var(--muted)';
            el.style.borderColor = 'var(--line)';
        }
    }

    function handleCustomerPayment(e, form) {
        const method = form.querySelector('select[name="method"]').value;
        if (method === 'ZeroPay') {
            e.preventDefault();
            if (window.runZeroPaySimulation) {
                window.runZeroPaySimulation({
                    form,
                    title: 'Memproses Pembayaran',
                    subtitle: 'Tagihan servis sedang diproses melalui ZeroPay.',
                    status: 'Validasi saldo pelanggan...',
                    duration: 1400
                });
            } else {
                form.submit();
            }
        } else if (method === 'Tunai') {
            // Use modal confirm; prevent default then submit if user confirms
            e.preventDefault();
            window.showConfirm('Konfirmasi Pembayaran', 'Pilih metode pembayaran tunai?').then(ok => {
                if (ok) form.submit();
            });
        }
    }
</script>

@if(session('show_nota_cashier'))
    <div id="notaOverlay" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:99999; cursor:pointer;" onclick="clickNota()">
        <div style="background:#fff; color:#000; padding:40px; border-radius:8px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5); user-select:none; font-family:monospace;">
            <h1 style="margin-bottom:10px; border-bottom:2px dashed #ccc; padding-bottom:10px;">NOTA SERVIS</h1>
            <p>Terima kasih telah membayar tunai.</p>
            <div style="margin:20px 0; font-size:40px;">NOTA</div>
            <p style="font-size:12px; color:#666;">Ketuk nota 3 kali untuk mencatat pemberian ke pelanggan.</p>
            <div id="clickCounter" style="margin-top:15px; font-weight:bold; font-size:18px; color:var(--primary);">Klik: 0/3</div>
        </div>
    </div>
    
    <script>
        let notaClicks = 0;
        function clickNota() {
            notaClicks++;
            document.getElementById('clickCounter').innerText = `Klik: ${notaClicks}/3`;
            
            if (notaClicks >= 3) {
                document.getElementById('notaOverlay').style.display = 'none';
                
                // Hit AJAX to give nota
                fetch("{{ route('payments.give-nota', session('show_nota_cashier')) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
@endif

@if(session('show_zeropay_receipt'))
    <div id="zeropayReceiptOverlay" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:99999;">
        <div style="background:#fff; color:#000; padding:40px; border-radius:8px; max-width:400px; width:100%; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.5); font-family:monospace; position:relative; overflow:hidden;">
            <div style="position:absolute; top:20%; left:50%; transform:translate(-50%, -50%) rotate(-15deg); font-size:60px; color:rgba(46,204,113,0.3); font-weight:900; border:6px solid rgba(46,204,113,0.3); padding:10px 30px; border-radius:15px; pointer-events:none;">LUNAS</div>
            <h2 style="margin-bottom:10px; border-bottom:2px dashed #ccc; padding-bottom:10px;">RECEIPT ZEROPAY</h2>
            <p style="font-weight:bold; font-size:18px;">Pembayaran Berhasil!</p>
            <p style="font-size:14px; margin-top:10px;">Terima kasih telah menggunakan ZeroPay.</p>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const overlay = document.getElementById('zeropayReceiptOverlay');
            if (overlay) overlay.style.display = 'none';
        }, 3000);
    </script>
@endif
@endsection


