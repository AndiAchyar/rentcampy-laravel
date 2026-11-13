<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 50)->index();
            // Path file di storage/public, atau data URL untuk gambar contoh dari seeder.
            $table->text('image')->nullable();
            $table->text('description');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock')->default(0);
            $table->timestampsTz();
        });
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_price_check CHECK (price >= 0)');
        DB::statement('ALTER TABLE products ADD CONSTRAINT products_stock_check CHECK (stock >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
