<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'gender' => 'required|in:M,F',
            'photo_file' => 'nullable|image|max:4096',
        ];

        if ($user->isCustomer()) {
            $rules['nif'] = 'nullable|digits:9';
            $rules['address'] = 'nullable|string';
            $rules['default_payment_type'] = 'nullable|in:Visa,PayPal,MB WAY';
            $rules['default_payment_ref'] = 'nullable|string|max:255';
        }

        return $rules;
    }
}