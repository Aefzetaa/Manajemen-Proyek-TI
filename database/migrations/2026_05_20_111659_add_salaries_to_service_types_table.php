<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->unsignedInteger('mechanic_salary')->default(0)->after('base_price');
            $table->unsignedInteger('cashier_salary')->default(0)->after('mechanic_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn(['mechanic_salary', 'cashier_salary']);
        });
    }
};
