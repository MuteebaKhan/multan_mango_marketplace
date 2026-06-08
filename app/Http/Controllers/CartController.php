<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request, $productId)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to add items to your cart.');
    }

    $quantity = $request->input('quantity_kg', 5);

    $existingCartItem = CartItem::where('user_id', Auth::id())
                                ->where('product_id', $productId)
                                ->first();

    if ($existingCartItem) {
        $existingCartItem->increment('quantity_kg', $quantity);
    } else {
        CartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'quantity_kg' => $quantity
        ]);
    }

    return redirect()->back()->with('success', '✨ Mango variety added to your royal cart successfully!');
}
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        $totalAmount = $cartItems->sum(function($item) {
            return $item->product->price_per_kg * $item->quantity_kg;
        });

        return view('cart.index', compact('cartItems', 'totalAmount'));
    }

    public function remove($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}