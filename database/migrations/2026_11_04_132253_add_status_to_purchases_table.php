<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // যদি আগে থেকে status কলাম না থাকে, তবে এটি যোগ করবে
            if (!Schema::hasColumn('purchases', 'status')) {
                $table->string('status')->default('RECEIVED')->after('due_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
