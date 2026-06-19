@extends('layouts.app')

@section('title', 'Akun')
@section('eyebrow', 'Manajemen Profil')

@section('content')
<div class="grid grid-2">
    <div class="stack">
        <section class="panel">
            <h2>Profil & Keamanan</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">Perbarui informasi pribadi dan password akun Anda.</p>
            
            <form method="POST" action="{{ route('account.update') }}" class="stack">
                @csrf
                @method('PATCH')
                
                <div class="field">
                    <label>Role / Peran</label>
                    <input type="text" value="{{ $user->roleLabel() }}" disabled style="background:var(--bg); cursor:not-allowed;">
                    <small class="muted">Role tidak dapat diubah setelah pendaftaran.</small>
                </div>
                
                <div class="field">
                    <label>Pilih Avatar Baru</label>
                    <x-avatar-picker name="avatar" :selected="old('avatar', $user->avatar)" :size="62" />
                </div>

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required maxlength="8" pattern="[a-zA-Z0-9_]+" title="Maksimal 8 karakter tanpa spasi">
                </div>

                <div class="field">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="field">
                    <label for="phone">Nomor HP</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" required>
                </div>
                
                <hr style="border:0; border-top:1px solid var(--line); margin: 10px 0;">
                
                <p class="muted" style="font-size:13px; margin:0;">Kosongkan bagian password jika tidak ingin mengubah password.</p>
                
                <div class="field">
                    <label for="current_password">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password">
                </div>
                
                <div class="field">
                    <label for="password">Password Baru</label>
                    <input id="password" name="password" type="password" minlength="8">
                </div>
                
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8">
                </div>
                
                <div style="margin-top:10px;">
                    <button class="button" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </section>

        {{-- Section PIN Withdraw: hanya untuk role yang bisa withdraw --}}
        @if(in_array(auth()->user()->role, ['mechanic', 'cashier', 'owner']))
        <section class="panel">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:15px;">
                <span style="font-size:20px;">🔒</span>
                <h2 style="margin:0;">Ubah Pin</h2>
                @if(! auth()->user()->withdraw_pin)
                    <span style="font-size:11px; background:var(--warning, #f59e0b); color:#000; padding:2px 8px; border-radius:20px; font-weight:600;">Belum Diatur</span>
                @else
                    <span style="font-size:11px; background:var(--success, #10b981); color:#fff; padding:2px 8px; border-radius:20px; font-weight:600;">Sudah Diatur</span>
                @endif
            </div>

            <form method="POST" action="{{ route('account.update') }}" class="stack">
                @csrf
                @method('PATCH')

                {{-- Field tersembunyi agar form profil lain tidak terkirim --}}
                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                <input type="hidden" name="username" value="{{ auth()->user()->username }}">
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">

                <div class="field">
                    <label for="current_withdraw_pin">Masukkan Pin Terkini</label>
                    <input id="current_withdraw_pin" name="current_withdraw_pin" type="password"
                           inputmode="numeric" maxlength="4" placeholder="{{ auth()->user()->withdraw_pin ? 'Masukkan PIN lama' : 'Masukkan Pin Default' }}">
                    @error('current_withdraw_pin')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="withdraw_pin">Masukkan Pin Baru</label>
                    <input id="withdraw_pin" name="withdraw_pin" type="password"
                           inputmode="numeric" maxlength="4" placeholder="Masukkan 4 digit PIN baru">
                    @error('withdraw_pin')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="withdraw_pin_confirmation">Konfirmasi PIN Baru</label>
                    <input id="withdraw_pin_confirmation" name="withdraw_pin_confirmation" type="password"
                           inputmode="numeric" maxlength="4" placeholder="Ulangi PIN baru">
                </div>

                <div style="margin-top:10px;">
                    <button class="button" type="submit">Konfirmasi</button>
                </div>
            </form>
        </section>
        @endif
    </div>
    
    <div class="stack">
        <section class="panel">
            <h2>Pengaturan Tema</h2>
            <p class="muted" style="margin-bottom:20px; font-size:14px;">Pilih tema antarmuka sesuai gaya Anda.</p>
            
            <div class="field">
                <label for="theme_selector">Pilih Tema</label>
                <select id="theme_selector" style="cursor:pointer;">
                    <option value="dark">🌙 Dark (Default)</option>
                    <option value="dark-planet">🪐 Dark Planet</option>
                    <option value="horror">🩸 Horror</option>
                    <option value="modern">⚡ Modern</option>
                </select>
                <small class="muted">Tema akan langsung berubah saat dipilih.</small>
            </div>
        </section>





        <section class="panel" style="border-color:var(--danger);">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                <span style="font-size:20px;">⚠️</span>
                <h2 style="margin:0; color:var(--danger);">Hapus Akun</h2>
            </div>
            <p class="muted" style="margin-bottom:20px; font-size:14px; line-height:1.5;">
                Setelah akun dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. 
                Sebelum menghapus akun, pastikan Anda telah menyimpan data yang ingin dipertahankan.
            </p>
            
            <form method="POST" action="{{ route('account.destroy') }}" class="stack" data-confirm="Apakah Anda yakin ingin menghapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                @method('DELETE')
                
                <div class="field">
                    <label for="delete_password">Konfirmasi dengan Password</label>
                    <input id="delete_password" name="password" type="password" required placeholder="Masukkan password Anda">
                </div>
                
                <div>
                    <button class="button danger" type="submit">Hapus Akun Permanen</button>
                </div>
            </form>
        </section>
    </div>
</div>

<!-- THANK YOU SUCCESS MODAL POPUP -->
<div id="thankYouModal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(8px); display:none; align-items:center; justify-content:center; z-index:99999; animation:modalOverlayIn 0.25s ease-out;">
    <div style="background:linear-gradient(135deg, #111827, #070a13); border:2px solid var(--primary); padding:32px; border-radius:16px; max-width:440px; width:100%; text-align:center; box-shadow:var(--shadow);">
        <div style="font-size:50px; margin-bottom:16px; filter:drop-shadow(0 0 10px rgba(32,188,168,0.35));">✨</div>
        <h2 style="margin:0 0 12px 0; font-size:20px; font-weight:800; color:var(--ink);">Pembelian Berhasil</h2>
        <p class="muted" style="font-size:14px; line-height:1.6; margin-bottom:24px; color:#c5c5c5;">
            Terima kasih telah membeli Fitur eksklusif kami, Fitur sekarang bisa diaktifkan di Tema dalam pengaturan
        </p>
        <button type="button" class="button" onclick="document.getElementById('thankYouModal').style.display='none';" style="width:120px; margin:0 auto; padding:8px 16px; font-weight:700;">Tutup</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeSelector = document.getElementById('theme_selector');
        const userId = '{{ auth()->id() }}';
        let currentTheme = localStorage.getItem('theme_' + userId) || 'dark';
        
        if (currentTheme === 'light') {
            currentTheme = 'dark';
            localStorage.setItem('theme_' + userId, 'dark');
            document.documentElement.dataset.theme = 'dark';
        }
        
        // Set dropdown value to current theme
        themeSelector.value = currentTheme;
        
        // Handle theme change
        themeSelector.addEventListener('change', function(e) {
            const selectedTheme = e.target.value;
            currentTheme = selectedTheme;
            document.documentElement.dataset.theme = selectedTheme;
            localStorage.setItem('theme_' + userId, selectedTheme);
        });
    });
</script>
@endsection
