<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionType;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['name','balance'];

    public function applyTransaction($amount, $type)
    {
        if (TransactionType::labelFromValue($type) == 'Credit') {
            $this->balance = $this->addToBalance($this->balance, $amount);
        } else {
            $this->balance = $this->subtractFromBalance($this->balance, $amount);
        }
        $this->save();
    }

    public function revertTransaction($amount, $type)
    {

        if (TransactionType::labelFromValue($type) == 'Credit') {

            $this->balance = $this->subtractFromBalance($this->balance, $amount);
        } else {
            $this->balance = $this->addToBalance($this->balance, $amount);
        }
        $this->save();
    }

    public function updateTransactionEffect($amount, $type)
    {
        $this->revertTransaction($amount, $type);
        $this->applyTransaction($amount, $type);

    }

    public function categories()
    {
        return $this->hasMany(LedgerCategory::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    private function addToBalance($balance, $amount)
    {
        return $balance + $amount;
    }

    private function subtractFromBalance($balance, $amount)
    {
        return $balance - $amount;
    }



}
