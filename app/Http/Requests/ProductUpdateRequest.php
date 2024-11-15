<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_path' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'stock_quantity' => ['required', 'integer'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }
}
