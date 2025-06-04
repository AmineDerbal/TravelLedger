<?php

namespace App\Http\Requests\LedgerCategory;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class basicLedgerCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function baseRules(): array
    {
       return [
        'ledger_id' => 'required|exists:ledgers,id',
        'type' => ['required', Rule::in(TransactionType::valueList())],
        'name' => [
            'required',
            'string',
            'max:20',
            Rule::unique('ledger_categories')
                ->where(function ($query) {
                    return $query->where('ledger_id', $this->ledger_id)
                                 ->where('type', $this->type);
                }),
        ],
    ];
    }
}