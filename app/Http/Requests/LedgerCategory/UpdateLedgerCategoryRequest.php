<?php

namespace App\Http\Requests\LedgerCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLedgerCategoryRequest extends BasicLedgerCategoryRequest
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
        return array_merge(
            [
            'id' => 'required|exists:ledger_categories,id'],
            $this->baseRules()
        );


    }
}
