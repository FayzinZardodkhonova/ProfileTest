<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(): JsonResponse
    {
        $ordersWithProducts = Order::with('products')->get();
        return response()->json($ordersWithProducts);
    }

    public function show($id): JsonResponse
    {
        $orderWithProducts = Order::with('products')->find($id);
        return response()->json($orderWithProducts);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required',
            'shipping_address' => 'required',
            'order_status' => 'required',
            'total_price' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.attributes' => 'array',
            'products.*.attributes.*.name' => 'string',
            'products.*.attributes.*.value' => 'string',
        ]);

        $order = $this->orderRepository->create($data);


        foreach ($data['products'] as $item) {
            $order->products()->attach($item['product_id'], ['quantity' => $item['quantity']]);

            if (isset($item['attributes'])) {
                $product = Product::find($item['product_id']);
                $product->attributes()->createMany($item['attributes']);
            }
        }
        $orderWithProducts = Order::with('products')->find($order->id);

        return response()->json($orderWithProducts, 201);
    }





    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required',
            'shipping_address' => 'required',
            'order_status' => 'required',
            'total_price' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.attributes' => 'array',
            'products.*.attributes.*.name' => 'string',
            'products.*.attributes.*.value' => 'string',
        ]);

        $order = $this->orderRepository->update($id, $data);

        $order->products()->detach();

        foreach ($data['products'] as $item) {
            $order->products()->attach($item['product_id'], ['quantity' => $item['quantity']]);
            if (isset($item['attributes'])) {
                $product = Product::find($item['product_id']);
                $product->attributes()->createMany($item['attributes']);
            }
        }

        $orderWithProducts = Order::with('products')->find($order->id);
        return response()->json($orderWithProducts, 200);
    }

    public function destroy($id): JsonResponse
    {
        $this->orderRepository->delete($id);
        return response()->json(null, 204);
    }

    public function getOrderStatus($id): JsonResponse
    {
        $orderStatus = $this->orderRepository->getOrderStatus($id);
        return response()->json(['status' => $orderStatus]);
    }
}
