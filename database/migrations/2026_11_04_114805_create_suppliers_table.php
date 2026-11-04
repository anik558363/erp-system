<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // যেমন: NSM-SUP-1001
            $table->string('name'); // সাপ্লায়ার বা কোম্পানির প্রতিনিধি
            $table->string('company_name'); // কোম্পানির নাম (যেমন: Square, Pran, City Group)
            $table->string('phone');
            $table->text('address');
            $table->decimal('opening_due', 15, 2)->default(0); // আগের পাওনা
            $table->decimal('current_due', 15, 2)->default(0); // বর্তমান পাওনা
            $table->string('status')->default('ACTIVE'); // ACTIVE বা INACTIVE
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
