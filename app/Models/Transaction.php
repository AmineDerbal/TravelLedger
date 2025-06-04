<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ledger_category_id',
        'amount',
        'profit',
        'date',
        'description',
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ledger() {
        return $this->belongsTo(Ledger::class);
    }
 
}