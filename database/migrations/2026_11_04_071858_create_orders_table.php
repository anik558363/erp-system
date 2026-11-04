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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_no')->unique(); // NSM-ORD-261103
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        $table->foreignId('salesman_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('area_id')->nullable()->constrained('areas')->onDelete('set null');
        $table->date('order_date');
        $table->decimal('total_amount', 14, 2);
        $table->decimal('returned_amount', 14, 2)->default(0);
        $table->decimal('net_amount', 14, 2)->default(0);
        $table->decimal('paid_amount', 14, 2)->default(0);
        $table->string('status')->default('PENDING_APPROVAL'); // PENDING_APPROVAL / APPROVED / PACKED / DISPATCHED / DELIVERED / RECONCILED / CANCELLED
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamp('approved_at')->nullable();
        $table->foreignId('packed_by')->nullable()->constrained('users');
        $table->timestamp('packed_at')->nullable();
        $table->foreignId('delivery_man_id')->nullable()->constrained('users');
        $table->timestamp('dispatched_at')->nullable();
        $table->timestamp('delivered_at')->nullable();
        $table->foreignId('reconciled_by')->nullable()->constrained('users');
        $table->timestamp('reconciled_at')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();

        // Indexes for high performance pipeline queries
        $table->index('customer_id');
        $table->index('salesman_id');
        $table->index('delivery_man_id');
        $table->index('status');
        $table->index('order_date');
        $table->index(['status', 'order_date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
