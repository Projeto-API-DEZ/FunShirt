<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TshirtImageFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPost = $this->isMethod('post');

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'image_file' => ($isPost ? 'required' : 'nullable') . '|image|mimes:png|max:4096',
        ];
    }
}
