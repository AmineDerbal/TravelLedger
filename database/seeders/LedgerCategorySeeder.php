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
            [
                 'name' => 'Rechargement',
              'type' => TransactionType::Credit->value,
              'ledger_id' => 2,
            ],
            [
               'name' => 'Refund',
              'type' => TransactionType::Credit->value,
              'ledger_id' => 2,
            ],
            [
                 'name' => 'Flight',
              'type' => TransactionType::Debit->value,
              'ledger_id' => 2,
            ],
            [
                   'name' => 'Hotel',
              'type' => TransactionType::Debit->value,
              'ledger_id' => 2,
            ],
            [
                'name' => 'Visa',
              'type' => TransactionType::Debit->value,
              'ledger_id' => 2,
            ]
        ];

        foreach ($rows as $row) {
            LedgerCategory::create($row);
        }


    }
}
