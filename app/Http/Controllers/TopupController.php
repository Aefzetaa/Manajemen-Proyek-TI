<?php

namespace App\Http\Controllers;

use App\Models\AccountActivity;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index()
    {
        return view('topup.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000', 'max:10000000'],
            'method' => ['required', 'string', 'in:gopay,ovo,dana,shopeepay'],
        ]);

        $amount = (float) $request->amount;
        $user = $request->user();

        if (! $user->canReceiveBalance($amount)) {
            return back()->withErrors([
                'amount' => 'Saldo ZeroPay maksimal Rp ' . number_format($user::MAX_BALANCE, 0, ',', '.') . '. Kurangi nominal top-up agar saldo tidak melewati batas.',
            ])->withInput();
        }

        $user->addBalance($amount);

        AccountActivity::create([
            'user_id' => $user->id,
            'type' => 'money_in',
            'description' => 'Topup saldo via ' . strtoupper($request->method),
            'amount' => $amount,
        ]);

        return redirect()->route('dashboard')->with('success', 'Top-up saldo sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil ditambahkan via ' . strtoupper($request->method) . '.');
    }
}
