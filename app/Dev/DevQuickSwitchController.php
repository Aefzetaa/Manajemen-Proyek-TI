<?php

namespace App\Dev;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * DEV-ONLY: Controller untuk DevQuickSwitch panel.
 * Hanya aktif saat config('dev_quick_switch.enabled') = true.
 * File ini sengaja ditempatkan di app/Dev/ (terpisah dari kode produksi)
 * agar mudah diidentifikasi sebagai kode pengembangan semata.
 */
class DevQuickSwitchController extends Controller
{
    public function switch(Request $request, User $user): RedirectResponse
    {
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:8', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone'    => ['required', 'regex:/^[0-9+ ]{10,16}$/'],
                'role'     => ['required', 'in:' . implode(',', array_keys(User::ROLES))],
                'password' => ['required', 'string', 'min:1', 'confirmed'],
                'avatar'   => ['nullable', 'string', 'in:' . implode(',', User::allowedAvatarValues())],
            ], [
                'phone.regex'      => 'Nomor HP tidak valid.',
                'username.regex'   => 'Username hanya huruf, angka, dan underscore.',
                'username.max'     => 'Username maksimal 8 karakter.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('_dqs_modal', 'add')
                ->withErrors($e->errors());
        }

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'role'     => $validated['role'],
            'password' => $validated['password'],
            'avatar'   => $validated['avatar'] ?? User::DEFAULT_AVATAR,
            'balance'  => 0,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $wasCurrent = auth()->id() === $user->id;

        if ($wasCurrent) {
            Auth::logout();
        }

        DB::table('zoruai_chats')->where('user_id', $user->id)->delete();
        $user->delete();

        if (User::count() === 0) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('welcome');
        }

        if ($wasCurrent) {
            $next = User::query()->orderBy('id')->first();
            Auth::login($next);
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return redirect()->back();
    }

    public function resetAll(Request $request, DevDemoResetService $reset): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget('reg_pending');

        $reset->resetAllTransactionalData();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Sistem Berhasil Direset');
    }
}
