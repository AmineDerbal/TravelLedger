<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 1. Drop existing foreign keys
            $table->dropForeign('transactions_ledger_id_foreign');
            $table->dropForeign('transactions_user_id_foreign');

            // 2. Drop indexes with same names (if they exist)
            // Safe: Laravel ignores if missing
            $table->dropIndex('transactions_ledger_id_foreign');
            $table->dropIndex('transactions_user_id_foreign');

            // 3. Recreate the foreign keys properly
            $table->foreign('ledger_id')
                ->references('id')->on('ledgers')
                ->onDelete('cascade'); // or NO ACTION if you prefer

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null');

            // 4. Add missing ledger_category_id foreign key
            if (!Schema::hasColumn('transactions', 'ledger_category_id')) {
                // Only if needed — otherwise remove this block
            } else {
                $table->foreign('ledger_category_id')
                    ->references('id')->on('ledger_categories')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Reverse operations
            $table->dropForeign(['ledger_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['ledger_category_id']);
        });
    }
};
