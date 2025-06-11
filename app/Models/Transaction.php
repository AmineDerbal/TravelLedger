<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionType;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ledger_id',
        'ledger_category_id',
        'type',
        'amount',
        'profit',
        'date',
        'description',
    ];




    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ledgerCategory() {
        return $this->belongsTo(LedgerCategory::class);
    }

    public function ledger() {
        return $this->belongsTo(Ledger::class);
    }


  

}