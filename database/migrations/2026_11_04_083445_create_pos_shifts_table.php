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
    Schema::create('pos_shifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('register_id')->constrained('pos_registers')->onDelete('cascade');
        $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade');
        $table->timestamp('opened_at');
        $table->timestamp('closed_at')->nullable();
        $table->decimal('opening_cash', 14, 2)->default(0);
        $table->decimal('expected_cash', 14, 2)->nullable();
        $table->decimal('counted_cash', 14, 2)->nullable();
        $table->decimal('difference', 14, 2)->nullable();
        $table->string('status')->default('OPEN'); // OPEN / CLOSED
        $table->foreignId('closed_by')->nullable()->constrained('users');
        $table->text('note')->nullable();
        $table->timestamps();

        $table->index(['register_id', 'status']);
        $table->index('cashier_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_shifts');
    }
};
