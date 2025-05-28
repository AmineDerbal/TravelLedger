<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransactionsWithBalanceExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $transactions;
    protected $balance;
    
   public function __construct(array $transactions, array $balance)
    {
        $this->transactions = $transactions;
        $this->balance = $balance;
    }

      public function headings(): array
    {
        return [
            ['Transactions'], // Title row (merged)
            [
                 'User', 'Ledger', 'Type',
                'Category', 'Amount', 'Date', 'Description'
            ]
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->transactions as $row) {
            $rows[] = [
                $row['user']['name'],
                $row['ledger']['name'],
                $row['type']['label'],
                $row['category']['label'],
                number_format($row['amount'], 2),
                $row['date'],
                $row['description'],
            ];
        }

        // Add empty row + balance summary
        $rows[] = [];
        $rows[] = ['Summary'];
        $rows[] = ['Total Credit', $this->balance['totalCredit']];
        $rows[] = ['Total Debit', $this->balance['totalDebit']];
        $rows[] = ['Balance', $this->balance['totalBalance']];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header formatting
                $sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Auto table formatting
                $lastDataRow = 2 + count($this->transactions);
                $sheet->getStyle("A3:G{$lastDataRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                // Bold for summary
                $summaryStart = $lastDataRow + 1;
                $sheet->getStyle("A{$summaryStart}:B" . ($summaryStart + 3))->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Merge title row
                $sheet->mergeCells('A1:g1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);
            }
        ];
    }

}