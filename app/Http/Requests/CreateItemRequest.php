<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:categories,name|max:255',
            'description' => 'required|min:3',
            'price' => 'required|decimal:0,2|gte:0',
            'quantity' => 'required|numeric|gte:0',
            'category_id' => 'required|array|min:1',
            'category_id.*' => 'required|numeric|exists:categories,id'
        ];
    }
}
