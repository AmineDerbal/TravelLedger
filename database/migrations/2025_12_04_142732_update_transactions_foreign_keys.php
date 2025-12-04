<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Make column nullable so nullOnDelete works
            $table->unsignedBigInteger('ledger_category_id')->nullable()->change();

            // Add real foreign keys
            $table->foreign('ledger_category_id')
                ->references('id')->on('ledger_categories')
                ->nullOnDelete();

            $table->foreign('ledger_id')
                ->references('id')->on('ledgers')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Remove added constraints
            $table->dropForeign(['ledger_category_id']);
            $table->dropForeign(['ledger_id']);

            // Restore previous state (indexes only)
            $table->index('ledger_category_id');
            $table->index('ledger_id');
        });
    }
};