<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class getTransactionsByDateRangeRequest extends FormRequest
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
            'ledger_id'=> 'required|exists:ledgers,id',
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ];
    }
}