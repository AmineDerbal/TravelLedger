<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('description');
            $table->decimal('profit', 10, 2)->nullable();
            $table->unsignedTinyInteger('type');
            $table->foreignId('ledger_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('linked_transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
