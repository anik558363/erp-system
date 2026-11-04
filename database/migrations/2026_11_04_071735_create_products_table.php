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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->unique();
        $table->string('barcode')->unique()->nullable(); // POS Scanner er jonno
        $table->string('name');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
        $table->string('unit_type'); // Carton / Bag / Piece
        $table->decimal('purchase_price', 12, 2);
        $table->decimal('wholesale_price', 12, 2);
        $table->decimal('mrp', 12, 2);
        $table->integer('min_stock')->default(0);
        $table->string('image')->nullable();
        $table->string('status')->default('ACTIVE');
        $table->timestamps();

        $table->index('name');
        $table->index('barcode');
        $table->index('category_id');
        $table->index('brand_id');
        $table->index('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
