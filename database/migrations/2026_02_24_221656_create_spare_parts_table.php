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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('unit')->default('pieza');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('minium_stock')->default(5);
            $table->string('location')->nullable();
            $table->string('supplier')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sku');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
