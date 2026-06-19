<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'max:8', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/'],
            'avatar'           => ['nullable', 'string', 'in:' . implode(',', User::allowedAvatarValues())],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'            => ['required', 'regex:/^[0-9+ ]{10,16}$/'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
            // PIN withdraw
            'current_withdraw_pin' => ['nullable', 'required_with:withdraw_pin', 'string'],
            'withdraw_pin'         => ['nullable', 'string', 'digits:4', 'confirmed'],
        ], [
            'username.regex'           => 'Username hanya boleh huruf, angka, dan underscore (tanpa spasi).',
            'username.max'             => 'Username maksimal 8 karakter.',
            'withdraw_pin.digits'      => 'PIN withdraw harus tepat 4 digit angka.',
            'current_withdraw_pin.required_with' => 'Masukkan PIN lama untuk mengubah PIN withdraw.',
        ]);

        if (! empty($validated['password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            $user->password = $validated['password'];
        }

        // Update PIN withdraw jika diisi
        if (! empty($validated['withdraw_pin'])) {
            // Jika user belum punya PIN, wajib verifikasi default "1234" atau default role-nya
            if (! $user->withdraw_pin) {
                $defaultPin = '1234';
                if ($user->isRole('owner')) $defaultPin = '0104';
                elseif ($user->isRole('cashier')) $defaultPin = '0000';
                elseif ($user->isRole('mechanic')) $defaultPin = '1111';
                
                if (($validated['current_withdraw_pin'] ?? '') !== $defaultPin && ($validated['current_withdraw_pin'] ?? '') !== '1234') {
                    return back()->withErrors(['current_withdraw_pin' => 'PIN lama tidak sesuai.']);
                }
            } else {
                // Jika user sudah punya PIN, wajib verifikasi PIN lama dulu
                if (! Hash::check($validated['current_withdraw_pin'] ?? '', $user->withdraw_pin)) {
                    return back()->withErrors(['current_withdraw_pin' => 'PIN withdraw lama tidak sesuai.']);
                }
            }
            $user->withdraw_pin = $validated['withdraw_pin'];
        }

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);
        
        if (isset($validated['avatar'])) {
            $user->avatar = $validated['avatar'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Log the activity
        \App\Models\AccountActivity::create([
            'user_id' => $user->id,
            'type' => 'profile',
            'description' => 'Memperbarui profil akun',
            'amount' => 0,
        ]);

        return back()->with('success', 'Profil akun berhasil diperbarui.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah, akun tidak dihapus.']);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}
