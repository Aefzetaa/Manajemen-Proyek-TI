@extends('layouts.app')

@section('title', 'Penarikan')
@section('eyebrow', 'Kelola penarikan saldo')

@section('content')

@if(! auth()->user()->withdraw_pin)
<div style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:10px; padding:12px 18px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
    <span style="font-size:20px;">🛡️</span>
    <div style="font-size:14px; color:#fca5a5;">
        <strong>Keamanan Akun:</strong> PIN penarikan Anda masih menggunakan PIN default bawaan "1234". Demi keamanan dana Anda, harap segera mengganti PIN Anda.
        <a href="{{ route('account.edit') }}" style="color:#60a5fa; font-weight:700; text-decoration:none; margin-left:6px; border-bottom:1px dashed #60a5fa;">Ganti PIN Sekarang →</a>
    </div>
</div>
@endif

{{-- MODUL KHUSUS KASIR: Tarik fee servis langsung ke ZeroPay --}}
@if(auth()->user()->isRole('cashier'))
<div class="grid grid-2" style="margin-bottom:24px;">
    <div class="stack">
        <section class="panel" style="border-color:var(--primary-soft);">
            <h2>Tarik Fee Servis ke Saldo ZeroPay</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">
                Tarik akumulasi fee komisi dari pekerjaan kasir Anda langsung ke saldo ZeroPay tanpa perlu logout.
            </p>
            <form method="POST" action="{{ route('withdraw.store') }}" class="stack" id="cashierFeeForm">
                @csrf
                <input type="hidden" name="method" value="zeropay">
                <input type="hidden" name="pin" id="feePinHidden" value="">

                <div class="field">
                    <label for="fee_amount">Nominal Penarikan (Rp)</label>
                    <input type="number" id="fee_amount" name="amount" min="1000" max="{{ auth()->user()->withdrawableFees() }}" placeholder="Contoh: 15000" required>
                    <span style="font-size:11px; color:var(--muted); margin-top:4px;">*Penarikan bebas berapapun, minimal Rp 1.000.</span>
                </div>

                <div style="margin-top:10px;">
                    <button class="button" type="button" id="openFeePinModalBtn" style="background:linear-gradient(135deg, var(--primary), var(--primary-dark)); border:none;">Tarik Fee ke ZeroPay</button>
                </div>
            </form>
        </section>
    </div>

    <div>
        <section class="panel">
            <h2 style="margin:0;">Rincian Gaji Kasir</h2>
            <div style="margin-top:20px; display:grid; gap:16px;">
                <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--line); padding-bottom:8px;">
                    <span class="muted">Total Gaji Belum Diambil:</span>
                    <strong>Rp {{ number_format(auth()->user()->unclaimed_salary, 0, ',', '.') }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--line); padding-bottom:8px;">
                    <span class="muted">Gaji Harian Kasir (Ditahan):</span>
                    <strong style="color:var(--danger);">Rp 50.000</strong>
                </div>
                <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--line); padding-bottom:8px;">
                    <span class="muted">Fee Servis yang Bisa Ditarik:</span>
                    <strong style="color:var(--ok); font-size:16px;">Rp {{ number_format(auth()->user()->withdrawableFees(), 0, ',', '.') }}</strong>
                </div>
                <small class="muted">*Catatan: Gaji harian Rp 50.000 ditahan dan hanya dapat dicairkan secara otomatis saat Anda keluar/logout dari sistem.</small>
            </div>
        </section>
    </div>
</div>
@endif

