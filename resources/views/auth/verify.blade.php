@extends('layouts.public')

@section('title', 'Verifikasi')
@section('body-class', 'public-verify')

@section('content')
<div class="auth-page" style="align-items:flex-start; padding-top:72px;">
    <div style="width:100%; max-width: 440px; position: relative;">
        
        <div class="auth-card">
            <div class="step-badge">Langkah 2 dari 2</div>
            <h1 class="auth-title">Verifikasi Akses</h1>
            <p class="auth-subtitle">Anda mendaftar sebagai <strong>{{ $roleLabel }}</strong>. Masukkan kode verifikasi untuk melanjutkan.</p>

            @if($errors->any())
                <div class="alert error" style="margin-bottom:18px;">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify.store') }}">
                @csrf
                <div class="verify-box">
                    <p>Kode ini diberikan oleh administrator untuk mencegah pendaftaran akun staf yang tidak sah.</p>
                    <div class="field" style="margin-bottom:0;">
                        <input id="verification_code" name="verification_code" type="password"
                               required autofocus autocomplete="off"
                               style="text-transform: uppercase; font-weight:700; font-size:16px; letter-spacing:1px; text-align:center;">
                    </div>
                </div>

                <button class="btn" type="submit" style="width:100%; padding:13px;">
                    Selesaikan Pendaftaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

