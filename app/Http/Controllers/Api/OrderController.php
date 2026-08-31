<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with([
            'items.product',
            'payments',
        ])
            ->where('user_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Historial de compras obtenido correctamente.',
            'data' => $orders,
        ]);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para consultar esta orden.',
            ], 403);
        }

        $order->load([
            'items.product',
            'payments',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Orden obtenida correctamente.',
            'data' => $order,
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $items = $request->validated()['items'];

        try {
            $order = DB::transaction(function () use ($request, $items) {
                $products = [];

                foreach ($items as $item) {
                    $product = Product::lockForUpdate()
                        ->find($item['product_id']);

                    if (!$product) {
                        throw new \Exception(
                            'Uno de los productos seleccionados no existe.'
                        );
                    }

                    if (!$product->is_active) {
                        throw new \Exception(
                            "El producto {$product->name} no se encuentra disponible."
                        );
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception(
                            "No hay suficiente stock para el producto {$product->name}."
                        );
                    }

                    $products[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                    ];
                }

                $total = 0;

                foreach ($products as $item) {
                    $subtotal = round(
                        (float) $item['product']->price * $item['quantity'],
                        2
                    );

                    $total += $subtotal;
                }

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total' => round($total, 2),
                    'status' => 'pending',
                ]);

                foreach ($products as $item) {
                    $product = $item['product'];
                    $quantity = $item['quantity'];

                    $subtotal = round(
                        (float) $product->price * $quantity,
                        2
                    );

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('stock', $quantity);
                }

                return $order->load([
                    'items.product',
                    'payments',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Orden creada correctamente.',
                'data' => $order,
            ], 201);
        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}