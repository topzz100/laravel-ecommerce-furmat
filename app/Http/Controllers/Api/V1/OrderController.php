<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Order\CheckoutRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController
{
    //
    public function checkout(CheckoutRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {

            $user = Auth::user();

            // Get user's cart
            $cart = Cart::with('items.product')
                ->where('user_id', $user->id)
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                // abort(400, 'Cart is empty');
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' =>  ['cart' => 'Cart is empty']
                ], 400);
            }

            $total = 0;

            // Create order (initially with 0 total)
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0,
                'delivery_address' => $data['delivery_address'],
                'phone' => $data['phone'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {

                $product = $item->product;

                // Safety checks
                if (!$product) {
                    abort(400, 'A product in your cart is no longer available');
                }

                if ($product->stock < $item->quantity) {
                    abort(400, "{$product->name} is out of stock");
                }

                $price = $product->price;
                $lineTotal = $price * $item->quantity;

                $total += $lineTotal;

                // Create order item (snapshot)
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $item->quantity,
                ]);

                // Reduce stock
                $product->decrement('stock', $item->quantity);
            }

            // Update total after loop
            $order->update([
                'total_amount' => $total
            ]);

            // Clear cart (do NOT delete cart itself)
            $cart->items()->delete();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => [
                    'order_id' => $order->id,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                ]
            ], 201);
        });
    }

     public function index(Request $request)
    {
        $orders = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => OrderResource::collection($orders),
        ]);
    }
}
