@extends('layouts.public')

@section('title', 'Masuk')
@section('body-class', 'public-auth')

@section('content')
<div class="auth-page">
    <div style="width:100%; max-width: 440px; position: relative;">
        
        {{-- Auth card --}}
        <div class="auth-card">
            <h1 class="auth-title" style="text-align: center;">Login</h1>

            @if($errors->any())
                <div style="background: var(--danger); color: white; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(248, 113, 113, 0.2);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <div>
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:18px;">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label for="username">Username atau Email</label>
                    <input id="username" name="username" type="text"
                           value="{{ old('username') }}" required autofocus
                           placeholder="Masukkan username atau email Anda">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password"
                           required placeholder="Masukkan password">
                </div>
                <div class="check-row" style="margin-bottom:22px;">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember" style="margin:0; font-weight:500; cursor:pointer;">Ingat saya</label>
                </div>
                <button class="btn" type="submit" style="width:100%; padding:13px;">
                    Login
                </button>
            </form>
        </div>

        {{-- Register prompt — di luar card --}}
        <p class="auth-foot" style="text-align:center; margin-top:20px; color:var(--muted); font-size:14px;">
            Belum punya akun?
            <a class="text-link" href="{{ route('register') }}">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
