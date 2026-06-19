<?php

namespace App\Http\Controllers;

use App\Models\AccountActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CashierSalaryController extends Controller
{
    public function claim(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->isRole('cashier'), 403);

        $amount = $user->unclaimed_salary;
        if ($amount <= 0) {
            return back()->withErrors(['salary' => 'Tidak ada gaji yang belum diambil.']);
        }

        // Move unclaimed to balance
        $user->addBalance($amount);
        $user->update(['unclaimed_salary' => 0]);

        AccountActivity::create([
            'user_id' => $user->id,
            'type' => 'money_in',
            'description' => 'Klaim gaji kasir (harian & fee transaksi)',
            'amount' => $amount,
        ]);

        $action = $request->input('action'); // 'ambil' or 'tarik'

        if ($action === 'tarik') {
            return redirect()->route('withdraw.index')->with('success', 'Gaji telah masuk saldo. Silakan lakukan penarikan dana.');
        }

        // If 'ambil', force logout
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Gaji berhasil diklaim ke saldo ZeroPay. Anda berhasil keluar.');
    }
}
