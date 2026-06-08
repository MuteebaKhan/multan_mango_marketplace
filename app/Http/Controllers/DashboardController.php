<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Order;
use App\Models\Product;
use App\Models\Farm;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
    
             // Total Revenue: Sirf delivered batches ya completed orders ki collection sum karein
        $totalRevenue = Order::whereIn('status', ['Delivered', 'shipped', 'delivered'])->sum('total_amount') ?? 0;
        
        // Total Registered Mango Farms
        $activeGrowers = Farm::count() ?? 0;
        
        // Pending Payouts / Orders Amount: Jo abhi tak verification cycle me pending hain
        $pendingPayouts = Order::where('status', 'pending')->sum('total_amount') ?? 0;
        
        // Total Coupon Vouchers Utilized by Customers
        $couponUsage = Order::whereNotNull('coupon_id')->count() ?? 0;

        // Table Data: `with(['customer', 'payment'])` taake customer name aur JazzCash TID dono efficiently fetch ho sakein
        $pendingOrders = Order::has('customer')
            ->with(['customer', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

            $platformEarnings = DB::table('orders')
            ->where('status', '!=', 'pending')
            ->where('status', '!=', 'Cancelled') 
            ->sum('platform_fee');

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'activeGrowers', 
            'pendingPayouts', 
            'couponUsage',
            'pendingOrders',
            'platformEarnings'
        ));
        }

        if ($user->role === 'vendor') {
            
            $farmData = Farm::where('user_id', $user->id)->first();

            $farmId = $farmData ? $farmData->id : $user->id;

            $products = Product::where('farm_id', $farmId)->with(['category'])->get();

            // Base Query: 
            $ordersQuery = Order::whereHas('items', function($query) use ($farmId) {
                $query->whereHas('product', function($pQuery) use ($farmId) {
                    $pQuery->where('farm_id', $farmId);
                });
            });

            // 🔥 SECURITY SECURITY FIX 1:
            $allCount       = (clone $ordersQuery)->where('status', '!=', 'pending')->count();
            $awaitingCount  = (clone $ordersQuery)->where('status', 'Awaiting Dispatch')->count();
            $inTransitCount = (clone $ordersQuery)->where('status', 'In Transit')->count();
            $deliveredCount = (clone $ordersQuery)->where('status', 'Delivered')->count();

            // --- FILTER LOGIC (Tab click filter) ---
            if (request()->has('status') && request('status') !== 'all') {
                // Agar user chalaki se url me status=pending likhe, tab bhi block ho jaye
                if (request('status') === 'pending') {
                    abort(403, 'Unauthorized Action.');
                }
                $ordersQuery->where('status', request('status'));
            } else {
                // Default rule: 
                $ordersQuery->where('status', '!=', 'pending');
            }

            $orders = $ordersQuery->with(['items.product', 'customer'])->latest()->get();

            // A. Total Revenue Earned: 
            $totalRevenueEarned = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('products.farm_id', $farmId)
                ->where('orders.status', 'Delivered')
                ->sum(DB::raw('order_items.quantity_kg * order_items.price_at_purchase'));

            // --- DYNAMIC PERFORMANCE INSIGHTS CHARTS GENERATION ---

            // 1. Line Chart Data: Monthly Sales Trend (Sirf 'Delivered' orders ki monthly kamai)
            $monthlySales = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('products.farm_id', $farmId)
                ->where('orders.status', 'Delivered')
                ->select(
                    DB::raw("DATE_FORMAT(orders.created_at, '%b') as month"),
                    DB::raw("SUM(order_items.quantity_kg * order_items.price_at_purchase) as total")
                )
                ->groupBy(DB::raw("DATE_FORMAT(orders.created_at, '%b')"))
                ->orderBy('orders.created_at', 'asc')
                ->get();

            $salesLabels = $monthlySales->pluck('month')->toArray();
            $salesData = $monthlySales->pluck('total')->toArray(); 

            if (empty($salesLabels)) {
                $salesLabels = ['Jul', 'Aug', 'Sept', 'Oct'];
                $salesData = [0, 0, 0, 0];
            }

            // 2. Donut Chart Data: Variety Performance (KGs sold per Category)
            $varietyPerformance = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('products.farm_id', $farmId)
                ->where('orders.status', 'Delivered')
                ->select(
                    'categories.name as variety_name',
                    DB::raw("SUM(order_items.quantity_kg) as total_kg")
                )
                ->groupBy('categories.id', 'categories.name')
                ->get();

            $donutLabels = $varietyPerformance->pluck('variety_name')->toArray();
            $donutData = $varietyPerformance->pluck('total_kg')->toArray();

            if (empty($donutLabels)) {
                $donutLabels = ['No Sales Yet'];
                $donutData = [1];
            }

            $stats = [
                'total_batches'         => $products->count(),
                'total_stock'           => $products->sum('stock_quantity_kg'),
                'estimated_vault_value' => $products->sum(function($product) {
                    return $product->price_per_kg * $product->stock_quantity_kg;
                }),
                'total_revenue_earned'  => $totalRevenueEarned ?? 0,
                
                // Live Chart data arrays
                'sales_labels'          => $salesLabels,
                'sales_data'            => $salesData,
                'donut_labels'          => $donutLabels,
                'donut_data'            => $donutData,
            ];

            $farm = (object)[
                'farm_name' => $farmData ? $farmData->farm_name : $user->name . ' Orchard'
            ];

            $lowStockThreshold = 50;

            $lowStockProducts = Product::where('farm_id', $farmId)
                ->where('stock_quantity_kg', '<=', $lowStockThreshold)
                ->with(['category'])
                ->get();

            return view('vendor.dashboard', compact(
                'orders', 
                'products', 
                'stats', 
                'farm', 
                'user',
                'allCount',
                'awaitingCount',
                'inTransitCount',
                'deliveredCount',
                'lowStockProducts',
                'lowStockThreshold'
            ));
        }

        $orders = Order::where('customer_id', $user->id)
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('customer.dashboard', compact('orders', 'user'));
    }

    // --- LIVE AJAX STATUS UPDATE (SECURED) ---
    public function updateOrderStatus(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($id);

        if ($user->role === 'vendor') {
            
            $allowedVendorStatuses = ['In Transit', 'Out For Delivery', 'Delivered'];
            
            if (!in_array($request->status, $allowedVendorStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => '🚫 Unauthorized! Vendors cannot set order status to ' . $request->status
                ], 403);
            }

            if ($order->status === 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => '🚫 Action Blocked! Is order ki payment abhi tak Admin se approve nahi hui.'
                ], 403);
            }
        }

        $request->validate([
            'status' => 'required|string|in:Pending,Awaiting Dispatch,In Transit,Out For Delivery,Delivered,Cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order #' . $id . ' status automatically updated to ' . $request->status
        ]);
    }
}