<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'user_type' => ['required', Rule::in(['C', 'F', 'A'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'nif' => ['nullable', 'digits:9'],
            'address' => ['nullable', 'string', 'max:1000'],
            'default_payment_type' => ['nullable', Rule::in(['Visa', 'PayPal', 'MB WAY'])],
            'default_payment_ref' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already in use.',
            'gender.in' => 'The selected gender is invalid.',
            'user_type.in' => 'The selected role is invalid.',
            'password.confirmed' => 'The password confirmation does not match.',
            'photo_file.mimes' => 'The profile photo must be a JPG, JPEG, PNG, or WEBP image.',
            'nif.digits' => 'The NIF must contain exactly 9 digits.',
            'default_payment_type.in' => 'The selected payment method is invalid.',
        ];
    }
}
