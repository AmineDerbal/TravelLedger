<?php

namespace App\Enums;

use App\Enums\TransactionType;


enum TransactionCategory: int
{
case Flight = 1;
case Hotel = 2;
case Visa = 3;
case Insurance = 4;
case Rechargement = 5;
case Refund = 6;

public static function forType(TransactionType $type): array
{
  return match ($type) {
    TransactionType::Credit => [
      self::Rechargement,
      self::Refund,
    ],
    TransactionType::Debit => [
      self::Flight,
      self::Hotel,
      self::Visa,
      self::Insurance,
    ],
  };
}

public function label(): string
{
  return match ($this) {
    self::Flight => 'Flight',
    self::Hotel => 'Hotel',
    self::Visa => 'Visa',
    self::Insurance => 'Insurance',
    self::Rechargement => 'Rechargement',
    self::Refund => 'Refund',
  };

}

public static function values(): array
{
  return [
        [
            'value' => self::Flight,
            'label' => self::Flight->label(),
            'type' => TransactionType::Debit
        ],
        [
            'value' => self::Hotel,
            'label' => self::Hotel->label(),
            'type' => TransactionType::Debit
        ],
        [
            'value' => self::Visa,
            'label' => self::Visa->label(),
            'type' => TransactionType::Debit
        ],
        [
            'value' => self::Insurance,
            'label' => self::Insurance->label(),
            'type' => TransactionType::Debit
        ],
        [
            'value' => self::Rechargement,
            'label' => self::Rechargement->label(),
            'type' => TransactionType::Credit
        ],
        [
            'value' => self::Refund,
            'label' => self::Refund->label(),
            'type' => TransactionType::Credit
        ],
    ];
}

}