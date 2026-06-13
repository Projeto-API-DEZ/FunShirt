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
            'unit_price_catalog' => 'required|numeric|min:0',
            'unit_price_own' => 'required|numeric|min:0',
            'qty_discount' => 'required|integer|min:1',
            'unit_price_catalog_discount' => 'required|numeric|min:0|lte:unit_price_catalog',
            'unit_price_own_discount' => 'required|numeric|min:0|lte:unit_price_own',
        ];
    }
}
