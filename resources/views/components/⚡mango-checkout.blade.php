@php
    // Controller se aane wale data variables handle karne ke liye fallbacks aur dynamic settings
    $subtotal = $totalAmount ?? 0;
    $platform_fee = 300;
    $shipping_fee = 0;
    
    // Total calculation 
    $totalAmountCalculated = $subtotal + $platform_fee + $shipping_fee;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Checkout - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-gray-900 min-h-screen flex flex-col">

    <nav class="bg-[#1b3322] text-white py-5 px-6 shadow-md border-b border-[#c59d3f]/20">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold tracking-wider text-[#e2b653] uppercase serif-heading">Multan</span>
                <span class="text-xs tracking-[0.2em] block text-gray-300 uppercase font-light">Mango Treasures</span>
            </div>
            <div class="flex items-center space-x-8 text-sm font-medium tracking-wide text-gray-200">
                <a href="/" class="hover:text-[#e2b653] transition">HOME</a>
                <a href="{{ route('products.index') }}" class="hover:text-[#e2b653] transition">OUR VARIETIES</a>
                <a href="{{ route('dashboard') }}" class="hover:text-[#e2b653] transition">DASHBOARD</a>
                <a href="{{ route('cart.index') }}" class="text-[#e2b653] font-bold transition flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full relative">
                    🛒 CART 
                    <span class="text-gray-400 text-[11px] px-2 py-0.5">
                        {{ $cartItems ? $cartItems->count() : '1' }}
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-12 px-4">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-6">
                <a href="{{ route('cart.index') }}" class="text-xs font-bold tracking-widest text-[#c59d3f] hover:text-[#1b3322] uppercase transition">
                    &larr; Return to Basket
                </a>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-semibold rounded">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-7 bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 space-y-6">
                    <div>
                        <h2 class="text-2xl font-normal text-[#1b3322] serif-heading uppercase tracking-wide">Secure Checkout</h2>
                        <p class="text-xs text-gray-400 mt-1">Please provide your genuine dispatch details for fresh orchard delivery.</p>
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        @if($product)
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity_kg" value="{{ $quantity ?? 5 }}">
                        @else
                            <input type="hidden" name="from_cart" value="true">
                        @endif

                        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                        <input type="hidden" name="shipping_fee" value="{{ $shipping_fee }}">
                        <input type="hidden" name="platform_fee" value="{{ $platform_fee }}">
                        <input type="hidden" name="total_amount" value="{{ $totalAmountCalculated }}">

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Recipient Name</label>
                            <input type="text" value="{{ Auth::check() ? Auth::user()->name : 'Valued Customer' }}" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed font-medium">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Active Contact Number (WhatsApp preferred)</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., 03001234567" required class="w-full px-4 py-3 bg-[#fcf9f2]/30 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-[#c59d3f] focus:border-[#c59d3f] focus:outline-none transition">
                            @error('phone_number') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Complete Delivery Address</label>
                            <textarea name="shipping_address" rows="3" placeholder="House#, Street#, Area/Colony, City Name" required class="w-full px-4 py-3 bg-[#fcf9f2]/30 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-[#c59d3f] focus:border-[#c59d3f] focus:outline-none transition resize-none">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2">
                            <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Select Payment Method</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="border p-3 rounded-lg flex items-center gap-2 cursor-pointer transition text-xs font-semibold border-gray-200 hover:border-[#c59d3f]">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <span>Cash On Delivery</span>
                                </label>
                                <label class="border p-3 rounded-lg flex items-center gap-2 cursor-pointer transition text-xs font-semibold border-gray-200 hover:border-[#c59d3f]">
                                    <input type="radio" name="payment_method" value="card">
                                    <span>Debit/Credit Card</span>
                                </label>
                                <label class="border p-3 rounded-lg flex items-center gap-2 cursor-pointer transition text-xs font-semibold border-gray-200 hover:border-[#c59d3f]">
                                    <input type="radio" name="payment_method" value="wallet">
                                    <span>Mobile Wallet</span>
                                </label>
                            </div>
                            @error('payment_method') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded-lg shadow-md transition cursor-pointer">
                                Confirm & Place Royal Order
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-5 lg:sticky lg:top-6">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-xs">
                        <div class="p-4 bg-[#1b3322] text-white text-center">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-[#e2b653]">Order Summary</h3>
                        </div>
                        
                        <div class="max-h-[300px] overflow-y-auto divide-y divide-gray-100">
                            @if($product)
                                <div class="p-4 flex gap-4 items-center bg-[#fcf9f2]/10">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-[#1b3322] text-xs uppercase tracking-wide">{{ $product->name }}</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Weight Batch: <b>{{ $quantity ?? 5 }} KG</b></p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">Rs. {{ number_format($subtotal, 0) }}</span>
                                </div>
                            @elseif($cartItems && $cartItems->isNotEmpty())
                                @foreach($cartItems as $item)
                                    <div class="p-4 flex gap-4 items-center bg-[#fcf9f2]/10">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-[#1b3322] text-xs uppercase tracking-wide">{{ $item->product->name }}</h4>
                                            <p class="text-[10px] text-gray-400 mt-0.5">Weight Batch: <b>{{ $item->quantity_kg }} KG</b></p>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">Rs. {{ number_format($item->product->price_per_kg * $item->quantity_kg, 0) }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="p-6 space-y-4 text-xs text-gray-600 border-t border-gray-100">
                            
                            <div class="pb-2 border-b border-gray-100">
                                <label class="block text-[9px] font-bold tracking-wider text-gray-400 uppercase mb-1">Apply Voucher</label>
                                <div class="flex gap-2">
                                    <input type="text" placeholder="e.g. MANGO10" class="flex-1 px-3 py-1.5 border border-gray-200 text-xs rounded uppercase focus:outline-none bg-gray-50 text-gray-400 cursor-not-allowed" disabled>
                                    <button type="button" class="px-3 bg-gray-400 text-white font-bold text-[10px] rounded cursor-not-allowed" disabled>APPLY</button>
                                </div>
                                <span class="text-gray-400 text-[10px] block mt-1 italic">Voucher system processed automatically during final verification.</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Subtotal:</span>
                                <span class="font-semibold text-gray-800 text-xs">Rs. {{ number_format($subtotal, 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Shipping Method:</span>
                                <span class="font-bold text-emerald-600 uppercase text-[10px] tracking-widest bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/50">Free Cargo</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Platform Service Fee:</span>
                                <span class="font-semibold text-gray-800 text-xs">Rs. {{ number_format($platform_fee, 0) }}</span>
                            </div>
                            
                            <div class="pt-4 border-t border-dashed border-gray-200 flex justify-between items-baseline">
                                <span class="font-bold text-gray-800 uppercase tracking-widest text-[11px]">Total Amount:</span>
                                <span class="text-2xl font-black text-[#1b3322] serif-heading">Rs. {{ number_format($totalAmountCalculated, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <nav class="bg-[#1b3322] text-white pt-12 pb-6 px-6 mt-20 border-t-4 border-[#c59d3f]">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-sm text-gray-300 pb-8 border-b border-gray-700/50">
            <div>
                <h4 class="text-base font-bold text-[#e2b653] mb-3 serif-heading uppercase tracking-wider">About The Marketplace</h4>
                <p class="text-xs leading-relaxed text-gray-400">Connecting genuine Multani growers directly with elite connoisseurs globally. Truly, the soul of Multan delivered fresh.</p>
            </div>
            <div class="text-center md:text-left">
                <h4 class="text-base font-bold text-[#e2b653] mb-3 serif-heading uppercase tracking-wider">Contact Us</h4>
                <p class="text-xs">Email: concierge@multanmangotreasures.com</p>
                <p class="text-xs mt-1">Support: FAQ & Assistance</p>
            </div>
            <div class="text-right">
                <h4 class="text-base font-bold text-[#e2b653] mb-3 serif-heading uppercase tracking-wider">Social Us</h4>
                <div class="flex justify-end space-x-4 mt-2 text-gray-400 text-xs">
                    <a href="#" class="hover:text-white">Instagram</a>
                    <a href="#" class="hover:text-white">Facebook</a>
                </div>
            </div>
        </div>
        <div class="text-center text-[11px] text-gray-500 mt-6 tracking-widest uppercase">
            Made in Multan with Love &copy; {{ date('Y') }}
        </div>
    </nav>

</body>
</html>