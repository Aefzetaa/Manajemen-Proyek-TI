<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\PurchaseTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// Simple E2E-style simulation: ensure a test user, deduct NOX and create PurchaseTransaction
$email = 'copilot-e2e-test@example.com';
$testPrice = 50;

$user = User::where('email', $email)->first();
if (! $user) {
    $user = User::create([
        'name' => 'Copilot E2E',
        'username' => 'copilot_e2e_' . Str::random(6),
        'email' => $email,
        'password' => bcrypt(Str::random(16)),
        'balance' => 1000000000,
    ]);
    echo "Created user id {$user->id}\n";
} else {
    echo "Found user id {$user->id}\n";
}

// Ensure the user has NOX balance to pay
$user->nox_balance = max(1000, $user->nox_balance ?? 0);
$user->save();
echo "User nox_balance={$user->nox_balance}, balance={$user->balance}\n";

use Illuminate\Support\Facades\DB as DBFac;

$ok = DBFac::transaction(function () use ($user, $testPrice) {
    $u = DBFac::table('users')->where('id', $user->id)->lockForUpdate()->first();
    if (! isset($u->nox_balance)) {
        echo "User record missing 'nox_balance' column\n";
        return false;
    }
    if ($u->nox_balance < $testPrice) {
        echo "Insufficient NOX: {$u->nox_balance}\n";
        return false;
    }

    DBFac::table('users')->where('id', $user->id)->update(['nox_balance' => $u->nox_balance - $testPrice]);

    $pt = PurchaseTransaction::create([
        'user_id' => $user->id,
        'confirmation_token' => Str::random(40),
        'item_type' => 'test_item',
        'item_key' => 'copilot_test',
        'price' => $testPrice,
        'status' => 'completed',
        'metadata' => ['note' => 'E2E sim']
    ]);

    echo "Created PurchaseTransaction id {$pt->id}\n";
    return true;
});

if ($ok) {
    echo "E2E purchase simulation succeeded\n";
} else {
    echo "E2E purchase simulation failed\n";
}
