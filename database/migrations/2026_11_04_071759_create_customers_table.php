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
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // NSM-CUST-1001
        $table->string('shop_name');
        $table->string('proprietor_name');
        $table->string('phone');
        $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
        $table->foreignId('salesman_id')->nullable()->constrained('users')->onDelete('set null');
        $table->text('address');
        $table->decimal('credit_limit', 14, 2)->default(0);
        $table->decimal('opening_due', 14, 2)->default(0);
        $table->decimal('current_due', 14, 2)->default(0);
        $table->string('photo')->nullable();
        $table->string('status')->default('ACTIVE');
        $table->timestamps();

        // Indexes for search & filtering
        $table->index('shop_name');
        $table->index('proprietor_name');
        $table->index('phone');
        $table->index('area_id');
        $table->index('salesman_id');
        $table->index('status');
        $table->index(['area_id', 'status']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
