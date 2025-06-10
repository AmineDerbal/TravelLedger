<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionType;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['name','balance'];

    public function updateAmount($amount, $type)
    {
        if (TransactionType::labelFromValue($type) == 'Credit') {
            $this->balance += $amount;
        } else {
            $this->balance -= $amount;
        }
        $this->save();
    }

    public function categories()
    {
        return $this->hasMany(LedgerCategory::class);
    }

}