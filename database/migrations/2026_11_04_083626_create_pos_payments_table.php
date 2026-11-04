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
    Schema::create('pos_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained('pos_sales')->onDelete('cascade');
        $table->string('method'); // CASH / BKASH / NAGAD / CARD / BANK
        $table->decimal('amount', 14, 2);
        $table->string('reference_no')->nullable();
        $table->timestamp('paid_at');
        $table->foreignId('received_by')->constrained('users');
        $table->timestamps();

        $table->index('sale_id');
        $table->index('method');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_payments');
    }
};
