<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaymentController extends Controller
{
    #[OA\Post(
        path: '/orders/{order}/payment',
        summary: 'Procesar el pago de una orden',
        description: 'La estructura inicial de Stripe fue agregada, pero el procesamiento completo del pago quedo pendiente.',
        tags: ['Pagos'],
        security: [
            ['sanctum' => []],
        ],
        parameters: [
            new OA\Parameter(
                name: 'order',
                description: 'ID de la orden que se desea pagar',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'payment_method_id',
                        type: 'string',
                        nullable: true,
                        example: 'pm_test_pending'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 501,
                description: 'La integracion completa con Stripe quedo pendiente'
            ),
            new OA\Response(
                response: 401,
                description: 'Usuario no autenticado'
            ),
            new OA\Response(
                response: 403,
                description: 'La orden pertenece a otro usuario'
            ),
            new OA\Response(
                response: 404,
                description: 'Orden no encontrada'
            ),
            new OA\Response(
                response: 422,
                description: 'La orden ya fue procesada'
            ),
        ]
    )]
    public function process(
        ProcessPaymentRequest $request,
        Order $order
    ): JsonResponse {
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para pagar esta orden.',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Esta orden ya fue procesada.',
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => 'La integracion completa con Stripe quedo pendiente por el tiempo disponible.',
            'data' => [
                'order_id' => $order->id,
                'amount' => $order->total,
                'currency' => 'usd',
                'status' => 'pending',
            ],
        ], 501);
    }
}