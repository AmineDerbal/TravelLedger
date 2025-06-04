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

     protected $casts = [
        'type' => TransactionType::class,
    ];

    
    public function getTypeAttribute($value)
{
    $enum = TransactionType::from($value);
    
    return [
        'value' => $enum->value,
        'label' => $enum->label(),
    ];
}

    public function ledger() {
        return $this->belongsTo(Ledger::class);
    }
    
}