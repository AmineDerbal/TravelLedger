<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TransactionType;

class BasicTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,],
            'ledger' => [
                'id' => $this->ledger->id,
                'name' => $this->ledger->name,],
            'type' => [
                'value' => $this->type,
                'label' => TransactionType::labelFromValue($this->type)
            ],
            'category' => [
                'id' => $this->ledgerCategory->id,
                'name' => $this->ledgerCategory->name,
            ],
            'amount' => $this->amount,
            'profit' => $this->profit,
            'date' => $this->date,
            'description' => $this->description,
            'is_active' => $this->is_active == 1 ? true : false,
        ];
    }
}
