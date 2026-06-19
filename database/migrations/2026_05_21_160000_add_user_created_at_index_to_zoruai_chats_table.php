<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zoruai_chats', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'zoruai_chats_user_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('zoruai_chats', function (Blueprint $table) {
            $table->dropIndex('zoruai_chats_user_created_at_index');
        });
    }
};
