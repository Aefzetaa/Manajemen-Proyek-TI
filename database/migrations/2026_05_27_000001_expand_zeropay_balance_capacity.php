<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'balance')) {
            DB::statement('ALTER TABLE users MODIFY balance DECIMAL(20,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasTable('account_activities') && Schema::hasColumn('account_activities', 'amount')) {
            DB::statement('ALTER TABLE account_activities MODIFY amount BIGINT NOT NULL DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'balance')) {
            DB::statement('ALTER TABLE users MODIFY balance DECIMAL(15,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasTable('account_activities') && Schema::hasColumn('account_activities', 'amount')) {
            DB::statement('ALTER TABLE account_activities MODIFY amount INT NOT NULL DEFAULT 0');
        }
    }
};
