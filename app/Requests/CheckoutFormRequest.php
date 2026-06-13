<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return [
            'nif' => 'required|digits:9',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_ref' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $type = $this->input('payment_type');
                    if ($type === 'Visa' && !preg_match('/^4[0-9]{15}$/', $value)) {
                        $fail('Visa references must have exactly 16 digits and start with 4.');
                    } elseif ($type === 'PayPal' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('PayPal reference must be a valid email address.');
                    } elseif ($type === 'MB WAY' && !preg_match('/^9[0-9]{8}$/', $value)) {
                        $fail('MB WAY references must have exactly 9 digits and start with 9.');
                    }
                },
            ],
        ];
    }
}
