<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WithdrawController extends Controller
{
    public function index(): View
    {
        return view('withdraw.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $method = $request->input('method');
        
        $maxAmount = $method === 'zeropay' ? $user->withdrawableFees() : $user->balance;

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1000', 'max:' . $maxAmount],
            'method' => ['required', 'string'],
            'pin'    => ['required', 'string'],
        ], [
            'amount.max' => $method === 'zeropay' ? 'Jumlah penarikan melebihi fee yang bisa ditarik.' : 'Saldo Anda tidak cukup.',
            'pin.required' => 'PIN wajib diisi.',
        ]);

        // Verifikasi PIN: dukung default role-based dan "1234" jika belum diatur
        if (! $user->withdraw_pin) {
            $defaultPin = '1234';
            if ($user->isRole('owner')) $defaultPin = '0104';
            elseif ($user->isRole('cashier')) $defaultPin = '0000';
            elseif ($user->isRole('mechanic')) $defaultPin = '1111';
            
            if ($validated['pin'] !== $defaultPin && $validated['pin'] !== '1234') {
                return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah. Gunakan PIN default Anda.']);
            }
        } else {
            if (! Hash::check($validated['pin'], $user->withdraw_pin)) {
                return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah.']);
            }
        }

        if ($validated['method'] === 'zeropay') {
            abort_unless($user->isRole('cashier'), 403);
            
            // Proses OOP state transition terenkapsulasi
            try {
                $user->withdrawFeesToZeroPay($validated['amount']);
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['amount' => $e->getMessage()]);
            }

            \App\Models\AccountActivity::create([
                'user_id'     => $user->id,
                'type'        => 'money_in',
                'description' => 'Tarik fee kasir ke ZeroPay',
                'amount'      => $validated['amount'],
            ]);

            return back()->with('success', 'Fee kasir sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil ditransfer ke saldo ZeroPay Anda.');
        }

        $user->decrement('balance', $validated['amount']);
        $user->save();

        \App\Models\AccountActivity::create([
            'user_id'     => $user->id,
            'type'        => 'money_out',
            'description' => 'Tarik dana ke ' . strtoupper($validated['method']),
            'amount'      => -abs($validated['amount']),
        ]);

        return back()->with('success', 'Dana berhasil ditarik dan sedang diproses ke ' . strtoupper($validated['method']) . ' Anda.');
    }
}

