<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\TransactionType;

class Ledger extends Model
{
    use HasFactory;

    public function updateAmount($amount, $type) {
        if(TransactionType::labelFromValue($type) == 'Credit') {
            $this->amount += $amount;
        } else {
            $this->amount -= $amount;
        }
        $this->save();
    } 
}