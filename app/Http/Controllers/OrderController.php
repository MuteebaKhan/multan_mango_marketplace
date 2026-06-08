<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request, $productId = null)
    {
        $platform_fee = 300.00; // Fixed Platform Fee
        $shipping_fee = 0.00;   // Free Cargo by default

        if ($request->has('from_cart') || !$productId) {
            $cartItems = CartItem::with(['product.farm', 'product.category'])
                                 ->where('user_id', Auth::id())
                                 ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('products.index')->with('error', 'Your mango basket is empty!');
            }

            $subtotal = $cartItems->sum(function($item) {
                return $item->product->price_per_kg * $item->quantity_kg;
            });

            $totalAmount = $subtotal + $platform_fee + $shipping_fee;

            return view('orders.checkout', compact('cartItems', 'subtotal', 'platform_fee', 'shipping_fee', 'totalAmount'));
        }

        // Single Product Instant Checkout
        $product = Product::with(['farm', 'category'])->findOrFail($productId);
        $quantity = $request->input('quantity', 5); 
        $subtotal = $product->price_per_kg * $quantity;
        $totalAmount = $subtotal + $platform_fee + $shipping_fee;

        return view('orders.checkout', compact('product', 'quantity', 'subtotal', 'platform_fee', 'shipping_fee', 'totalAmount'));
    }

    public function store(Request $request)
    {
        // Custom validations for new fields (including dynamic check for JazzCash)
        $request->validate([
            'phone_number'     => 'required|string|max:20',
            'shipping_address' => 'required|string|min:10',
            'payment_method'   => 'required|string|in:COD,Card,JazzCash',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'coupon_code'      => 'nullable|string',
            'transaction_id'   => $request->payment_method === 'JazzCash' ? 'required|string|min:6|max:50' : 'nullable|string',
        ], [
            'transaction_id.required' => 'JazzCash payment ke liye 12-digit ki Transaction ID (TID) likhna lazmi hai!'
        ]);

        try {
            $orderId = DB::transaction(function () use ($request) {
                $platform_fee = 300.00;
                $shipping_fee = 0.00;
                $discount_amount = 0.00;
                $coupon_id = null;

                // 1. Calculate Items Price (Subtotal)
                if ($request->has('from_cart') || !$request->product_id) {
                    $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
                    if ($cartItems->isEmpty()) {
                        throw new \Exception('Basket is empty.');
                    }
                    $subtotal = $cartItems->sum(function($item) {
                        return $item->product->price_per_kg * $item->quantity_kg;
                    });
                } else {
                    $product = Product::findOrFail($request->product_id);
                    $quantity = $request->input('quantity_kg', 5);
                    $subtotal = $product->price_per_kg * $quantity;
                }

                // 2. Check Coupon if Applied
                if ($request->filled('coupon_code')) {
                    $coupon = Coupon::where('code', $request->coupon_code)
                                    ->where('is_active', true)
                                    ->where('expiry_date', '>=', now()->toDateString())
                                    ->where('min_order_amount', '<=', $subtotal)
                                    ->first();

                    if ($coupon) {
                        $coupon_id = $coupon->id;
                        if ($coupon->type === 'percentage') {
                            $discount_amount = ($subtotal * $coupon->value) / 100;
                        } else {
                            $discount_amount = $coupon->value;
                        }
                    }
                }

                // Total Calculation logic matches layout rules
                $total_amount = ($subtotal + $platform_fee + $shipping_fee) - $discount_amount;

                // 3. Create Record in Orders Table
                $order = Order::create([
                    'customer_id'      => Auth::id(),
                    'coupon_id'        => $coupon_id,
                    'subtotal'         => $subtotal,
                    'shipping_fee'     => $shipping_fee,
                    'platform_fee'     => $platform_fee,
                    'discount_amount'  => $discount_amount,
                    'total_amount'     => $total_amount,
                    'shipping_address' => $request->shipping_address,
                    'latitude'         => $request->latitude,
                    'longitude'        => $request->longitude,
                    'status'           => 'pending', 
                ]);

                // 4. Save Items and Adjust Inventory Stock
                if ($request->has('from_cart') || !$request->product_id) {
                    foreach ($cartItems as $item) {
                        if ($item->product->stock_quantity_kg < $item->quantity_kg) {
                            throw new \Exception('Afsoos! ' . $item->product->name . ' ka itna stock bacha nahi hai.');
                        }

                        OrderItem::create([
                            'order_id'           => $order->id,
                            'product_id'         => $item->product_id,
                            'quantity_kg'        => $item->quantity_kg,
                            'price_at_purchase'  => $item->product->price_per_kg,
                        ]);

                        $item->product->decrement('stock_quantity_kg', $item->quantity_kg);
                    }
                    CartItem::where('user_id', Auth::id())->delete();
                } else {
                    if ($product->stock_quantity_kg < $quantity) {
                        throw new \Exception('Afsoos! Bagh me itna stock maujood nahi hai.');
                    }

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_id'         => $product->id,
                        'quantity_kg'        => $quantity,
                        'price_at_purchase'  => $product->price_per_kg,
                    ]);

                    $product->decrement('stock_quantity_kg', $quantity);
                }

                // 5. Secure Payment Lifecycle Logic
                $paymentStatus = ($request->payment_method === 'Card') ? 'completed' : 'pending';
                
                // TID extraction check
                $txnId = null;
                if ($request->payment_method === 'JazzCash') {
                    $txnId = trim($request->transaction_id);
                } elseif ($request->payment_method === 'Card') {
                    $txnId = 'CRD-' . strtoupper(uniqid());
                }

                Payment::create([
                    'order_id'       => $order->id,
                    'payment_method' => $request->payment_method,
                    'amount'         => $total_amount,
                    'status'         => $paymentStatus,
                    'transaction_id' => $txnId,
                ]);

                return $order->id;
            });

            return redirect()->route('dashboard')->with('success', '👑 Royal Mango Order Placed Successfully! Your batch is being prepared.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // 3. Dynamic AJAX API for Coupon Validation (No Page Reload)
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        $coupon = Coupon::where('code', $request->code)
                        ->where('is_active', true)
                        ->where('expiry_date', '>=', now()->toDateString())
                        ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code!']);
        }

        if ($request->subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'Minimum order amount for this coupon is Rs. ' . number_format($coupon->min_order_amount)
            ]);
        }

        // Calculate real discount breakdown dynamic response
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = ($request->subtotal * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount_amount' => $discount,
            'message' => 'Coupon applied successfully!'
        ]);
    }
}