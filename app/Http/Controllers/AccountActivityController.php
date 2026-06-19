<?php

namespace App\Http\Controllers;

use App\Models\AccountActivity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = AccountActivity::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('account.activities', [
            'activities' => $activities,
        ]);
    }
}
