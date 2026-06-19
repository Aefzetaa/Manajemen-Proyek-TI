<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('estimated_minutes')->default(60);
            $table->unsignedInteger('base_price')->default(0);
            $table->timestamps();
        });

        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plate_number')->index();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained()->nullOnDelete();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('status')->default('scheduled');
            $table->text('service_description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['booking_date', 'booking_time']);
        });

        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained()->nullOnDelete();
            $table->date('serviced_at');
            $table->text('complaint')->nullable();
            $table->text('diagnosis')->nullable();
            $table->unsignedInteger('labor_cost')->default(0);
            $table->unsignedInteger('parts_total')->default(0);
            $table->unsignedInteger('total_cost')->default(0);
            $table->string('status')->default('waiting_approval');
            $table->timestamp('customer_approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spare_part_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price')->default(0);
            $table->unsignedInteger('total_price')->default(0);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference_no')->unique();
            $table->unsignedInteger('amount')->default(0);
            $table->string('method')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('recipient_channel')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('service_order_items');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('spare_parts');
        Schema::dropIfExists('service_types');
    }
};
