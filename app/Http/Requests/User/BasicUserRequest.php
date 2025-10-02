<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

abstract class BasicUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function baseRules()
    {
        return [
            'name' => 'required|string|max:255|unique:users,name',
             'email' => 'required|string|email|max:255|unique:users,email',
             'password' => 'required|string|min:8',
             'password_confirmation' => 'required|same:password',
             'role' => 'required|string|in:user,admin',
        ];
    }
}
