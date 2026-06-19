<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // PIN 4 digit untuk otentikasi withdraw, disimpan ter-hash.
            // NULL = user belum mengatur PIN, tidak bisa withdraw sebelum PIN diatur.
            $table->string('withdraw_pin')->nullable()->after('balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('withdraw_pin');
        });
    }
};
