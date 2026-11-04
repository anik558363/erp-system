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
    Schema::create('ledger_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        $table->date('entry_date');
        $table->string('type'); // OPENING / INVOICE / PAYMENT / RETURN / ADJUSTMENT
        $table->string('ref_type')->nullable(); // orders / payments / sales_returns
        $table->unsignedBigInteger('ref_id')->nullable();
        $table->string('ref_no')->nullable();
        $table->text('description')->nullable();
        $table->decimal('prev_balance', 14, 2);
        $table->decimal('debit', 14, 2)->default(0);
        $table->decimal('credit', 14, 2)->default(0);
        $table->decimal('running_balance', 14, 2);
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->timestamps();

        // Indexes for quick ledger statement generation
        $table->index(['customer_id', 'entry_date']);
        $table->index('type');
        $table->index('ref_no');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
