<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'user_type' => ['required', Rule::in(['C', 'F', 'A'])],
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
            'photo_file.mimes' => 'The profile photo must be a JPG, JPEG, PNG, or WEBP image.',
            'nif.digits' => 'The NIF must contain exactly 9 digits.',
            'default_payment_type.in' => 'The selected payment method is invalid.',
        ];
    }
}
