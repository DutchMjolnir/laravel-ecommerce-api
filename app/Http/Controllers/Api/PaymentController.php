<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
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
            'message' => 'La intgracion completa con Stripe quedo pendiente por el tiempo disponible.',
            'data' => [
                'order_id' => $order->id,
                'amount' => $order->total,
                'currency' => 'usd',
                'status' => 'pending',
            ],
        ], 501);
    }
}