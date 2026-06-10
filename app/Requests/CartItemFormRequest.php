<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color_code' => 'required|exists:colors,code',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }
}
