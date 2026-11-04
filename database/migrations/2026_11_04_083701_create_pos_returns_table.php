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
    Schema::create('pos_returns', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained('pos_sales')->onDelete('cascade');
        $table->foreignId('sale_item_id')->constrained('pos_sale_items');
        $table->foreignId('product_id')->constrained('products');
        $table->foreignId('shift_id')->nullable()->constrained('pos_shifts');
        $table->string('return_type'); // GOOD / DAMAGED
        $table->integer('quantity');
        $table->decimal('refund_amount', 14, 2);
        $table->string('refund_method')->nullable();
        $table->text('reason')->nullable();
        $table->foreignId('recorded_by')->constrained('users');
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
        Schema::dropIfExists('pos_returns');
    }
};
