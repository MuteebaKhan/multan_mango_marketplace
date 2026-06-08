<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Royal Checkout - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
        /* Style for active custom payment method radio boxes */
        .payment-radio:checked + .payment-box {
            border-color: #c59d3f;
            background-color: rgba(252, 249, 242, 0.4);
            box-shadow: 0 2px 4px rgba(197, 157, 63, 0.1);
        }
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
                    @if(isset($globalCartCount) && $globalCartCount > 0)
                        <span class="bg-[#c59d3f] text-white text-[11px] font-extrabold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                            {{ $globalCartCount }}
                        </span>
                    @else
                        <span class="text-gray-400 text-[11px] px-2 py-0.5">0</span>
                    @endif
                </a>
            </div>
            
            @auth
                @if(Auth::user()->role === 'vendor')
                    <a href="{{ route('products.create') }}" class="px-5 py-2 bg-[#c59d3f] hover:bg-[#b08732] text-white font-semibold rounded text-sm tracking-wider transition duration-150 shadow-xs">
                        + ADD NEW BATCH
                    </a>
                @endif
            @endauth
        </div>
    </nav>

    <main class="flex-grow py-12 px-4">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-6">
                @if(isset($product))
                    <a href="{{ route('products.show', $product->id) }}" class="text-xs font-bold tracking-widest text-[#c59d3f] hover:text-[#1b3322] uppercase transition">
                        &larr; Change Weight / Go Back
                    </a>
                @endif
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-semibold rounded">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST" id="checkoutMasterForm">
                @csrf
                
                @if(isset($cartItems))
                    <input type="hidden" name="from_cart" value="true">
                @else
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity_kg" value="{{ $quantity }}">
                @endif

                <input type="hidden" name="coupon_code" id="hidden_coupon_code" value="">

                <input type="hidden" name="latitude" id="latitude_input" value="">
                <input type="hidden" name="longitude" id="longitude_input" value="">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <div class="lg:col-span-7 space-y-6">
                        
                        <div class="bg-white p-6 md:p-8 rounded-xl shadow-xs border border-gray-100 space-y-5">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-[#1b3322] text-[#e2b653] font-bold text-xs flex items-center justify-center">1</span>
                                <h3 class="text-lg font-bold text-[#1b3322] tracking-wide uppercase text-sm">Shipping Details</h3>
                            </div>
                            <hr class="border-gray-100">

                            <div>
                                <label class="block text-[10px] font-bold tracking-wider text-gray-400 uppercase mb-2">Recipient Name</label>
                                <input type="text" value="{{ Auth::check() ? Auth::user()->name : 'Valued Customer' }}" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed font-medium">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Active Contact Number (WhatsApp preferred)</label>
                                <input type="text" name="phone_number" placeholder="e.g., 03001234567" value="{{ old('phone_number') }}" required class="w-full px-4 py-3 bg-[#fcf9f2]/30 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-[#c59d3f] focus:border-[#c59d3f] focus:outline-none transition placeholder-gray-400">
                                @error('phone_number') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-2">Complete Delivery Address</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3" placeholder="House#, Street#, Area/Colony, City Name" required class="w-full px-4 py-3 bg-[#fcf9f2]/30 border border-gray-200 rounded text-sm focus:ring-1 focus:ring-[#c59d3f] focus:border-[#c59d3f] focus:outline-none transition resize-none placeholder-gray-400">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase">Pin Your Exact Location (For Fast Delivery Rider)</label>
                                <p class="text-[11px] text-gray-400">Map par click karke apni location marker set karein ya automatic detect karein.</p>
                                <div class="flex gap-2 mb-2">
                                    <button type="button" onclick="locateUserDevice()" class="px-3 py-1.5 bg-[#1b3322] text-[#e2b653] font-bold text-[10px] tracking-wider uppercase rounded hover:bg-[#132418] transition flex items-center gap-1 cursor-pointer">
                                        📍 Detect My Current Location
                                    </button>
                                    <span id="geo_status_badge" class="text-[10px] self-center text-gray-400 italic">Location coordinates pending...</span>
                                </div>
                                <div id="checkout_map" class="w-full h-48 bg-gray-100 rounded-lg border border-gray-200 z-10"></div>
                            </div>
                        </div>

                        <div class="bg-white p-6 md:p-8 rounded-xl shadow-xs border border-gray-100 space-y-5">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-[#1b3322] text-[#e2b653] font-bold text-xs flex items-center justify-center">2</span>
                                <h3 class="text-lg font-bold text-[#1b3322] tracking-wide uppercase text-sm">Payment Method</h3>
                            </div>
                            <hr class="border-gray-100">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="COD" {{ old('payment_method', 'COD') === 'COD' ? 'checked' : '' }} class="sr-only payment-radio">
                                    <div class="payment-box border p-4 rounded-xl flex flex-col items-center justify-center text-center gap-1.5 transition hover:border-[#c59d3f] border-gray-200">
                                        <span class="text-xl">📦</span>
                                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wide">Cash On Delivery</span>
                                        <span class="text-[9px] text-gray-400 leading-tight">Pay at your doorstep</span>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="Card" {{ old('payment_method') === 'Card' ? 'checked' : '' }} class="sr-only payment-radio">
                                    <div class="payment-box border p-4 rounded-xl flex flex-col items-center justify-center text-center gap-1.5 transition hover:border-[#c59d3f] border-gray-200">
                                        <span class="text-xl">💳</span>
                                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wide">Credit / Debit Card</span>
                                        <span class="text-[9px] text-gray-400 leading-tight">Visa / MasterCard</span>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="JazzCash" {{ old('payment_method') === 'JazzCash' ? 'checked' : '' }} class="sr-only payment-radio" id="jazzcash_radio_btn">
                                    <div class="payment-box border p-4 rounded-xl flex flex-col items-center justify-center text-center gap-1.5 transition hover:border-[#c59d3f] border-gray-200">
                                        <span class="text-xl">📱</span>
                                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wide">JazzCash / Wallet</span>
                                        <span class="text-[9px] text-gray-400 leading-tight">Instant local transfer</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div id="jazzcash_credentials_box" class="hidden mt-4 p-5 bg-[#fcf9f2] rounded-xl border border-[#c59d3f]/40 space-y-4">
                                <div class="bg-white p-3.5 rounded border border-[#c59d3f]/20 shadow-xs">
                                    <p class="text-[10px] font-bold tracking-widest text-[#c59d3f] uppercase mb-1.5">Moneypool Transfer Credentials</p>
                                    <div class="space-y-1 text-xs text-gray-700">
                                        <div class="flex justify-between"><span>JazzCash Till ID:</span> <b class="font-bold text-[#1b3322]">00246813</b></div>
                                        <div class="flex justify-between"><span>Mobile Account:</span> <b class="font-bold text-[#1b3322]">0304 - 7654321</b></div>
                                        <div class="flex justify-between"><span>Account Title:</span> <b class="font-semibold text-gray-900">Multan Mango Treasures</b></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1.5">Enter 12-Digit Transaction ID (TID)</label>
                                    <input type="text" name="transaction_id" id="transaction_id" placeholder="e.g., 601249875321" value="{{ old('transaction_id') }}" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded text-sm focus:ring-1 focus:ring-[#c59d3f] focus:border-[#c59d3f] focus:outline-none transition uppercase tracking-widest text-gray-800 placeholder-gray-300">
                                    <p class="text-[9px] text-gray-400 mt-1">Rider dispatch verification cycle starts after cross-matching your entered TID inside logs.</p>
                                    @error('transaction_id') <span class="text-red-500 text-[11px] block mt-1 font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @error('payment_method') <span class="text-red-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-white p-6 md:p-8 rounded-xl shadow-xs border border-gray-100 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-[#1b3322] text-[#e2b653] font-bold text-xs flex items-center justify-center">3</span>
                                <h3 class="text-lg font-bold text-[#1b3322] tracking-wide uppercase text-sm">Review & Place Order</h3>
                            </div>
                            <hr class="border-gray-100">
                            
                            <p class="text-[11px] text-gray-400 leading-relaxed">By clicking the absolute dispatch authorization button below, you confirm that your address details are valid. Your fresh orchard harvest processing cycle begins immediately.</p>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-4 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded-lg shadow-md transition cursor-pointer">
                                    👑 Confirm & Place Royal Order
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-5 lg:sticky lg:top-6">
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-xs">
                            <div class="p-4 bg-[#1b3322] text-white text-center">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-[#e2b653]">Order Summary</h3>
                            </div>
                            
                            @if(isset($cartItems) && !$cartItems->isEmpty())
                                <div class="max-h-[240px] overflow-y-auto divide-y divide-gray-100">
                                    @foreach($cartItems as $item)
                                        <div class="p-4 flex gap-4 items-center bg-[#fcf9f2]/10">
                                            <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-xs">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-bold text-[#1b3322] text-xs truncate uppercase tracking-wide">{{ $item->product->name }}</h4>
                                                <p class="text-[10px] text-gray-400 mt-0.5">Weight Batch: <b>{{ $item->quantity_kg }} KG</b></p>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700">Rs. {{ number_format($item->product->price_per_kg * $item->quantity_kg, 0) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(isset($product))
                                <div class="p-4 border-b border-gray-100 flex gap-4 items-center bg-[#fcf9f2]/20">
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-14 h-14 object-cover rounded-lg border border-gray-200 shadow-xs">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-[#1b3322] text-xs truncate uppercase serif-heading tracking-wide">{{ $product->name }}</h4>
                                        <p class="text-[10px] font-semibold text-[#c59d3f] mt-0.5 uppercase tracking-wider">🚜 {{ $product->farm->name ?? 'Premium Multani Orchard' }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Weight: <b>{{ $quantity }} KG</b></p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">Rs. {{ number_format($subtotal, 0) }}</span>
                                </div>
                            @endif

                            <div class="p-6 space-y-4 text-xs text-gray-600 border-t border-gray-100">
                                
                                <div class="pb-3 border-b border-gray-100">
                                    <label class="block text-[9px] font-bold tracking-wider text-gray-400 uppercase mb-1">Apply Voucher Coupon</label>
                                    <div class="flex gap-2">
                                        <input type="text" id="coupon_input_field" placeholder="e.g. MANGO10" class="flex-1 px-3 py-1.5 border border-gray-200 text-xs rounded uppercase focus:outline-none focus:border-[#c59d3f] bg-white text-gray-800">
                                        <button type="button" onclick="processCouponVerification()" class="px-4 bg-[#1b3322] text-[#e2b653] font-bold text-[10px] rounded hover:bg-[#132418] transition cursor-pointer">APPLY</button>
                                    </div>
                                    <span id="coupon_response_msg" class="text-[10px] block mt-1 hidden font-medium"></span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Subtotal:</span>
                                    <span class="font-semibold text-gray-800 text-xs">Rs. <span id="display_subtotal">{{ number_format($subtotal, 0) }}</span></span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Cargo Care Fee:</span>
                                    <span class="font-semibold text-gray-800 text-xs">Rs. <span id="display_platform">{{ number_format($platform_fee, 0) }}</span></span>
                                </div>

                                <!-- <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-500 uppercase tracking-wider text-[10px]">Shipping Method:</span>
                                    <span class="font-bold text-emerald-600 uppercase text-[10px] tracking-widest bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/50">Free Cargo</span>
                                </div> -->

                                <div id="discount_ledger_row" class="flex justify-between items-center text-red-600 hidden bg-red-50/50 p-1.5 rounded border border-red-100/50">
                                    <span class="font-medium uppercase tracking-wider text-[10px]">Voucher Discount (<span id="active_coupon_badge">NONE</span>):</span>
                                    <span class="font-bold text-xs">- Rs. <span id="display_discount">0</span></span>
                                </div>
                                
                                <div class="pt-4 border-t border-dashed border-gray-200 flex justify-between items-baseline">
                                    <span class="font-bold text-gray-800 uppercase tracking-widest text-[11px]">Total Amount:</span>
                                    <span class="text-2xl font-black text-[#1b3322] serif-heading">Rs. <span id="display_grand_total">{{ number_format($totalAmount, 0) }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <footer class="bg-[#1b3322] text-white pt-12 pb-6 px-6 mt-20 border-t-4 border-[#c59d3f]">
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
                    <a href="#" class="hover:text-white">Twitter</a>
                </div>
            </div>
        </div>
        <div class="text-center text-[11px] text-gray-500 mt-6 tracking-widest uppercase">
            Made in Multan with Love &copy; {{ date('Y') }}
        </div>
    </footer>

    <script>
    // Global Calculation state references mapped natively
    const rawSubtotal = parseFloat("{{ $subtotal }}");
    const rawPlatformFee = parseFloat("{{ $platform_fee }}");
    let activeDiscountAmount = 0.00;

    // ----------------------------------------------------
    // JAZZCASH TOGGLE CONTROLLER FUNCTIONALITY
    // ----------------------------------------------------
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleJazzCashInterface(this.value);
        });
    });

    function toggleJazzCashInterface(selectedMethod) {
        const jcBox = document.getElementById('jazzcash_credentials_box');
        const jcInput = document.getElementById('transaction_id');
        
        if (selectedMethod === 'JazzCash') {
            jcBox.classList.remove('hidden');
            jcInput.setAttribute('required', 'required');
        } else {
            jcBox.classList.add('hidden');
            jcInput.removeAttribute('required');
            jcInput.value = ''; // Reset ID state if toggled away
        }
    }

    // Checking validation state hook on initial load for safe redirection preservation
    window.addEventListener('DOMContentLoaded', () => {
        const checkedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        toggleJazzCashInterface(checkedMethod);
    });

    // ----------------------------------------------------
    // 1. DYNAMIC JAVASCRIPT AJAX COUPON VERIFICATION UNIT
    // ----------------------------------------------------
    async function processCouponVerification() {
        const couponInput = document.getElementById('coupon_input_field').value.trim();
        const responseMsgNode = document.getElementById('coupon_response_msg');
        const discountRow = document.getElementById('discount_ledger_row');
        
        if(!couponInput) {
            alert('Please enter a voucher code first!');
            return;
        }

        try {
            responseMsgNode.classList.remove('hidden', 'text-emerald-600', 'text-red-600');
            responseMsgNode.classList.add('text-gray-400');
            responseMsgNode.innerText = 'Checking validity...';

            const response = await fetch('/api/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: couponInput,
                    subtotal: rawSubtotal
                })
            });

            const data = await response.json();

            if(data.success) {
                activeDiscountAmount = parseFloat(data.discount_amount);
                document.getElementById('hidden_coupon_code').value = data.code;
                document.getElementById('active_coupon_badge').innerText = data.code;
                document.getElementById('display_discount').innerText = activeDiscountAmount.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0});
                
                responseMsgNode.className = "text-[10px] block mt-1 font-semibold text-emerald-600";
                responseMsgNode.innerText = `🎉 ${data.message}`;
                discountRow.classList.remove('hidden');
                
                recalculateGrandTotal();
            } else {
                resetAppliedCouponState();
                responseMsgNode.className = "text-[10px] block mt-1 font-semibold text-red-600";
                responseMsgNode.innerText = `⚠️ ${data.message}`;
            }

        } catch (error) {
            console.error("Voucher Error:", error);
            alert('Error running background verification request check.');
        }
    }

    function resetAppliedCouponState() {
        activeDiscountAmount = 0.00;
        document.getElementById('hidden_coupon_code').value = "";
        document.getElementById('discount_ledger_row').classList.add('hidden');
        recalculateGrandTotal();
    }

    function recalculateGrandTotal() {
        const computedTotal = (rawSubtotal + rawPlatformFee) - activeDiscountAmount;
        document.getElementById('display_grand_total').innerText = computedTotal.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0});
    }


    // ----------------------------------------------------
    // 2. OPENSTREETMAP (LEAFLET INTERACTIVE GEOLOCATOR WITH AUTO-SEARCH)
    // ----------------------------------------------------
    let mapInstance = L.map('checkout_map').setView([30.1984, 71.4687], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapInstance);

    let activeRiderMarker = L.marker([30.1984, 71.4687], {draggable: true}).addTo(mapInstance);
    
    updateHiddenCoordinates(30.1984, 71.4687);

    activeRiderMarker.on('dragend', function (event) {
        let position = activeRiderMarker.getLatLng();
        updateHiddenCoordinates(position.lat, position.lng);
    });

    mapInstance.on('click', function(e) {
        activeRiderMarker.setLatLng(e.latlng);
        updateHiddenCoordinates(e.latlng.lat, e.latlng.lng);
    });

    function updateHiddenCoordinates(lat, lng) {
        document.getElementById('latitude_input').value = lat.toFixed(8);
        document.getElementById('longitude_input').value = lng.toFixed(8);
        document.getElementById('geo_status_badge').className = "text-[10px] self-center text-emerald-600 font-bold";
        document.getElementById('geo_status_badge').innerText = `📍 Verified: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    }

    function locateUserDevice() {
        const statusBadge = document.getElementById('geo_status_badge');
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        statusBadge.innerText = "Scanning GPS signals...";
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                mapInstance.setView([userLat, userLng], 16);
                activeRiderMarker.setLatLng([userLat, userLng]);
                updateHiddenCoordinates(userLat, userLng);
            },
            (error) => {
                alert('Could not auto detect location. Please select on map manually.');
                statusBadge.innerText = "GPS access denied.";
            }
        );
    }

    // ----------------------------------------------------
    // SMART TEXT ADDRESS TO MAP SEARCH WITH FALLBACK
    // ----------------------------------------------------
    let searchTimer;
    document.getElementById('shipping_address').addEventListener('input', function() {
        clearTimeout(searchTimer);
        const addressText = this.value.trim();
        const statusBadge = document.getElementById('geo_status_badge');

        if (addressText.length < 4) return; 

        searchTimer = setTimeout(async () => {
            statusBadge.className = "text-[10px] self-center text-orange-500 font-medium animate-pulse";
            statusBadge.innerText = "Searching address on map...";

            let success = await fetchCoordinatesFromAPI(addressText + ", Multan, Pakistan");

            if (!success) {
                const addressParts = addressText.split(/[\s,]+/);
                
                if (addressParts.length > 1) {
                    const fallbackArea = addressParts.slice(-2).join(' ');
                    statusBadge.innerText = `Exact home not on map, locating area: "${fallbackArea}"...`;
                    success = await fetchCoordinatesFromAPI(fallbackArea + ", Multan, Pakistan");
                }
            }

            if (!success) {
                statusBadge.className = "text-[10px] self-center text-gray-400 italic";
                statusBadge.innerText = "Location not found, please pin manually on map.";
            }
        }, 1500); 
    });

    async function fetchCoordinatesFromAPI(queryString) {
        try {
            const query = encodeURIComponent(queryString);
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
            const data = await response.json();

            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);

                mapInstance.panTo(new L.LatLng(lat, lon));
                activeRiderMarker.setLatLng([lat, lon]);
                updateHiddenCoordinates(lat, lon);
                return true;
            }
            return false;
        } catch (error) {
            console.error("Geocoding API Error:", error);
            return false;
        }
    }
</script>
</body>
</html>