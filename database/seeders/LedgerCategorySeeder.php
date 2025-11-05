<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerCategory;
use App\Enums\TransactionType;

class LedgerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // office id 1
        // RTW id 2
        $rows = [
            [
                'name' => 'Income',
              'type' => TransactionType::Credit->value,
              'ledger_id' => 1,
            ],
            [
                'name' => 'Profit',
              'type' => TransactionType::Credit->value,
              'ledger_id' => 1,
            ],
            [
                'name' => 'Expense',
              'type' => TransactionType::Debit->value,
              'ledger_id' => 1,
            ],

        ];

        foreach ($rows as $row) {
            LedgerCategory::firstOrCreate($row);
        }


    }
}
