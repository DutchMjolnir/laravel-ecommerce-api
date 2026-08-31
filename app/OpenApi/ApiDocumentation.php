<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API de E-commerce Segura',
    description: 'API REST desarrollada con Laravel 12 para gestionar usuarios, productos, ordenes y pagos.',
    contact: new OA\Contact(
        name: 'Luis Monge'
    )
)]
#[OA\Server(
    url: '/api',
    description: 'Servidor principal de la API'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Token generado mediante Laravel Sanctum'
)]
#[OA\Schema(
    schema: 'Product',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
            example: 1
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
            example: 'Teclado mecanico'
        ),
        new OA\Property(
            property: 'description',
            type: 'string',
            nullable: true,
            example: 'Teclado mecanico con iluminacion RGB.'
        ),
        new OA\Property(
            property: 'price',
            type: 'number',
            format: 'float',
            example: 65.99
        ),
        new OA\Property(
            property: 'stock',
            type: 'integer',
            example: 15
        ),
        new OA\Property(
            property: 'is_active',
            type: 'boolean',
            example: true
        ),
    ]
)]
#[OA\Schema(
    schema: 'OrderItemInput',
    type: 'object',
    required: ['product_id', 'quantity'],
    properties: [
        new OA\Property(
            property: 'product_id',
            type: 'integer',
            example: 1
        ),
        new OA\Property(
            property: 'quantity',
            type: 'integer',
            example: 2
        ),
    ]
)]
#[OA\Schema(
    schema: 'ApiError',
    type: 'object',
    properties: [
        new OA\Property(
            property: 'success',
            type: 'boolean',
            example: false
        ),
        new OA\Property(
            property: 'message',
            type: 'string',
            example: 'Ocurrio un error al procesar la solicitud.'
        ),
    ]
)]
class ApiDocumentation
{
}