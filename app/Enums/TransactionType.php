<?php

namespace App\Enums;

enum TransactionType: int
{
    case Credit = 1;
    case Debit = 2;


    public function label(): string
    {
        return match ($this) {
            self::Credit => 'Credit',
            self::Debit => 'Debit',
        };
    }
    public static function values(): array
    {
        return [
          [
            'value' => self::Credit,
            'label' => self::Credit->label(),
          ],
          [
            'value' => self::Debit,
            'label' => self::Debit->label(),
          ],
        ];
    }
}
