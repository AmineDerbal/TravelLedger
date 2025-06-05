<?php

namespace App\Http\Resources\LedgerCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicLedgerCategoryResource extends JsonResource
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
            'ledger' => [
                'id' => $this->ledger->id,
                'name' => $this->ledger->name,
            ],
            'type' => $this->type,
            'name' => $this->name
        ];
    }
}
