<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\LedgerCategory;
use App\Enums\TransactionType;

abstract class BaseTransactionRequest extends FormRequest
{

 
    protected function baseRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ledger_id' => 'required|exists:ledgers,id',
            'ledger_category_id' => 'required|exists:ledger_categories,id',
            'type' => ['required', Rule::in(TransactionType::valueList())],
            'amount' => 'required|numeric|gte:0',
            'date' => 'required|date|date_format:Y-m-d',
            'description' => 'required|string|max:80',
            'profit' => 'nullable|numeric|gte:0',
        ];
    }

    public function withValidator(Validator $validator): void
{
    if ($validator->errors()->isNotEmpty()) {
        return;
    }

    $validator->after(function ($validator) {
        $profit = $this->input('profit');
        $ledgerCategoryId = $this->input('ledger_category_id');
        $type = (int)$this->input('type') ;
        $ledgerId = (int)$this->input('ledger_id');

        $ledgerCategory = LedgerCategory::find($ledgerCategoryId);

        if($ledgerCategory->type['value'] !== $type || $ledgerCategory->ledger_id !== $ledgerId) {
            $validator->errors()->add(
                'ledger_category_id',
                'The ledger category type and ledger must match the transaction type and ledger.'
            );
        }

        if ($ledgerCategoryId) {
     
                $isDebitAndRTW = 
                    $type === 2 &&
                    $ledgerId === 2;

                if ($profit !== null && !$isDebitAndRTW) {
                    $validator->errors()->add(
                        'profit',
                        'When profit is set, the category must be of type Debit and belong to the RTW ledger.'
                    );
                }

                if ($profit === null && $isDebitAndRTW) {
                    $validator->errors()->add(
                        'profit',
                        'Profit is required when the category is of type Debit and belongs to the RTW ledger.'
                    );
                
            }
        }
    });
}

}