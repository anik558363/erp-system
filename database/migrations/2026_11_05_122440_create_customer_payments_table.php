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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique(); // যেমন: PAY-261105-XXXX
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null'); // কোন অর্ডারের বিপরীতে পেমেন্ট (옵শনাল)
            $table->foreignId('collected_by')->constrained('users')->onDelete('cascade'); // কে পেমেন্ট রিসিভ করলো
            $table->decimal('amount', 14, 2); // কত টাকা দিল
            $table->string('payment_method')->default('CASH');
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->date('payment_date');
            $table->timestamps();

           
            $table->index('customer_id');
            $table->index('order_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
