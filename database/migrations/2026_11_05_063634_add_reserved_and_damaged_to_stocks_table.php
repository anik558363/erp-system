<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('stocks', function (Blueprint $table) {
        if (!Schema::hasColumn('stocks', 'reserved_qty')) {
            $table->integer('reserved_qty')->default(0)->after('physical_qty');
        }
        if (!Schema::hasColumn('stocks', 'damaged_qty')) {
            $table->integer('damaged_qty')->default(0)->after('reserved_qty');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            //
        });
    }
};
