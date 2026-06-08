<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Royal Mango Vault</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fbf8f3] text-[#1c2a1e] min-h-screen">

    <nav class="bg-[#1b3322] text-white py-5 px-6 shadow-md border-b border-[#c59d3f]/20">
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

    <nav class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-[#1b3322] tracking-wide uppercase serif-heading">Royal Mango Vault</h1>
            <p class="text-[10px] text-[#c59d3f] tracking-widest uppercase font-bold">Orchard Panel</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-xs font-semibold bg-gray-50 border border-gray-100 text-gray-700 px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                🧑‍🌾 {{ Auth::user()->name }} ({{ $farm->farm_name ?? 'No Farm Registered' }})
            </span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-red-500 font-bold uppercase tracking-wider hover:text-red-700 transition cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-[1600px] mx-auto px-6 py-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-normal text-[#1b3322] serif-heading">Assalam-o-Alaikum, Mango Merchant!</h2>
                <p class="text-xs text-gray-500 mt-0.5">Manage your commercial harvest batches and live market listings.</p>
            </div>
            <div>
                <button onclick="openAddModal()" class="inline-block px-5 py-2.5 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest rounded-lg transition uppercase shadow-sm cursor-pointer font-semibold">
                    + List New Batch
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-2xs">
                <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Active Mango Batches</p>
                <p class="text-2xl font-bold text-[#1b3322] mt-1">{{ $stats['total_batches'] ?? 0 }} <span class="text-xs font-normal text-gray-400">Live</span></p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-2xs">
                <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Total Stock Available</p>
                <p class="text-2xl font-bold text-[#1b3322] mt-1">{{ number_format($stats['total_stock'] ?? 0) }} <span class="text-xs font-normal text-gray-400">KGs</span></p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-2xs">
                <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Estimated Vault Value</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">PKR {{ number_format($stats['estimated_vault_value'] ?? 0) }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-2xs relative overflow-hidden ring-1 ring-emerald-500/10">
                <p class="text-[10px] uppercase font-bold tracking-wider text-emerald-600">Total Revenue Earned</p>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-2xl font-black text-[#1b3322]">Rs. {{ number_format($stats['total_revenue_earned'] ?? 0) }}</p>
                    <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-1.5 py-0.5 rounded flex items-center">▲ +15%</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-2xs">
                <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Top-Selling Variety</p>
                <div class="flex items-baseline justify-between mt-1">
                    <p class="text-lg font-bold text-gray-800 truncate max-w-[120px]">Organic Chaunsa</p>
                    <span class="text-xs text-gray-400 font-semibold">62%</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <div class="lg:col-span-3 sticky top-6 space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Performance Insights</h3>
                
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-2xs">
                    <p class="text-[11px] uppercase font-bold tracking-wider text-gray-500 mb-4">Monthly Sales Trend (PKR)</p>
                    <div class="w-full h-44">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-2xs">
                    <p class="text-[11px] uppercase font-bold tracking-wider text-gray-500 mb-4">Variety Performance (KGs)</p>
                    <div class="w-full h-44 flex items-center justify-center">
                        <canvas id="varietyDonutChart"></canvas>
                    </div>

                    @if(!empty($stats['donut_labels']) && count($stats['donut_labels']) > 0)
                        @php
                            $totalWeight = array_sum($stats['donut_data'] ?? [1]);
                            $colors = ['bg-[#1b3322]', 'bg-[#c59d3f]', 'bg-[#922b21]', 'bg-[#2e4053]', 'bg-[#d35400]'];
                        @endphp
                        <div class="mt-6 border-t border-gray-100 pt-4 space-y-3">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Variety Volume Breakdown</h4>
                            @foreach($stats['donut_labels'] as $index => $label)
                                @php
                                    $weight = $stats['donut_data'][$index] ?? 0;
                                    $percentage = $totalWeight > 0 ? round(($weight / $totalWeight) * 100) : 0;
                                    $barColor = $colors[$index % count($colors)];
                                @endphp
                                <div>
                                    <div class="flex justify-between text-[11px] font-semibold text-gray-700 mb-1">
                                        <span class="truncate pr-2">{{ $label }}</span>
                                        <span class="shrink-0 text-gray-500 font-bold">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-1.5 rounded-full">
                                        <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-9 space-y-8">
                
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-50 pb-4 mb-4 gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1b3322] tracking-wide uppercase serif-heading">Inbound Customer Orders</h3>
                            <p class="text-[11px] text-gray-400 mt-0.5">Orders received from customers requesting fresh cargo dispatch.</p>
                        </div>
                        <div class="inline-flex p-1 bg-gray-50 border border-gray-200 rounded-full shadow-sm">
    
                            <a href="{{ route('vendor.dashboard', ['status' => 'all']) }}" 
                               class="px-5 py-1.5 text-sm font-medium rounded-full transition-all duration-200 
                               {{ request('status', 'all') == 'all' ? 'bg-[#f4ebe1] text-[#1e1e1e] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                All ({{ $allCount }})
                            </a>
                            
                            <a href="{{ route('vendor.dashboard', ['status' => 'Awaiting Dispatch']) }}" 
                               class="px-5 py-1.5 text-sm font-medium rounded-full transition-all duration-200 
                               {{ request('status') == 'Awaiting Dispatch' ? 'bg-[#f4ebe1] text-[#1e1e1e] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                Awaiting Dispatch ({{ $awaitingCount }})
                            </a>
                            
                            <a href="{{ route('vendor.dashboard', ['status' => 'In Transit']) }}" 
                               class="px-5 py-1.5 text-sm font-medium rounded-full transition-all duration-200 
                               {{ request('status') == 'In Transit' ? 'bg-[#f4ebe1] text-[#1e1e1e] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                In Transit ({{ $inTransitCount }})
                            </a>
                            
                            <a href="{{ route('vendor.dashboard', ['status' => 'Delivered']) }}" 
                               class="px-5 py-1.5 text-sm font-medium rounded-full transition-all duration-200 
                               {{ request('status') == 'Delivered' ? 'bg-[#f4ebe1] text-[#1e1e1e] shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                Delivered ({{ $deliveredCount }})
                            </a>

                        </div>
                    </div>

                    @if($orders->isEmpty())
                        <div class="text-center py-12 border border-dashed border-gray-100 rounded-xl bg-[#fcf9f2]/30">
                            <p class="text-gray-400 text-xs italic">No operational requests loaded in pipeline yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-100">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-gray-50/70 text-gray-500 uppercase font-bold tracking-wider border-b border-gray-100">
                                        <th class="p-3.5">Order ID</th>
                                        <th class="p-3.5">Customer</th>
                                        <th class="p-3.5">Requested Batch</th>
                                        <th class="p-3.5">Total Earnings</th>
                                        <th class="p-3.5">Shipping Address</th>
                                        <th class="p-3.5 text-center">Action Logistics Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50/30 transition">
                                            <td class="p-3.5 font-bold text-gray-400">#{{ $order->id }}</td>
                                            <td class="p-3.5 font-medium">
                                                <p class="text-gray-800 font-bold">{{ $order->customer->name ?? 'Guest User' }}</p>
                                                <p class="text-[10px] text-gray-400 mt-0.5">📞 {{ $order->customer->phone ?? 'N/A' }}</p>
                                            </td>
                                            <td class="p-3.5">
                                                @foreach($order->items as $item)
                                                    <div class="flex items-center gap-1.5 mb-0.5 last:mb-0">
                                                        <span class="font-semibold text-[#1b3322]">{{ $item->product->name ?? 'Mango Product' }}</span>
                                                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1 rounded font-medium">{{ number_format($item->quantity_kg, 0) }} KG</span>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="p-3.5 font-bold text-[#1b3322]">
                                                Rs. {{ number_format($order->total_amount, 0) }}
                                            </td>
                                            <td class="p-3.5 text-gray-500 max-w-xs truncate" title="{{ $order->shipping_address }}">
                                                {{ $order->shipping_address }}
                                            </td>
                                            <td class="p-3.5 text-center">
                                                <select 
                                                    id="status-select-{{ $order->id }}" 
                                                    onchange="autoSaveStatus('{{ $order->id }}')"
                                                    class="bg-white border border-gray-200 text-[11px] font-medium rounded-md px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-[#1b3322] text-gray-700 cursor-pointer shadow-3xs"
                                                >
                                                    <!-- <!-- <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>⏳ Pending</option> -->
                                                    <option value="Awaiting Dispatch" {{ $order->status === 'Awaiting Dispatch' ? 'selected' : '' }}>📦 Awaiting Dispatch</option> 
                                                    <option value="In Transit" {{ $order->status === 'In Transit' ? 'selected' : '' }}>🚚 In Transit</option>
                                                    <option value="Out For Delivery" {{ $order->status === 'Out For Delivery' ? 'selected' : '' }}>🛵 Out For Delivery</option>
                                                    <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                                    <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-4">Your Live Market Inventory</h3>
                    
                    @if(empty($products) || $products->isEmpty())
                        <div class="bg-white text-center py-16 rounded-xl border border-dashed border-gray-200 shadow-2xs">
                            <p class="text-gray-400 text-sm">No operational cargo batches registered yet.</p>
                            <button onclick="openAddModal()" class="text-xs text-[#c59d3f] font-bold underline mt-2 inline-block cursor-pointer">Add Product Now &rarr;</button>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($products as $product)
                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-2xs flex flex-col justify-between transition hover:shadow-xs">
                                    <div>
                                        <div class="w-full h-40 bg-gray-50 relative">
                                            <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            <span class="absolute top-3 right-3 bg-white/95 text-[#1b3322] border border-gray-100 text-[9px] font-black px-2 py-0.5 rounded shadow-3xs uppercase tracking-wider">
                                                {{ $product->category->name ?? 'Variety' }}
                                            </span>
                                        </div>
                                        
                                        <div class="p-4">
                                            <div class="flex items-center justify-between gap-2 mb-1">
                                                <h4 class="font-bold text-gray-800 text-sm truncate">{{ $product->name }}</h4>
                                                <span class="text-[8px] font-bold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded uppercase tracking-wide shrink-0">Quality Grade</span>
                                            </div>
                                            <p class="text-[11px] text-gray-400 line-clamp-2 mb-4">{{ $product->description ?? 'No specific micro-orchard notes compiled.' }}</p>
                                            
                                            <div class="flex justify-between items-center text-xs border-t border-gray-50 pt-3">
                                                <div>
                                                    <p class="text-[9px] text-gray-400 uppercase font-semibold">Price / KG</p>
                                                    <p class="font-bold text-[#1b3322] text-sm">Rs. {{ number_format($product->price_per_kg) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[9px] text-gray-400 uppercase font-semibold">Remaining Stock</p>
                                                    <p class="font-bold text-gray-700 {{ $product->stock_quantity_kg <= 50 ? 'text-red-500 font-extrabold animate-pulse' : '' }}">
                                                        {{ number_format($product->stock_quantity_kg) }} KGs
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4 pt-1 grid grid-cols-2 gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-center py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-[11px] font-semibold rounded-md transition border border-gray-200/60 shadow-3xs">
                                            Edit Batch
                                        </a>
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Kya aap waqai is mango batch ko delete karna chahte hain?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-1.5 bg-red-50 hover:bg-red-100/80 text-red-600 text-[11px] font-semibold rounded-md transition border border-red-100/50 cursor-pointer shadow-3xs">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div id="add-mango-modal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-xl w-full p-6 shadow-2xl border border-gray-100 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                <h3 class="text-lg font-bold text-[#1b3322] uppercase tracking-wide serif-heading">Harvest New Mango Batch</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-2xl font-light">&times;</button>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Mango Variety / Product Name</label>
                    <input type="text" name="name" placeholder="e.g. Premium Anwar Ratol" required class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Price per KG (PKR)</label>
                        <input type="number" name="price_per_kg" placeholder="350" required class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Total Available Stock (KG)</label>
                        <input type="number" name="stock_quantity_kg" placeholder="500" required class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Mango Type / Category</label>
                    <select name="category_id" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                        <option value="">Select Variety Type</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">👑 {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Product Description / Orchard Notes</label>
                    <textarea name="description" rows="3" placeholder="Describe taste profile, sweetness index, and harvest location..." required class="w-full px-3 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-1">Upload Product Image</label>
                    <input type="file" name="image_url" required class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-[#c59d3f] hover:bg-[#b08732] text-white text-xs font-bold rounded-lg uppercase tracking-widest shadow">Launch Batch</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        
        const ctxLine = document.getElementById('salesTrendChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['sales_labels']) !!},
                datasets: [{
                    label: 'Sales (PKR)',
                    data: {!! json_encode($stats['sales_data']) !!},
                    borderColor: '#c59d3f',
                    backgroundColor: 'rgba(197, 157, 63, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#1b3322'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false } 
                },
                scales: {
                    y: { 
                        grid: { display: false }, 
                        ticks: { 
                            callback: value => 'Rs. ' + value.toLocaleString(),
                            font: { size: 9 }
                        } 
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });

        const ctxDonut = document.getElementById('varietyDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($stats['donut_labels']) !!},
                datasets: [{
                    data: {!! json_encode($stats['donut_data']) !!},
                    backgroundColor: ['#1b3322', '#c59d3f', '#922b21', '#2e4053', '#d35400'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'right', 
                        labels: { 
                            boxWidth: 10, 
                            font: { size: 10 },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        const value = data.datasets[0].data[i];
                                        return {
                                            text: label + ' (' + value + ' KG)',
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        } 
                    } 
                },
                cutout: '70%'
            }
        });
    });

    function autoSaveStatus(orderId) {
        const selectElement = document.getElementById('status-select-' + orderId);
        const currentStatus = selectElement.value;
        selectElement.style.opacity = '0.5';

        fetch(`/vendor/order/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: currentStatus })
        })
        .then(response => response.json())
        .then(data => {
            selectElement.style.opacity = '1';
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast('Something went wrong.', 'error');
            }
        })
        .catch(error => {
            selectElement.style.opacity = '1';
            showToast('Network error while saving status.', 'error');
        });
    }

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `transform translate-x-full transition-all duration-300 ease-out p-4 rounded-lg shadow-lg max-w-sm flex items-center gap-3 text-white pointer-events-auto ${
            type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
        }`;
        
        const icon = type === 'success' ? '✨' : '⚠️';
        toast.innerHTML = `
            <span class="text-lg">${icon}</span>
            <p class="text-xs font-semibold uppercase tracking-wider">${message}</p>
        `;
        
        container.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 10);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    function openAddModal() {
        const modal = document.getElementById('add-mango-modal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.children[0].classList.remove('scale-95', 'opacity-0'), 10);
    }
    function closeAddModal() {
        const modal = document.getElementById('add-mango-modal');
        modal.children[0].classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    </script>
</body>
</html>