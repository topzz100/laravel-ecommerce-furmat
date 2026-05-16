<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
        // return [
        //     //

        //     'status' => 'pending|shipped|shipped',
        //     //'price' => 'required|numeric',
        //     'notes' => 'nullable|string',
        //     'delivery_address' => 'nullable|string',
        //     'phone' => 'required|integer',
        //     'payment_method' => 'required|string',


            
        // ];
         return [
            'delivery_address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:paystack,cash_on_delivery'],
            'notes' => ['nullable', 'string'],
        ];

        
    }

      public function messages(): array
    {
        return [
            'delivery_address.required' => 'Delivery address is required',
            'phone.required' => 'Phone number is required',
            'payment_method.required' => 'Payment method is required',
            'payment_method.in' => 'Invalid payment method selected',
        ];
    }
}
