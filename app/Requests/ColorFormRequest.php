<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ColorFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $currentCode = $this->route('color')?->code;

        return [
            'code' => [
                'required',
                'string',
                'max:9',
                'regex:/^#[0-9A-Fa-f]{6}([0-9A-Fa-f]{2})?$/',
                Rule::unique('colors', 'code')->ignore($currentCode, 'code'),
            ],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $code = strtoupper(trim((string) $this->input('code')));

        if ($code !== '' && ! str_starts_with($code, '#')) {
            $code = '#' . $code;
        }

        $this->merge([
            'code' => $code,
        ]);
    }

    public function messages(): array
    {
        return [
            'code.regex' => 'Use a color code like #A30B9E or #A30B9EFF.',
        ];
    }
}
