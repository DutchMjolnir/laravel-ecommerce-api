<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method_id' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_id.string' =>
                'El metodo de pago debe ser texto.',

            'payment_method_id.max' =>
                'El metodo de pago no puede superar los 255 caracteres.',
        ];
    }
}