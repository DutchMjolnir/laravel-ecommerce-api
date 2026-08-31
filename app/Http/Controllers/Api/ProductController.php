<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: '/products',
        summary: 'Listar productos disponibles',
        tags: ['Productos'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Productos obtenidos correctamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Productos obtenidos correctamente.'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                ref: '#/components/schemas/Product'
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $products = Product::where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Productos obtenidos correctamente.',
            'data' => $products,
        ]);
    }

    #[OA\Get(
        path: '/products/{product}',
        summary: 'Consultar un producto por ID',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(
                name: 'product',
                description: 'ID del producto',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Producto obtenido correctamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Producto obtenido correctamente.'
                        ),
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/Product'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Producto no encontrado'
            ),
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto obtenido correctamente.',
            'data' => $product,
        ]);
    }

    #[OA\Post(
        path: '/products',
        summary: 'Crear un producto',
        tags: ['Productos'],
        security: [
            ['sanctum' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'name',
                    'price',
                    'stock',
                ],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Soporte para laptop'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        nullable: true,
                        example: 'Soporte ajustable para escritorio.'
                    ),
                    new OA\Property(
                        property: 'price',
                        type: 'number',
                        format: 'float',
                        example: 29.99
                    ),
                    new OA\Property(
                        property: 'stock',
                        type: 'integer',
                        example: 10
                    ),
                    new OA\Property(
                        property: 'is_active',
                        type: 'boolean',
                        example: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Producto creado correctamente'
            ),
            new OA\Response(
                response: 401,
                description: 'Usuario no autenticado'
            ),
            new OA\Response(
                response: 422,
                description: 'Error de validacion'
            ),
        ]
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        $product = Product::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado correctamente.',
            'data' => $product,
        ], 201);
    }

    #[OA\Put(
        path: '/products/{product}',
        summary: 'Actualizar un producto',
        tags: ['Productos'],
        security: [
            ['sanctum' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'product',
                description: 'ID del producto',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Teclado mecanico actualizado'
                    ),
                    new OA\Property(
                        property: 'description',
                        type: 'string',
                        nullable: true,
                        example: 'Descripcion actualizada del producto.'
                    ),
                    new OA\Property(
                        property: 'price',
                        type: 'number',
                        format: 'float',
                        example: 70.99
                    ),
                    new OA\Property(
                        property: 'stock',
                        type: 'integer',
                        example: 20
                    ),
                    new OA\Property(
                        property: 'is_active',
                        type: 'boolean',
                        example: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Producto actualizado correctamente'
            ),
            new OA\Response(
                response: 401,
                description: 'Usuario no autenticado'
            ),
            new OA\Response(
                response: 404,
                description: 'Producto no encontrado'
            ),
            new OA\Response(
                response: 422,
                description: 'Error de validacion'
            ),
        ]
    )]
    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente.',
            'data' => $product->fresh(),
        ]);
    }

    #[OA\Delete(
        path: '/products/{product}',
        summary: 'Eliminar un producto',
        tags: ['Productos'],
        security: [
            ['sanctum' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'product',
                description: 'ID del producto',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Producto eliminado correctamente'
            ),
            new OA\Response(
                response: 401,
                description: 'Usuario no autenticado'
            ),
            new OA\Response(
                response: 404,
                description: 'Producto no encontrado'
            ),
        ]
    )]
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado correctamente.',
        ]);
    }
}