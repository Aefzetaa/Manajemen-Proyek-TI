<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use App\Models\SparePart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $serviceTypes = [
            ['name' => 'Servis Ringan', 'estimated_minutes' => 60, 'base_price' => 75000],
            ['name' => 'Ganti Oli', 'estimated_minutes' => 30, 'base_price' => 45000],
            ['name' => 'Tune Up Mesin', 'estimated_minutes' => 120, 'base_price' => 150000],
            ['name' => 'Perbaikan Rem', 'estimated_minutes' => 90, 'base_price' => 100000],
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
