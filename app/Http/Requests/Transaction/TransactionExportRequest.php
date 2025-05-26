<?php

namespace App\Http\Requests\Transaction;


use App\Enums\TransactionType;
use Illuminate\Validation\Rule;

class TransactionExportRequest extends BaseTransactionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     protected function prepareForValidation(): void
    {
        $balance = $this->input('balance');

        if (is_array($balance)) {
            $this->merge([
                'balance' => [
                    'totalCredit' => str_replace(',', '', $balance['totalCredit'] ?? 0),
                    'totalDebit' => str_replace(',', '', $balance['totalDebit'] ?? 0),
                    'totalBalance' => str_replace(',', '', $balance['totalBalance'] ?? 0),
                ]
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transactions' => 'required|array',
            'transactions.*.id' => 'required|exists:transactions,id',
            'transactions.*.user.id' => 'required|exists:users,id',
            'transactions.*.user.name' => 'required|exists:users,name',
            'transactions.*.ledger.id' => 'required|exists:ledgers,id',
            'transactions.*.ledger.name' => 'required|exists:ledgers,name',
            'transactions.*.type.value' => ['required', Rule::in(TransactionType::valueList())],
            'transactions.*.type.label' => 'required',
            'transactions.*.category.value' => $this->categoryValidationRule(),
            'transactions.*.category.label' => 'required',
            'transactions.*.amount' => 'required|numeric|gte:0',
            'transactions.*.date' => 'required|date|date_format:Y-m-d',
            'transactions.*.description' => 'required|string|max:80',

            'balance' => 'required|array',
            'balance.totalCredit' => 'required|numeric|gte:0',
            'balance.totalDebit' => 'required|numeric|gte:0',
            'balance.totalBalance' => 'required|numeric',
            
        ];
    }
}