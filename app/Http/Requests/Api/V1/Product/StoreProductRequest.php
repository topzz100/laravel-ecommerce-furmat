<?php

namespace App\Http\Requests\Api\V1\Product;

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
            //
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'material' => 'nullable|string',
            'color' => 'nullable|string',
            'dimension' => 'nullable|string',
            'stock' => 'required|integer',
            'featured' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',

            'images' => 'sometimes|array|min:1',
            'images.*' => 'required|string|url',

        ];
    }
}
