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

        $rows = [
            [
                'name' => 'Income',
              'type' => TransactionType::Credit->value,

            ],
            [
                'name' => 'Profit',
              'type' => TransactionType::Credit->value,

            ],
            [
                'name' => 'Expense',
              'type' => TransactionType::Debit->value,

            ],
            [
                'name' => 'Remboursement',
              'type' => TransactionType::Debit->value,

            ],

        ];

        foreach ($rows as $row) {
            LedgerCategory::firstOrCreate($row);
        }


    }
}
