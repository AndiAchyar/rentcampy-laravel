<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('status', 20)->default('pending');
            $table->text('delivery_address');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('rental_days');
            $table->unsignedBigInteger('total_amount');
            $table->timestampsTz();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'start_date', 'end_date']);
        });
        DB::statement("ALTER TABLE rental_orders ADD CONSTRAINT rental_orders_status_check
            CHECK (status IN ('pending', 'approved', 'borrowed', 'returned', 'rejected', 'cancelled'))");
        DB::statement('ALTER TABLE rental_orders ADD CONSTRAINT rental_orders_dates_check CHECK (end_date >= start_date)');
        DB::statement('ALTER TABLE rental_orders ADD CONSTRAINT rental_orders_days_check CHECK (rental_days > 0)');

        Schema::create('rental_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('rental_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('category', 50);
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('quantity');
        });
        DB::statement('ALTER TABLE rental_order_items ADD CONSTRAINT rental_order_items_quantity_check CHECK (quantity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_order_items');
        Schema::dropIfExists('rental_orders');
    }
};
