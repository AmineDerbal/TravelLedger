<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ledger_id',
        'ledger_category_id',
        'linked_transaction_id',
        'type',
        'amount',
        'date',
        'description',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ledgerCategory()
    {
        return $this->belongsTo(LedgerCategory::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    public function linkedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'linked_transaction_id');
    }

    public function reverseLinkedTransaction()
    {
        return $this->hasOne(Transaction::class, 'linked_transaction_id');
    }


}
