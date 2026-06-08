<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string|min:10',
            'price_per_kg' => 'required|numeric|min:0',
            'stock_quantity_kg' => 'required|numeric|min:0',
            'image'             => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'description'       => 'required|string|min:10',
            'is_active'         => 'nullable|boolean'     
        ];
    }
}
