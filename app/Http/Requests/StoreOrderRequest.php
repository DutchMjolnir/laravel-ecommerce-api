<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Debe agregar al menos un producto a la orden.',
            'items.array' => 'Los productos deben enviarse como una lista.',
            'items.min' => 'Debe agregar al menos un producto a la orden.',

            'items.*.product_id.required' => 'El ID del producto es obligatorio.',
            'items.*.product_id.integer' => 'El ID del producto debe ser un numero entero.',
            'items.*.product_id.distinct' => 'No puede repetir el mismo producto en la orden.',
            'items.*.product_id.exists' => 'Uno de los productos seleccionados no existe.',

            'items.*.quantity.required' => 'La cantidad del producto es obligatoria.',
            'items.*.quantity.integer' => 'La cantidad debe ser un numero entero.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
        ];
    }
}
