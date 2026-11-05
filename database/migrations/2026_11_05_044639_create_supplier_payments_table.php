<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('set null'); // কোন চালানের বিপরীতে পেমেন্ট হলে
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->default('Cash'); // Cash, Bank, Bkash ইত্যাদি
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
