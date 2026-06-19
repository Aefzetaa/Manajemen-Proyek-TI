<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            // Kolom ini tidak pernah digunakan — service_order mengambil data
            // jenis servis dari relasi booking->serviceTypes (pivot table).
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->foreignId('service_type_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
