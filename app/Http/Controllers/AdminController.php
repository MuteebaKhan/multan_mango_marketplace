<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // 1. Executive Dashboard Overview Data
    public function index()
    {
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

    // 2. Payment Approval Status Method (JazzCash TID Cross Match Verification)
    public function verifyPayment(Order $order)
    {
        if (!$order) {
            return redirect()->back()->with('error', 'Order system me nahi mila!');
        }

        $order->update(['status' => 'Awaiting Dispatch']);
        
        if ($order->payment) {
            $order->payment->update(['status' => 'completed']);
        }
        
        return redirect()->back()->with('success', "👑 Order #{$order->id} Payment Successfully Verified! Inventory dispatch logs generated for the orchard farm.");
    }


    // 3. Coupons Listing View
    public function coupons()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons', compact('coupons'));
    }

    // 4. Store New Coupon Engine Rule
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|max:50',
            'type' => 'required',
            'value' => 'required|numeric',
            'expiry_date' => 'required|date',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'expiry_date' => $request->expiry_date,
            'is_active' => true, 
        ]);

        return redirect()->back()->with('success', 'Naya Coupon successfully create ho gaya!');
    }

    // 5. Delete Coupon Rule
    public function deleteCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->back()->with('success', 'Coupon database se remove kar diya gaya!');
    }
}