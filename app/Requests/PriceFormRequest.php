<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'catalog_price' => 'required|numeric|min:0',
            'custom_price' => 'required|numeric|min:0',
            'qty_discount_threshold' => 'required|integer|min:1',
            'catalog_discount_price' => 'required|numeric|min:0|lte:catalog_price',
            'custom_discount_price' => 'required|numeric|min:0|lte:custom_price',
        ];
    }
}
