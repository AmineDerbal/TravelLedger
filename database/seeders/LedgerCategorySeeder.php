<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerCategory;
use App\Enums\TransactionType;
use Carbon\Carbon;

class LedgerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // office id 1 
        // RTW id 2
        $now = Carbon::now();
  LedgerCategory::insert([
    [
        'name' => 'Rechargement',
        'type' => TransactionType::Credit->value,
        'ledger_id' => 2,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'name' => 'Refund',
        'type' => TransactionType::Credit->value,
        'ledger_id' => 2,
        'created_at' => $now,
        'updated_at' => $now,
    ],

    [
        'name' => 'Flight',
        'type' => TransactionType::Debit->value,
        'ledger_id' => 2,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'name' => 'Hotel',
        'type' => TransactionType::Debit->value,
        'ledger_id' => 1,
         'created_at' => $now,
        'updated_at' => $now,
    ]
]);


    }
}