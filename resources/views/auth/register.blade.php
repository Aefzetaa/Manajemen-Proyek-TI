@extends('layouts.public')

@section('title', 'Daftar')
@section('body-class', 'public-auth')

@section('content')
<div class="auth-page" style="align-items: flex-start; padding-top:72px;">
    <div style="width:100%; max-width: 600px; margin: 0 auto; position: relative;">
        
        <div class="auth-card" style="max-width: 100%;">
            
            <div class="step-badge">Langkah 1 dari 2</div>
            <h1 class="auth-title">Daftar Akun Baru</h1>
            <p class="auth-subtitle">Silakan isi data diri Anda. Untuk role internal, Anda akan diminta kode verifikasi di langkah selanjutnya.</p>

            @if($errors->any())
                <div class="alert error" style="margin-bottom:18px;">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                
                <div style="margin-bottom:20px; text-align:center;">
                    <label style="display:block; margin-bottom:10px;">Pilih Avatar</label>
                    <div style="display:flex; justify-content:center; position:relative;">
                        <x-avatar-picker name="avatar" :size="68" :placeholder="true" :contained="true" />
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name', session('reg_pending.name')) }}" required autofocus>
                    </div>
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text"
                               value="{{ old('username', session('reg_pending.username')) }}" required maxlength="8" pattern="[a-zA-Z0-9_]+" title="Maksimal 8 karakter tanpa spasi">
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email', session('reg_pending.email')) }}" required>
                    </div>
                    <div class="field">
                        <label for="phone">Nomor HP</label>
                        <input id="phone" name="phone" type="text"
                               value="{{ old('phone', session('reg_pending.phone')) }}" required>
                    </div>
                </div>

                <div class="field">
                    <label for="role">Daftar Sebagai</label>
                    <select id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" @selected(old('role', session('reg_pending.role')) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required minlength="1">
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="1">
                    </div>
                </div>

                <button class="btn" type="submit" style="width:100%; padding:13px; margin-top:10px;">
                    Lanjut &rarr;
                </button>
            </form>
        </div>

        <p class="auth-foot" style="text-align:center; margin-top:20px; color:var(--muted); font-size:14px;">
            Sudah punya akun?
            <a class="text-link" href="{{ route('login') }}">Masuk ke sini</a>
        </p>
    </div>
</div>

@endsection
