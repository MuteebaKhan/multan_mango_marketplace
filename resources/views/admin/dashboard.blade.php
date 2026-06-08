<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multan Mango Treasures - Admin Executive Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfbf7; }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <div class="flex min-h-screen">

        <aside class="w-64 bg-[#1b3322] text-white flex flex-col justify-between hidden md:flex shrink-0 shadow-xl border-r border-emerald-950">
            <div>
                <div class="p-6 border-b border-emerald-900/50 flex items-center gap-3">
                    <span class="text-2xl">🥭</span>
                    <div>
                        <h1 class="text-sm font-bold tracking-wider text-amber-400 uppercase leading-none">Multan Mango</h1>
                        <p class="text-[10px] text-gray-400 tracking-widest uppercase mt-0.5">Treasures</p>
                    </div>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[#c59d3f]/10 text-amber-400 rounded-lg text-xs font-semibold tracking-wide transition">
                        <i class="fa-solid fa-chart-pie text-sm"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-basket-shopping text-sm"></i> Orders
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-credit-card text-sm"></i> Payment Verification
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-ticket text-sm"></i> Vouchers / Coupons
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-tree text-sm"></i> Product Varieties
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-users-gear text-sm"></i> Vendor Management
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-user-group text-sm"></i> Customers
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-truck-ramp-box text-sm"></i> Logistics / Delivery
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-emerald-900/50">
                <div class="flex items-center justify-between text-xs text-gray-400 bg-emerald-950/50 p-2.5 rounded-lg">
                    <span class="font-medium">v2.1 (Stable)</span>
                    <i class="fa-solid fa-circle-check text-emerald-500 animate-pulse"></i>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            
            <header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-6 md:px-8 shrink-0">
                <div class="flex items-center gap-4">
                    <button class="md:hidden text-gray-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div class="flex items-center bg-gray-50 border border-gray-200/60 px-3 py-1.5 rounded-lg gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span class="text-[11px] font-bold text-[#1b3322] uppercase tracking-wide">Concierge Active</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button class="relative text-gray-500 hover:text-gray-700 transition">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-[#1b3322] leading-none">{{ auth()->user()->name ?? 'Royal Admin' }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Super Administrator</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-[#c59d3f]/20 border border-[#c59d3f]/40 flex items-center justify-center font-bold text-[#1b3322] text-sm">
                            RA
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
                
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i> {{ session('success') }}
                    </div>
                @endif

                <div>
                    <h2 class="text-xl font-bold text-[#1b3322] tracking-tight">Executive Overview</h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">Real-time dynamic data sync from marketplace microservices.</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-lg">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm font-medium opacity-80">Platform Earnings (Net Profit)</p>
            <h3 class="text-2xl font-bold mt-1">Rs. {{ number_format($platformEarnings, 2) }}</h3>
        </div>
        <div class="p-3 bg-white/20 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <p class="text-xs mt-4 opacity-90">✨ Rs. 300 per successful order</p>
</div>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Revenue (PKR)</span>
                            <span class="p-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs"><i class="fa-solid fa-wallet"></i></span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black text-[#1b3322] font-mono">Rs. {{ number_format($totalRevenue, 2) }}</h3>
                            <p class="text-[10px] text-emerald-600 font-medium mt-1"><i class="fa-solid fa-chart-line"></i> +14.2% from last week</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active Mango Growers</span>
                            <span class="p-2 bg-amber-50 text-[#c59d3f] rounded-xl text-xs"><i class="fa-solid fa-tree"></i></span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black text-[#1b3322] font-mono">{{ $activeGrowers }}</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-1">Verified farms registered</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending Orders Amount</span>
                            <span class="p-2 bg-blue-50 text-blue-600 rounded-xl text-xs"><i class="fa-solid fa-clock-rotate-left"></i></span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black text-[#1b3322] font-mono">Rs. {{ number_format($pendingPayouts, 2) }}</h3>
                            <p class="text-[10px] text-blue-600 font-medium mt-1">Awaiting manual validation</p>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Coupon Usage</span>
                            <span class="p-2 bg-purple-50 text-purple-600 rounded-xl text-xs"><i class="fa-solid fa-ticket"></i></span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black text-[#1b3322] font-mono">{{ $couponUsage }}</h3>
                            <p class="text-[10px] text-purple-600 font-medium mt-1">Total vouchers applied</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden lg:col-span-2 flex flex-col">
                        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 bg-gray-50/50">
                            <div>
                                <h3 class="text-sm font-bold text-[#1b3322] uppercase tracking-wider">Pending Payment Verifications</h3>
                                <p class="text-[10px] text-gray-400 mt-0.5">Option B System Sandbox Flow for JazzCash Till IDs</p>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-50 text-[#c59d3f] border border-[#c59d3f]/30 rounded text-[9px] font-bold tracking-wider uppercase">Workflow Manual</span>
                        </div>

                        <div class="flex-1 overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="border-b border-gray-100 text-[10px] font-bold text-gray-400 bg-gray-50 uppercase tracking-wider">
                                        <th class="py-3.5 px-5">Order ID</th>
                                        <th class="py-3.5 px-4">Customer Name</th>
                                        <th class="py-3.5 px-4">Payment Method</th>
                                        <th class="py-3.5 px-4">Amount</th>
                                        <th class="py-3.5 px-5 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-xs">
                                @forelse($pendingOrders as $order)
    <tr class="hover:bg-gray-50/70 transition border-b border-gray-100">
        <td class="py-4 px-5 font-mono font-bold text-gray-700">#{{ $order->id }}</td>
        <td class="py-4 px-4 font-medium text-gray-800">
            {{ $order->customer->name ?? 'Multan Customer' }}
        </td>
        <td class="py-4 px-4">
            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded text-[10px] font-bold uppercase tracking-wider block w-max">
                {{ $order->payment->payment_method ?? 'JazzCash' }}
            </span>
            @if($order->payment && $order->payment->transaction_id)
                <p class="text-[11px] font-mono font-bold text-emerald-800 mt-1">
                    TID: {{ $order->payment->transaction_id }}
                </p>
            @endif
        </td>
        <td class="py-4 px-4 font-bold text-[#1b3322] font-mono">Rs. {{ number_format($order->total_amount, 2) }}</td>
        <td class="py-4 px-5 text-center">
            <div class="flex items-center justify-center gap-3">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-blue-50 text-blue-700">
                    {{ $order->status }}
                </span>

                @if($order->status === 'pending')
                    <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" onsubmit="return confirm('Kia aap ne JazzCash system me is TID ko verify kar liya hai?')">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-[#1b3322] hover:bg-emerald-900 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm transition">
                            <i class="fa-solid fa-circle-check mr-1 text-amber-400"></i> Approve
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-8 text-center text-gray-400 font-medium text-xs">
            <i class="fa-solid fa-inbox block text-2xl mb-2 text-gray-300"></i> No orders found in database!
        </td>
    </tr>
@endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1b3322] uppercase tracking-wider">Order Density Map</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Real-time rider geocoding logs for Multan.</p>
                        </div>
                        
                        <div class="flex-1 bg-stone-100 rounded-xl border border-gray-200/60 relative overflow-hidden min-h-[220px] flex items-center justify-center text-center p-4">
                            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(#1b3322_1px,transparent_1px)] [background-size:16px_16px]"></div>
                            <div class="relative space-y-2">
                                <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-600 mx-auto text-sm animate-bounce">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <p class="text-[11px] font-bold text-[#1b3322]">Multan Cantt & Garden Town Hub</p>
                                <p class="text-[10px] text-gray-400 leading-tight">Leaflet Map coordinates binding will render live location feeds here.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>

</body>
</html>