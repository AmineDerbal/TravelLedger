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

    public static function labelFromValue(int $value): string
{
    return self::from($value)->label();
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

    // For validation
    public static function valueList(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}