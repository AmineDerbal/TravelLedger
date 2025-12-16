<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ledger_categories', function (Blueprint $table) {
            $table->dropForeign(['ledger_id']);
            $table->unsignedBigInteger('ledger_id')->nullable()->change();
        });
        DB::table('ledger_categories')->update(['ledger_id' => null]);

        Schema::table('ledger_categories', function (Blueprint $table) {
            $table->dropColumn('ledger_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledger_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->foreign('ledger_id')->references('id')->on('ledgers')->nullOnDelete();

        });
    }
};