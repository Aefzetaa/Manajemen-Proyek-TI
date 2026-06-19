<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Dev\DevQuickSwitchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\WithdrawController;
use App\Dev\EnsureDevQuickSwitch;
use App\Models\Promotion;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;


// Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Public welcome page (always accessible) Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
Route::get('/', function () {
    $services = Schema::hasTable('service_types')
        ? ServiceType::with('activePromotions')->orderBy('name')->get(['id', 'name', 'estimated_minutes', 'base_price'])
        : collect();

    $activePromotions = Schema::hasTable('promotions')
        ? Promotion::with('serviceType')->where('is_active', true)->latest()->get()
        : collect();

    return view('welcome', compact('services', 'activePromotions'));
})->name('welcome');

Route::post('/zoruai/public', [ReportController::class, 'publicZoruAi'])->name('zoruai.public');

// Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Guest-only routes Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.store');

    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    // Step 2 verification (kode role internal)
    Route::get('/register/verify',  [AuthController::class, 'showVerify'])->name('register.verify');
    Route::post('/register/verify', [AuthController::class, 'verify'])->name('register.verify.store');
});

// Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Authenticated routes Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Account management
    Route::get('/account/edit',    [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account',       [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account',      [AccountController::class, 'destroy'])->name('account.destroy');
    Route::get('/account/activities', [\App\Http\Controllers\AccountActivityController::class, 'index'])->name('account.activities');

    // Topup Saldo (Mock)
    Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
    Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

    // Messages
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}/read', [\App\Http\Controllers\MessageController::class, 'read'])->name('messages.read');

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/service-orders', [ServiceOrderController::class, 'index'])->name('service-orders.index');
    Route::get('/service-orders/{serviceOrder}', [ServiceOrderController::class, 'show'])->name('service-orders.show');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/invoices/{invoice}', [PaymentController::class, 'invoice'])->name('payments.invoice');

    Route::get('/agent-ai', [ReportController::class, 'analytics'])->name('reports.analytics');
    Route::post('/agent-ai/zoru', [ReportController::class, 'zoruAi'])->name('reports.analytics.zoru');
    Route::post('/agent-ai/zoru/restock', [ReportController::class, 'zoruAiRestock'])->name('reports.analytics.zoru.restock');
    Route::delete('/agent-ai/zoru/restock', [ReportController::class, 'zoruAiCancelRestock'])->name('reports.analytics.zoru.restock.cancel');

    Route::middleware('role:customer')->group(function () {
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');

        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::patch('/service-orders/{serviceOrder}/approve', [ServiceOrderController::class, 'approve'])->name('service-orders.approve');
        Route::patch('/service-orders/{serviceOrder}/approve-pay', [ServiceOrderController::class, 'approveAndPay'])->name('service-orders.approve-pay');
        Route::patch('/payments/{payment}/pay-customer', [PaymentController::class, 'payCustomer'])->name('payments.pay-customer');
    });

    Route::middleware('role:mechanic,owner')->group(function () {
        Route::patch('/bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
        Route::patch('/bookings/{booking}/start-work', [BookingController::class, 'startWork'])->name('bookings.start-work');
        Route::get('/bookings/{booking}/service/create', [ServiceOrderController::class, 'create'])->name('service-orders.create');
        Route::post('/bookings/{booking}/service', [ServiceOrderController::class, 'store'])->name('service-orders.store');
    });

    Route::middleware('role:mechanic,cashier,owner,customer')->group(function () {
        Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
        Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.store');
    });

    Route::middleware('role:owner')->group(function () {
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/reports/finance', [ReportController::class, 'finance'])->name('reports.finance');
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::post('/catalog/service-types', [CatalogController::class, 'storeServiceType'])->name('catalog.service-types.store');
        Route::patch('/catalog/service-types/{serviceType}', [CatalogController::class, 'updateServiceType'])->name('catalog.service-types.update');
        Route::delete('/catalog/service-types/{serviceType}', [CatalogController::class, 'destroyServiceType'])->name('catalog.service-types.destroy');
        Route::post('/catalog/promotions', [CatalogController::class, 'storePromotion'])->name('catalog.promotions.store');
        Route::patch('/catalog/promotions/{promotion}', [CatalogController::class, 'updatePromotion'])->name('catalog.promotions.update');
        Route::delete('/catalog/promotions/{promotion}', [CatalogController::class, 'destroyPromotion'])->name('catalog.promotions.destroy');
        Route::post('/catalog/spare-parts', [CatalogController::class, 'storeSparePart'])->name('catalog.spare-parts.store');
        Route::patch('/catalog/spare-parts/{sparePart}', [CatalogController::class, 'updateSparePart'])->name('catalog.spare-parts.update');
        Route::post('/catalog/spare-parts/{sparePart}/restock', [CatalogController::class, 'restockSparePart'])->name('catalog.spare-parts.restock');
        Route::delete('/catalog/spare-parts/{sparePart}', [CatalogController::class, 'destroySparePart'])->name('catalog.spare-parts.destroy');
    });

    Route::middleware('role:cashier,owner')->group(function () {
        Route::patch('/payments/{payment}/paid', [PaymentController::class, 'markPaid'])->name('payments.paid');
        Route::post('/payments/{payment}/give-nota', [PaymentController::class, 'giveNota'])->name('payments.give-nota');
        Route::patch('/service-orders/{serviceOrder}/send-to-customer', [ServiceOrderController::class, 'sendToCustomer'])->name('service-orders.send-to-customer');
        Route::get('/reports/cashier', [ReportController::class, 'cashier'])->name('reports.cashier');
        Route::post('/cashier/claim-salary', [\App\Http\Controllers\CashierSalaryController::class, 'claim'])->name('cashier.claim-salary');
    });
});

// Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬ Dev quick switch (local only, floating tester tool) Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬Ã¢â€â‚¬
if (config('dev_quick_switch.enabled')) {
    Route::middleware(EnsureDevQuickSwitch::class)->group(function () {
        Route::post('/dev/quick-switch/reset-all', [DevQuickSwitchController::class, 'resetAll'])
            ->name('dev.quick-switch.reset-all');
        Route::post('/dev/quick-switch/users', [DevQuickSwitchController::class, 'store'])
            ->name('dev.quick-switch.store');
        Route::post('/dev/quick-switch/{user}', [DevQuickSwitchController::class, 'switch'])
            ->name('dev.quick-switch.switch');
        Route::delete('/dev/quick-switch/{user}', [DevQuickSwitchController::class, 'destroy'])
            ->name('dev.quick-switch.destroy');
    });
}



