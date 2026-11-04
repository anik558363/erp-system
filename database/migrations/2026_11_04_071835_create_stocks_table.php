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
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->unique()->constrained('products')->onDelete('cascade');
        $table->integer('physical_qty')->default(0);
        $table->integer('reserved_qty')->default(0); // Approved order-er jonno আটকানো
        $table->integer('damaged_qty')->default(0);
        $table->timestamp('updated_at')->nullable();

        $table->index('product_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
