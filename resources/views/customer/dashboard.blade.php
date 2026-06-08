<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Vault - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#fcf9f2] text-gray-900">

    <nav class="bg-[#1b3322] text-[#e2b653] px-8 py-4 flex justify-between items-center shadow-md">
        <span class="font-bold text-xl uppercase tracking-wider">Multan Mango Treasures</span>
        <div class="flex items-center gap-4 text-sm">
            <span class="text-white">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs border border-[#e2b653]/30 px-3 py-1.5 rounded uppercase font-bold hover:border-[#e2b653] transition cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-12">
        
        <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm mb-6">
            <h1 class="text-2xl font-bold text-[#1b3322] mb-1">Your Royal Mango Vault</h1>
            <p class="text-xs text-[#c59d3f] uppercase tracking-wider font-semibold">Track your orders and premium sweet deliveries</p>
            
            @if($orders->isEmpty())
                <div class="mt-8 p-6 bg-[#fcf9f2] text-center rounded border border-dashed border-gray-200">
                    <p class="text-sm text-gray-500 italic">You haven't ordered any premium mango batches yet.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 px-6 py-2 bg-[#c59d3f] text-white text-xs font-bold uppercase rounded tracking-wider shadow hover:bg-[#b08732] transition">
                        Browse Fresh Harvest
                    </a>
                </div>
            @else
            <div>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 px-6 py-2 bg-[#c59d3f] text-white text-xs font-bold uppercase rounded tracking-wider shadow hover:bg-[#b08732] transition">
                        Browse Fresh Harvest
                    </a>
</div>
                <div class="mt-8 overflow-x-auto rounded-lg border border-gray-100">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-[#1b3322] text-[#e2b653] text-[10px] uppercase tracking-widest font-bold">
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Variety</th>
                                <th class="p-4">Weight</th>
                                <th class="p-4">Total Price</th>
                                <th class="p-4">Delivery Address</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4 font-bold text-gray-400 text-xs">#{{ $order->id }}</td>
                                    <td class="p-4 font-semibold text-[#1b3322]">
                                        @foreach($order->items as $item)
                                            <span class="block">{{ $item->product->name ?? 'Premium Batch' }}</span>
                                        @endforeach
                                    </td>
                                    <td class="p-4">
                                        @foreach($order->items as $item)
                                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold">
                                                {{ number_format($item->quantity_kg, 0) }} KG
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="p-4 font-black text-gray-900">
                                        Rs. {{ number_format($order->total_amount, 0) }}
                                    </td>
                                    <td class="p-4 text-xs text-gray-500 max-w-xs truncate">
                                        {{ $order->shipping_address }}
                                    </td>
                                    <td class="p-3 text-center align-middle">
                                        @if($order->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                ⏳ Pending
                                            </span>
                                        @elseif($order->status === 'dispatched' || $order->status === 'shipped')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">
                                                🚚 Dispatched
                                            </span>
                                        @elseif($order->status === 'delivered')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✅ Delivered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-200">
                                                📦 {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="mt-8 p-6 bg-[#fcf9f2] text-center rounded border border-dashed border-gray-200">
                        <p class="text-sm text-gray-500 italic">More features for customers dashboard Coming Soon...</p>
                    </div>
            @endif
        </div>
    </div>

    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>
    <script>
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `transform translate-x-full transition-all duration-300 ease-out p-4 rounded shadow-lg max-w-sm flex items-center gap-3 text-white pointer-events-auto ${
            type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
        }`;
        
        const icon = type === 'success' ? '✨' : '⚠️';
        toast.innerHTML = `
            <span class="text-lg">${icon}</span>
            <p class="text-xs font-semibold uppercase tracking-wider">${message}</p>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    @if(session('success'))
        document.addEventListener("DOMContentLoaded", function() {
            showToast("{{ session('success') }}", 'success');
        });
    @endif

    @if(session('error'))
        document.addEventListener("DOMContentLoaded", function() {
            showToast("{{ session('error') }}", 'error');
        });
    @endif
    </script>
    </body>
</html>