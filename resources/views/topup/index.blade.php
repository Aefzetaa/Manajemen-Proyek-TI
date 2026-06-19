@extends('layouts.app')

@section('title', 'Top Up')
@section('eyebrow', 'Tambah saldo ZeroPay')

@section('content')
<div class="grid grid-2">
    <div class="stack">
        <section class="panel">
            <h2>Pilih Nominal & Metode</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;"></p>
            <form method="POST" action="{{ route('topup.store') }}" class="stack" id="topupForm">
                @csrf
                
                <div class="field">
                    <label for="amount">Nominal Top-Up</label>
                    <select id="amount" name="amount" required>
                        <option value="">-- Pilih Nominal --</option>
                        <option value="10000">Rp 10.000</option>
                        <option value="25000">Rp 25.000</option>
                        <option value="50000">Rp 50.000</option>
                        <option value="100000">Rp 100.000</option>
                        <option value="500000">Rp 500.000</option>
                        <option value="1000000">Rp 1.000.000</option>
                        <option value="5000000">Rp 5.000.000</option>
                        <option value="10000000">Rp 10.000.000</option>
                    </select>
                    <small class="muted" style="display:block; margin-top:6px;">Maksimal Rp 10.000.000 per transaksi.</small>
                </div>
                
                <div class="field">
                    <label for="method">Metode Pembayaran</label>
                    <select id="method" name="method" required>
                        <option value="">-- Pilih E-Wallet --</option>
                        <option value="gopay">GoPay</option>
                        <option value="ovo">OVO</option>
                        <option value="dana">DANA</option>
                        <option value="shopeepay">ShopeePay</option>
                    </select>
                </div>
                
                <div style="margin-top:10px;">
                    <button class="button" type="submit">Konfirmasi Pembayaran</button>
                </div>
            </form>
        </section>
    </div>
    
    <div>
        <section class="panel">
            <h2 style="margin:0;">Informasi Saldo</h2>
            <div style="margin-top:20px; text-align:center;">
                <p class="muted" style="font-size:14px; margin-bottom:8px;">Saldo ZeroPay Saat Ini:</p>
                <h1 style="font-size:36px; color:var(--primary); margin:0;">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</h1>
            </div>
        </section>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('topupForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (window.runZeroPaySimulation) {
            window.runZeroPaySimulation({
                form: e.target,
                title: 'Memproses Top-up',
                subtitle: 'Top-up ZeroPay sedang diproses melalui kanal pembayaran.',
                status: 'Menunggu respons pembayaran...',
                duration: 1400
            });
            return;
        }

        e.target.submit();
    });
</script>
@endsection
