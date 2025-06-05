<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ledger;

class LedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                'name' => 'Office',

            ],
            [
                'name' => 'RTW',
            ],
        ];
        foreach ($rows as $row) {
            Ledger::create($row);
        }
    }
}
