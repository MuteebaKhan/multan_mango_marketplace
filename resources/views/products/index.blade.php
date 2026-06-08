<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Varieties - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght=0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght=0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-[#2b2925] min-h-screen flex flex-col">

    <nav class="bg-[#1b3322] text-white py-5 px-6 shadow-md border-b border-[#c59d3f]/20 z-30 relative">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold tracking-wider text-[#e2b653] uppercase serif-heading">Multan</span>
                <span class="text-xs tracking-[0.2em] block text-gray-300 uppercase font-light">Mango Treasures</span>
            </div>
            <div class="flex items-center space-x-8 text-sm font-medium tracking-wide text-gray-200">
                <a href="/" class="hover:text-[#e2b653] transition">HOME</a>
                <a href="{{ route('products.index') }}" class="text-[#e2b653] border-b-2 border-[#e2b653] pb-1">OUR VARIETIES</a>
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
            </div>
        </div>
    </nav>

    <div class="flex flex-col lg:flex-row flex-grow w-full relative">
        
        <aside class="w-full lg:w-72 bg-[#1b3322] text-white p-6 lg:min-h-[calc(100vh-80px)] lg:sticky lg:top-0 border-r border-[#c59d3f]/20 shadow-xl flex flex-col justify-between shrink-0 z-20">
            <div>
                <div class="mb-8 pb-4 border-b border-[#c59d3f]/20">
                    <h3 class="text-xs font-bold tracking-[0.15em] text-[#e2b653] uppercase">
                        Orchard Selection
                    </h3>
                    <p class="text-[11px] text-gray-400 italic mt-1">Filter fresh harvest by master grower</p>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center justify-between px-4 py-3 text-xs font-semibold tracking-wider rounded-xl transition-all duration-200 group uppercase
                       {{ !request()->has('farm') || request('farm') == '' ? 'bg-[#c59d3f] text-[#1b3322] shadow-md font-bold' : 'hover:bg-white/5 text-gray-300' }}">
                        <span>🌳 All Orchards</span>
                        <span class="text-[10px] opacity-60 group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>

                    @foreach($farms as $farm)
                        <a href="{{ route('products.index', ['farm' => $farm->id]) }}" 
                           class="flex items-center justify-between px-4 py-3 text-xs font-semibold tracking-wider rounded-xl transition-all duration-200 group uppercase truncate
                           {{ request('farm') == $farm->id ? 'bg-[#c59d3f] text-[#1b3322] shadow-md font-bold' : 'hover:bg-white/5 text-gray-300' }}"
                           title="{{ $farm->name ?? $farm->farm_name ?? 'Premium Grower' }}">
                            <span class="truncate">
                                  {{ $farm->name ?? $farm->farm_name ?? 'Premium Orchard' }}
                            </span>
                            <span class="text-[10px] opacity-60 group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="mt-12 pt-4 border-t border-[#c59d3f]/10 text-center hidden lg:block">
                <span class="text-[9px] tracking-[0.2em] text-[#e2b653]/60 uppercase block font-light">
                    100% Certified Origin
                </span>
            </div>
        </aside>

        <main class="flex-grow p-6 sm:p-8 lg:p-12">
            
            <div class="mb-12 text-center lg:text-left">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-normal text-[#1b3322] tracking-wide uppercase serif-heading">
                    Choose Your Crown Jewel
                </h2>
                <div class="w-24 h-[2px] bg-[#c59d3f] mt-4 mx-auto lg:mx-0"></div>
                <p class="text-gray-500 mt-4 max-w-xl text-sm italic">
                    Experience the rich heritage and exceptional sweetness of globally acclaimed Multani mango varieties, fresh from the royal orchards.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-10 max-w-4xl p-4 bg-[#1b3322] text-[#e2b653] border-l-4 border-[#c59d3f] rounded shadow-sm font-medium tracking-wide">
                    ✨ {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())
                <div class="text-center py-24 bg-white rounded-2xl shadow-xs border border-gray-100 max-w-4xl px-6">
                    <div class="text-4xl mb-4">📦</div>
                    <p class="text-base text-gray-700 font-semibold serif-heading uppercase tracking-wide">No Active Vault Batches</p>
                    <p class="text-gray-400 text-xs mt-1 max-w-sm mx-auto">This specific grower has either sold out their premier harvest or is currently preparation staging the next crop flush.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-6 text-xs font-bold uppercase bg-[#1b3322] text-[#e2b653] px-5 py-2.5 rounded hover:bg-[#132418] transition shadow-xs">
                        &larr; Return to All Orchards
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 max-w-[1400px]">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-xs hover:shadow-xl border border-gray-100 transition-all duration-300 flex flex-col justify-between p-5 group">
                            
                            <div>
                                <div class="h-48 w-full rounded-xl bg-gray-50 relative overflow-hidden mb-4">
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <span class="absolute top-3 left-3 bg-[#1b3322]/90 backdrop-blur-xs text-[#e2b653] text-[9px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-xs">
                                        🌾 {{ $product->farm->name ?? $product->farm->farm_name ?? 'Multani Orchard' }}
                                    </span>
                                </div>

                                <div class="text-center px-1">
                                    <h3 class="text-lg font-bold text-[#1b3322] uppercase tracking-wide serif-heading truncate" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h3>
                                    <span class="text-[10px] italic text-[#c59d3f] tracking-widest block font-bold mt-0.5 uppercase">
                                        {{ $product->category->name ?? 'Premium Quality' }}
                                    </span>
                                    <p class="text-gray-500 text-xs mt-3 line-clamp-2 leading-relaxed">
                                        {{ $product->description ?? 'No description provided.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-4 text-xs">
                                    <div class="text-left">
                                        <span class="text-gray-400 block uppercase text-[9px] font-bold tracking-wider">Rate</span>
                                        <span class="text-sm font-extrabold text-[#1b3322]">Rs. {{ number_format($product->price_per_kg, 0) }}<span class="font-normal text-gray-400 text-[10px]">/kg</span></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-400 block uppercase text-[9px] font-bold tracking-wider">Available</span>
                                        <span class="text-xs font-bold bg-gray-50 border border-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ number_format($product->stock_quantity_kg, 0) }} KG</span>
                                    </div>
                                </div>

                                <a href="{{ route('products.show', $product->id) }}" class="block w-full text-center py-2.5 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest rounded-lg transition duration-200 uppercase shadow-xs">
                                    Buy Fresh / View Details
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-16 flex justify-center max-w-[1400px]">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>

    <footer class="bg-[#1b3322] text-white pt-12 pb-6 px-6 border-t-4 border-[#c59d3f] z-30 relative">
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