<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\LedgerCategory;

abstract class BaseTransactionRequest extends FormRequest
{
    protected function baseRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ledger_category_id' => 'required|exists:ledger_categories,id',
            'amount' => 'required|numeric|gte:0',
            'date' => 'required|date|date_format:Y-m-d',
            'description' => 'required|string|max:80',
            'profit' => 'nullable|numeric|gte:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {

        $validator->after(function ($validator) {
            $profit = $this->input('profit');
            $ledgerCategoryId = $this->input('ledger_category_id');

            if ($profit !== null && $ledgerCategoryId) {
                $ledgerCategory = LedgerCategory::with('ledger')->find($ledgerCategoryId);
                if (!$ledgerCategory || $ledgerCategory->type['value'] !== 2 || $ledgerCategory->ledger->id !== 2) {
                    $validator->errors()->add(
                        'ledger_category_id',
                        'When profit is set, the category must be of type Debit and belong to the RTW ledger.'
                    );

                }
            }
        });
    }

}
