<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DebtStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'string'],
        ];
    }
}