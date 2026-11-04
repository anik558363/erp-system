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
    Schema::create('pos_sales', function (Blueprint $table) {
        $table->id();
        $table->string('sale_no')->unique();
        $table->foreignId('register_id')->constrained('pos_registers');
        $table->foreignId('shift_id')->constrained('pos_shifts');
        $table->foreignId('cashier_id')->constrained('users');
        $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
        $table->timestamp('sale_date');
        $table->decimal('subtotal', 14, 2);
        $table->decimal('discount_amount', 14, 2)->default(0);
        $table->decimal('tax_amount', 14, 2)->default(0);
        $table->decimal('total_amount', 14, 2);
        $table->decimal('paid_amount', 14, 2)->default(0);
        $table->decimal('change_amount', 14, 2)->default(0);
        $table->decimal('due_amount', 14, 2)->default(0);
        $table->string('status')->default('COMPLETED');
        $table->foreignId('voided_by')->nullable()->constrained('users');
        $table->timestamp('voided_at')->nullable();
        $table->text('void_reason')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();

        $table->index('sale_no');
        $table->index('customer_id');
        $table->index('status');
        $table->index('sale_date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_sales');
    }
};
