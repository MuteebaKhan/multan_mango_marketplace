<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Royal Cart - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-gray-900 min-h-screen flex flex-col justify-between">

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
                <a href="{{ route('cart.index') }}" class="text-[#e2b653] font-bold transition flex items-center gap-1">
                    🛒 CART ({{ $cartItems->count() }})
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto w-full px-4 py-12 flex-1">
        <h1 class="text-3xl font-normal text-[#1b3322] serif-heading uppercase tracking-wide mb-8">
            Your Royal Mango Basket 🛒
        </h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs font-semibold rounded shadow-xs">
                ✨ {{ session('success') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white p-12 rounded-xl text-center border border-gray-100 shadow-xs">
                <p class="text-gray-500 text-sm mb-6">Your basket is currently empty. No premium Multani batches reserved yet.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded shadow transition duration-150">
                    Browse Varieties &rarr;
                </a>
            </div>
        @else
        <div>
        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded shadow transition duration-150">
                    Browse Varieties &rarr;
                </a>
        </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
            <div class="lg:col-span-2 space-y-4">
    @foreach($cartItems as $item)
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg bg-gray-50 overflow-hidden border border-gray-100">
                    <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-[#1b3322] uppercase tracking-wide text-sm">{{ $item->product->name }}</h3>
                    <p class="text-xs text-gray-400 font-medium">Rs. {{ number_format($item->product->price_per_kg, 0) }} / KG</p>
                    <p class="text-xs text-[#c59d3f] font-bold mt-1">Batch Weight: {{ $item->quantity_kg }} KG</p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-black text-[#1b3322] text-base">
                    Rs. {{ number_format($item->product->price_per_kg * $item->quantity_kg, 0) }}
                </span>
            </div>
        </div>
    @endforeach
</div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-6">
                    <h2 class="text-lg font-bold text-[#1b3322] uppercase tracking-wide border-b border-gray-100 pb-3 serif-heading">Order Summary</h2>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Total Reserved Batches:</span>
                        <span class="font-bold text-gray-800">{{ $cartItems->sum('quantity_kg') }} KG</span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <span class="text-gray-800 font-bold text-sm">Total Amount:</span>
                        <span class="text-xl font-black text-[#1b3322]">Rs. {{ number_format($totalAmount, 0) }}</span>
                    </div>

                    <a href="{{ route('checkout') }}?from_cart=true" class="block w-full py-4 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded shadow transition duration-150 text-center cursor-pointer">
                        Proceed To Checkout ⚡
                    </a>
                    
                    <p class="text-[10px] text-gray-400 text-center italic leading-relaxed">By proceeding, your premium harvest slots are locked & locked for guaranteed delivery.</p>
                </div>

            </div>
        @endif
    </div>

    <footer class="bg-[#1b3322] text-white pt-6 pb-6 text-center text-[10px] text-gray-400 uppercase tracking-widest border-t-4 border-[#c59d3f]">
        Multan Mango Treasures &bull; Cultivating Royalty &copy; {{ date('Y') }}
    </footer>

</body>
</html>