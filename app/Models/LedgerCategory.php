<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;

class LedgerCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'type',
        'ledger_id',
    ];

  

    public function ledger() {
        return $this->belongsTo(Ledger::class);
    }

    public function getTypeAttribute($value)
{
    return [
        'value' => $value,
        'label' => TransactionType::labelFromValue($value),
    ];
}
    
}