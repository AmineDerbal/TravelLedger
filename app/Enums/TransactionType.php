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
}