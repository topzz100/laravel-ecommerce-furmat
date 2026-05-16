<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController
{
    //
    public function addToCart(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        $item = $cart->items()
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->quantity ?? 1);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json(['message' => 'Added to cart']);
    }

    public function getCart(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->first();

        return response()->json(['data' => $cart]);
    }
    public function removeItem($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item removed']);
    }
}
