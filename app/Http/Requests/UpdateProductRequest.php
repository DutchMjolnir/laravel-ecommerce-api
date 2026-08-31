<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0.01',
            ],
            'stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede tener mas de 255 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numerico.',
            'price.min' => 'El precio debe ser mayor que cero.',
            'stock.required' => 'La cantidad disponible es obligatoria.',
            'stock.integer' => 'La cantidad debe ser un numero entero.',
            'stock.min' => 'La cantidad no puede ser negativa.',
            'is_active.boolean' => 'El estado del producto debe ser verdadero o falso.',
        ];
    }
}
