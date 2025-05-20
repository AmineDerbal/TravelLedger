<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\TransactionCategory;
use App\Enums\TransactionType;

class storeTransactionRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'ledger_id' => 'required|exists:ledgers,id',
           'type' => ['required', Rule::in(TransactionType::valueList())],
   'category' => [
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
                    $validCategoryValues = array_map(fn($c) => $c->value, $validCategories);

                    if (!in_array((int) $value, $validCategoryValues)) {
                        $fail('The selected category is not valid for the chosen transaction type.');
                    }
                } catch (\ValueError $e) {
                    $fail('Invalid transaction type.');
                }
            },
        ],
            'amount' => 'required|numeric|gte:0',
            'date' => 'required|date|date_format:Y-m-d',
            'description' => 'required|string|max:80',
        ];
    }
}