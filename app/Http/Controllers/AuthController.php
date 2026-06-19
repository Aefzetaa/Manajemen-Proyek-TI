<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /* ─────────────────────────────────── Login ─── */

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        if ($user?->locked_until && $user->locked_until->isFuture()) {
            return back()
                ->withErrors(['username' => 'Akun terkunci sampai ' . $user->locked_until->format('H:i') . '.'])
                ->onlyInput('username');
        }

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            if ($user) {
                $attempts = $user->failed_login_attempts + 1;
                $user->forceFill([
                    'failed_login_attempts' => $attempts,
                    'locked_until'          => $attempts >= 3 ? now()->addMinutes(30) : null,
                ])->save();
            }

            return back()
                ->withErrors(['username' => 'Username atau password tidak sesuai.'])
                ->onlyInput('username');
        }

        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ])->save();

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Langsung arahkan ke dashboard setelah login
        return redirect()->route('dashboard');
    }

    /* ─────────────────────────────────── Logout ─── */

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isRole('cashier') && $user->unclaimed_salary > 0 && !$request->boolean('force_logout')) {
            return back()->with('unclaimed_salary_prompt', true);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Selalu arahkan ke halaman welcome setelah logout
        return redirect()->route('welcome')->with('success', 'Anda berhasil keluar.');
    }

    /* ─────────────────────────────────── Register Step 1 ─── */

    public function showRegister(): View
    {
        return view('auth.register', [
            'roles' => User::ROLES,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:8', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'regex:/^[0-9+ ]{10,16}$/'],
            'role'     => ['required', 'in:' . implode(',', array_keys(User::ROLES))],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
            'avatar'   => ['nullable', 'string', 'in:' . implode(',', User::allowedAvatarValues())],
        ], [
            'phone.regex' => 'Nomor HP tidak valid.',
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore (tanpa spasi).',
            'username.max' => 'Username maksimal 8 karakter.',
        ]);

        $role = $validated['role'];

        // Jika role internal → simpan data ke session, arahkan ke verifikasi kode
        if (in_array($role, User::INTERNAL_ROLES, true)) {
            session([
                'reg_pending' => [
                    'name'     => $validated['name'],
                    'username' => $validated['username'],
                    'email'    => $validated['email'],
                    'phone'    => $validated['phone'],
                    'role'     => $role,
                    'password' => bcrypt($validated['password']),
                    'avatar'   => $validated['avatar'] ?? User::DEFAULT_AVATAR,
                ],
            ]);
            return redirect()->route('register.verify');
        }

        // Role pelanggan → langsung buat akun
        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'role'     => $role,
            'password' => $validated['password'],
            'avatar'   => $validated['avatar'] ?? User::DEFAULT_AVATAR,
            'balance'  => 0,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil.');
    }

    /* ─────────────────────────────────── Register Step 2 – Verifikasi ─── */

    public function showVerify(Request $request): View|RedirectResponse
    {
        $pending = session('reg_pending');

        if (! $pending) {
            return redirect()->route('register');
        }

        return view('auth.verify', [
            'roleLabel' => User::ROLES[$pending['role']] ?? $pending['role'],
            'role'      => $pending['role'],
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $pending = session('reg_pending');

        if (! $pending) {
            return redirect()->route('register');
        }

        $request->validate([
            'verification_code' => ['required', 'string'],
        ]);

        $role            = $pending['role'];
        $expectedCode    = User::verificationCodeFor($role);

        if ($request->input('verification_code') !== $expectedCode) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi salah. Hubungi administrator bengkel.',
            ]);
        }

        // Cek batas akun per role
        if (! User::canRegisterRole($role)) {
            $limit = config("role_verification.limits.{$role}");
            return back()->withErrors([
                'verification_code' => "Role {$role} sudah mencapai batas maksimal ({$limit} akun). Hubungi administrator.",
            ]);
        }

        // Buat akun
        $user = User::forceCreate([
            'name'     => $pending['name'],
            'username' => $pending['username'],
            'email'    => $pending['email'],
            'phone'    => $pending['phone'],
            'role'     => $pending['role'],
            'password' => $pending['password'],
            'avatar'   => $pending['avatar'] ?? User::DEFAULT_AVATAR,
            'balance'  => 0,
        ]);

        session()->forget('reg_pending');

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Verifikasi berhasil.');
    }
}
