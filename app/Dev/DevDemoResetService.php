<?php

namespace App\Dev;

use App\Models\AccountActivity;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\ServiceType;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DEV-ONLY: Service untuk mereset semua data transaksional.
 * Hanya digunakan oleh DevQuickSwitchController.
 * File ini sengaja ditempatkan di app/Dev/ (terpisah dari kode produksi)
 * agar mudah diidentifikasi sebagai kode pengembangan semata.
 */
class DevDemoResetService
{
    public function resetAllTransactionalData(): void
    {
        DB::transaction(function () {
            Invoice::query()->delete();
            Payment::query()->delete();
            ServiceOrderItem::query()->delete();
            ServiceOrder::query()->delete();
            $this->deleteTableIfExists('booking_service_type');
            Booking::query()->delete();
            Vehicle::query()->delete();
            AccountActivity::query()->delete();
            Message::query()->delete();
            $this->deleteTableIfExists('zoruai_chats');
            $this->deleteTableIfExists('promotions');

            User::query()->update($this->userResetPayload());

            $this->restoreCatalogDefaults();
        });
    }

    private function deleteTableIfExists(string $table): void
    {
        if (Schema::hasTable($table)) {
            DB::table($table)->delete();
        }
    }

    private function userResetPayload(): array
    {
        return [
            'balance'               => 0,
            'unclaimed_salary'      => 0,
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ];
    }

    private function restoreCatalogDefaults(): void
    {
        $serviceTypes = [
            ['name' => 'Servis Ringan', 'estimated_minutes' => 60, 'base_price' => 75000, 'mechanic_salary' => 35, 'cashier_salary' => 15],
            ['name' => 'Ganti Oli', 'estimated_minutes' => 30, 'base_price' => 45000, 'mechanic_salary' => 35, 'cashier_salary' => 15],
            ['name' => 'Tune Up Mesin', 'estimated_minutes' => 120, 'base_price' => 150000, 'mechanic_salary' => 35, 'cashier_salary' => 15],
            ['name' => 'Perbaikan Rem', 'estimated_minutes' => 90, 'base_price' => 100000, 'mechanic_salary' => 35, 'cashier_salary' => 15],
        ];

        foreach ($serviceTypes as $type) {
            ServiceType::updateOrCreate(['name' => $type['name']], $type);
        }

        $parts = [
            ['name' => 'Oli Mesin 0.8L', 'sku' => 'OLI-08', 'stock' => 40, 'price' => 65000],
            ['name' => 'Busi Motor', 'sku' => 'BUSI-STD', 'stock' => 32, 'price' => 28000],
            ['name' => 'Kampas Rem Depan', 'sku' => 'REM-DEPAN', 'stock' => 18, 'price' => 85000],
            ['name' => 'Filter Udara', 'sku' => 'FILTER-UD', 'stock' => 24, 'price' => 45000],
        ];

        foreach ($parts as $part) {
            SparePart::updateOrCreate(['sku' => $part['sku']], $part);
        }
    }
}
