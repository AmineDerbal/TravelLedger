<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Enums\TransactionType;
use App\Enums\TransactionCategory;

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
                'value' => $this->ledgerCategory->id,
                'label' => $this->ledgerCategory->name,
            ],
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description
        ];
    }
}