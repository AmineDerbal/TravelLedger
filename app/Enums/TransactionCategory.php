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

}