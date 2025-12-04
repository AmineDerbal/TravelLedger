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
        Schema::create('ledger_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->unsignedTinyInteger('type');
            $table->foreignId('ledger_id')->constrained();
            $table->timestamps();
            $table->unique(['type', 'ledger_id', 'name'], 'unique_type_ledger_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_categories');
    }
};