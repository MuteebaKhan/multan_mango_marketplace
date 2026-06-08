<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Multan Mango Treasures</title>
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
                <a href="{{ route('cart.index') }}" class="hover:text-[#e2b653] text-[#e2b653] font-bold transition flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full relative">
    🛒 CART 
    @if(isset($globalCartCount) && $globalCartCount > 0)
        <span class="bg-[#c59d3f] text-white text-[11px] font-extrabold px-2 py-0.5 rounded-full animate-pulse">
            {{ $globalCartCount }}
        </span>
    @else
        <span class="text-gray-400 text-[11px]">0</span>
    @endif
</a>
            <!-- </div>
            
            @auth
                @if(Auth::user()->role === 'vendor')
                    <a href="{{ route('products.create') }}" class="px-5 py-2 bg-[#c59d3f] hover:bg-[#b08732] text-white font-semibold rounded text-sm tracking-wider transition duration-150 shadow-xs">
                        + ADD NEW BATCH
                    </a>
                @endif
            @endauth
        </div> -->
    </nav>

    <div class="max-w-6xl mx-auto w-full px-4 pt-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs font-semibold rounded shadow-xs">
                ✨ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-semibold rounded shadow-xs">
                ⚠️ {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="max-w-6xl mx-auto w-full px-4 pt-4">
        <a href="{{ route('products.index') }}" class="text-xs font-bold tracking-widest text-[#c59d3f] hover:text-[#1b3322] uppercase transition flex items-center gap-2">
            &larr; Back To Varieties
        </a>
    </div>

    <div class="max-w-6xl mx-auto w-full px-4 py-8 flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white p-8 md:p-12 rounded-xl shadow-sm border border-gray-100">
            
            <div class="rounded-lg overflow-hidden bg-gray-50 border border-gray-100 shadow-inner h-[300px] md:h-[450px]">
                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold tracking-widest bg-[#c59d3f]/10 text-[#c59d3f] px-2.5 py-1 rounded-full uppercase">
                            👑 {{ $product->category->name ?? 'Premium Variety' }}
                        </span>
                        <span class="text-[10px] font-bold tracking-widest bg-[#1b3322]/5 text-[#1b3322] px-2.5 py-1 rounded-full uppercase">
                            🚜 {{ $product->farm->name ?? 'Multani Orchard' }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-normal text-[#1b3322] serif-heading uppercase tracking-wide mb-4">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <span class="text-3xl font-black text-[#1b3322]">Rs. {{ number_format($product->price_per_kg, 0) }}</span>
                        <span class="text-xs text-gray-400 font-semibold"> / Per Kilogram</span>
                    </div>

                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        {{ $product->description ?? 'No orchard notes available for this premium batch.' }}
                    </p>

                    <div class="bg-[#fcf9f2] p-4 rounded border border-[#c59d3f]/20 flex justify-between items-center text-sm mb-6">
                        <span class="text-gray-500 font-medium">Available Batch Weight:</span>
                        <span class="font-bold text-[#1b3322] text-base">
                            {{ number_format($product->stock_quantity_kg, 0) }} KG
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded border border-gray-100">
        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Select Weight Batch:</label>
        <select id="weight-selector" class="px-3 py-1.5 bg-white border border-gray-200 rounded text-xs focus:outline-none focus:ring-1 focus:ring-[#c59d3f] font-semibold text-gray-700">
            @for ($i = 5; $i <= min($product->stock_quantity_kg, 50); $i += 5)
                <option value="{{ $i }}">{{ $i }} KG (Premium Box)</option>
            @endfor
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
        
    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full m-0 p-0">
    @csrf
    <input type="hidden" name="quantity_kg" id="cart-weight-input" value="5">
    
    <button type="submit" class="w-full py-4 bg-white border-2 border-[#1b3322] text-[#1b3322] hover:bg-[#1b3322] hover:text-white font-bold text-xs tracking-widest uppercase rounded shadow transition duration-150 cursor-pointer text-center block">
        🛒 Add To Cart
    </button>
</form>

        <button type="button" onclick="goToCheckout({{ $product->id }})" class="w-full py-4 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest uppercase rounded shadow transition duration-150 cursor-pointer text-center">
            ⚡ Buy It Now
        </button>
    </div>
    <p class="text-[10px] text-gray-400 text-center italic">Direct from certified Multan farms &bull; Premium quality delivery guaranteed</p>
</div>
            </div>

        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    function sendToCart(productId) {
        const weight = document.getElementById('weight-selector').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Background Fetch Request (Forcing 100% POST Protocol)
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity_kg: weight
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success); // Royal confirmation popup
                window.location.reload(); // Quick view reload to refresh any cart counts
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong, please check login status.');
        });
    }

    function goToCheckout(productId) {
        const weight = document.getElementById('weight-selector').value;
        // Direct safe relocation
        window.location.href = `/checkout/${productId}?quantity=${weight}`;
    }
</script>

    <footer class="bg-[#1b3322] text-white pt-12 pb-6 px-6 border-t-4 border-[#c59d3f]">
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

</body>
</html>