<div class="grid grid-2">
    <div class="stack">
        <section class="panel">
            <h2>Pilih Nominal & Tujuan (E-Wallet/Bank)</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">Tarik saldo ZeroPay Anda ke rekening bank atau e-wallet eksternal.</p>
            <form method="POST" action="{{ route('withdraw.store') }}" class="stack" id="withdrawForm">
                @csrf
                <input type="hidden" name="pin" id="withdrawPinHidden" value="">

                <div class="field">
                    <label for="amount">Nominal Tarik Tunai</label>
                    <select id="amount" name="amount" required>
                        <option value="">-- Pilih Nominal --</option>
                        <option value="50000" @selected(old('amount') == '50000')>Rp 50.000</option>
                        <option value="100000" @selected(old('amount') == '100000')>Rp 100.000</option>
                        <option value="250000" @selected(old('amount') == '250000')>Rp 250.000</option>
                        <option value="500000" @selected(old('amount') == '500000')>Rp 500.000</option>
                        <option value="1000000" @selected(old('amount') == '1000000')>Rp 1.000.000</option>
                    </select>
                    @error('amount')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="method">Tujuan E-Wallet / Rekening</label>
                    <select id="method" name="method" required>
                        <option value="">-- Pilih Tujuan --</option>
                        <option value="gopay" @selected(old('method') == 'gopay')>GoPay</option>
                        <option value="ovo" @selected(old('method') == 'ovo')>OVO</option>
                        <option value="dana" @selected(old('method') == 'dana')>DANA</option>
                        <option value="bca" @selected(old('method') == 'bca')>Bank BCA</option>
                        <option value="mandiri" @selected(old('method') == 'mandiri')>Bank Mandiri</option>
                    </select>
                </div>

                <div style="margin-top:10px;">
                    <button class="button" type="button" id="openPinModalBtn">Konfirmasi Penarikan</button>
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

<div id="pinVerifyModal" class="modal-overlay hide" style="position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; display:none; align-items:center; justify-content:center; padding:20px;">
    <div class="panel modal-card" style="background:var(--panel-solid); max-width:380px; width:100%; padding:28px 24px; text-align:center; border:1px solid var(--line); box-shadow:var(--shadow);">
        <div style="width:56px; height:56px; margin:0 auto 16px; border-radius:14px; background:linear-gradient(135deg, var(--primary), var(--accent)); display:grid; place-items:center; color:#fff; font-size:26px;">🔒</div>
        <h3 style="margin:0 0 8px;">Verifikasi PIN Anda</h3>
        <p class="muted" style="font-size:13px; margin-bottom:20px;">Masukkan PIN keamanan 4 digit untuk melanjutkan penarikan dana.</p>

        <input type="password" id="pinModalInput" inputmode="numeric" maxlength="4" placeholder="••••" autocomplete="off"
               style="letter-spacing:8px; text-align:center; font-weight:bold; font-size:22px; max-width:160px; margin:0 auto 12px; display:block;">

        <div id="pinModalError" style="color:var(--danger); font-size:12px; margin-bottom:12px; min-height:18px;">
            @error('pin'){{ $message }}@enderror
        </div>

        <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
            <button type="button" class="button secondary" id="closePinModalBtn">Batal</button>
            <button type="button" class="button" id="confirmPinBtn">Verifikasi & Tarik</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const form = document.getElementById('withdrawForm');
    const cashierFeeForm = document.getElementById('cashierFeeForm');
    const modal = document.getElementById('pinVerifyModal');
    const openBtn = document.getElementById('openPinModalBtn');
    const openFeeBtn = document.getElementById('openFeePinModalBtn');
    const closeBtn = document.getElementById('closePinModalBtn');
    const confirmBtn = document.getElementById('confirmPinBtn');
    const pinInput = document.getElementById('pinModalInput');
    const pinError = document.getElementById('pinModalError');

    let activeForm = form;

    function openModal() {
        if (activeForm === form && (!form.amount.value || !form.method.value)) {
            form.reportValidity();
            return;
        }
        if (activeForm === cashierFeeForm && !cashierFeeForm.amount.value) {
            cashierFeeForm.reportValidity();
            return;
        }
        pinInput.value = '';
        pinError.textContent = '';
        modal.style.display = 'flex';
        modal.classList.remove('hide');
        setTimeout(() => pinInput.focus(), 200);
    }

    function closeModal() {
        modal.style.display = 'none';
        modal.classList.add('hide');
    }

    openBtn.addEventListener('click', () => {
        activeForm = form;
        openModal();
    });

    if (openFeeBtn) {
        openFeeBtn.addEventListener('click', () => {
            activeForm = cashierFeeForm;
            openModal();
        });
    }

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    confirmBtn.addEventListener('click', () => {
        const pin = pinInput.value.trim();
        if (pin.length !== 4) {
            pinError.textContent = 'PIN harus 4 digit.';
            pinInput.focus();
            return;
        }
        activeForm.querySelector('input[name="pin"]').value = pin;
        activeForm.submit();
    });

    pinInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            confirmBtn.click();
        }
    });

    @if($errors->has('pin'))
        openModal();
    @endif
})();
</script>
@endsection
