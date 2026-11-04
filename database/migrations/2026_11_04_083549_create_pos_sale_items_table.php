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
    Schema::create('pos_sale_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained('pos_sales')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products');
        $table->integer('quantity');
        $table->decimal('unit_price', 12, 2);
        $table->decimal('cost_price', 12, 2);
        $table->decimal('discount_amount', 12, 2)->default(0);
        $table->decimal('line_total', 14, 2);
        $table->integer('returned_qty')->default(0);
        $table->timestamps();

        $table->index('sale_id');
        $table->index('product_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_sale_items');
    }
};
