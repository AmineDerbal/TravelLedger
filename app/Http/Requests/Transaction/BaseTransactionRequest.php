<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\TransactionCategory;
use App\Enums\TransactionType;

abstract class BaseTransactionRequest extends FormRequest
{
    protected function categoryValidationRule(): array
    {
        return [
            'required',
            function ($attribute, $value, $fail) {
                $type = (int) $this->input('type');

                // Skip if type is missing or invalid
                if (!in_array($type, TransactionType::valueList())) {
                    return;
                }

                try {
                    $transactionType = TransactionType::from($type);
                    $validCategories = TransactionCategory::forType($transactionType);
                    $validCategoryValues = array_map(fn ($c) => $c->value, $validCategories);

                    if (!in_array((int) $value, $validCategoryValues)) {
                        $fail('The selected category is not valid for the chosen transaction type.');
                    }
                } catch (\ValueError $e) {
                    $fail('Invalid transaction type.');
                }
            },
        ];
    }

    protected function baseRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ledger_id' => 'required|exists:ledgers,id',
            'type' => ['required', Rule::in(TransactionType::valueList())],
            'category' => $this->categoryValidationRule(),
            'amount' => 'required|numeric|gte:0',
            'date' => 'required|date|date_format:Y-m-d',
            'description' => 'required|string|max:80',
        ];
    }
}
