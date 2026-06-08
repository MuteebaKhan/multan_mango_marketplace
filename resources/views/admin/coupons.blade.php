<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons - Multan Mango Treasures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfbf7; }</style>
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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-emerald-900/40 hover:text-white rounded-lg text-xs font-medium tracking-wide transition">
                        <i class="fa-solid fa-chart-pie text-sm"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[#c59d3f]/10 text-amber-400 rounded-lg text-xs font-semibold tracking-wide transition">
                        <i class="fa-solid fa-ticket text-sm"></i> Vouchers / Coupons
                    </a>
                </nav>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-6 md:px-8 shrink-0">
                <h2 class="text-md font-bold text-[#1b3322] uppercase tracking-wider">Coupons Management Engine</h2>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-[#1b3322]">{{ auth()->user()->name ?? 'Royal Admin' }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl flex items-center gap-2 shadow-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs h-fit">
                        <h3 class="text-sm font-bold text-[#1b3322] uppercase tracking-wider mb-4"><i class="fa-solid fa-plus-circle mr-1"></i> Issue New Voucher</h3>
                        
                        <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Coupon Code</label>
        <input type="text" name="code" placeholder="e.g., MANGO10" required class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b3322] font-mono uppercase">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Discount Type</label>
            <select name="type" required class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b3322]">
                <option value="percentage">Percentage (%)</option>
                <option value="fixed">Fixed (PKR)</option>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Value</label>
            <input type="number" step="0.01" name="value" placeholder="10 or 250" required class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b3322]">
        </div>
    </div>

    <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Min Order Amount (PKR)</label>
        <input type="number" step="0.01" name="min_order_amount" value="0" class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b3322]">
    </div>

    <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Expiry Date</label>
        <input type="date" name="expiry_date" required class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-[#1b3322]">
    </div>

    <button type="submit" class="w-full py-2 bg-[#1b3322] text-white hover:bg-[#c59d3f] rounded-lg font-bold text-xs tracking-wider uppercase transition mt-2 shadow-xs">
        Activate Coupon
    </button>
</form>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden lg:col-span-2">
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-sm font-bold text-[#1b3322] uppercase tracking-wider">Active System Vault</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse whitespace-nowrap">
                                <thead>
                                    <tr class="border-b border-gray-100 text-[10px] font-bold text-gray-400 bg-gray-50 uppercase tracking-wider">
                                        <th class="py-3.5 px-5">Code</th>
                                        <th class="py-3.5 px-4">Type</th>
                                        <th class="py-3.5 px-4">Discount Value</th>
                                        <th class="py-3.5 px-4">Min Order</th>
                                        <th class="py-3.5 px-4">Expiry</th>
                                        <th class="py-3.5 px-5 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-xs">
                                    @forelse($coupons as $coupon)
                                        <tr class="hover:bg-gray-50/70 transition">
                                            <td class="py-4 px-5 font-mono font-bold text-gray-800 tracking-wide"><i class="fa-solid fa-tag text-amber-500 mr-1.5"></i>{{ $coupon->code }}</td>
                                            <td class="py-4 px-4 uppercase font-semibold text-gray-500">{{ $coupon->type }}</td>
                                            <td class="py-4 px-4 font-bold text-gray-700 font-mono">{{ $coupon->type == 'percentage' ? $coupon->value.'%' : 'Rs. '.number_format($coupon->value, 2) }}</td>
                                            <td class="py-4 px-4 text-gray-600 font-mono">Rs. {{ number_format($coupon->min_order_amount, 2) }}</td>
                                            <td class="py-4 px-4 text-gray-500 font-mono">{{ $coupon->expiry_date }}</td>
                                            <td class="py-4 px-5 text-center">
                                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Kya aap waqai is coupon ko delete karna chahte hain?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-gray-400 font-medium">
                                                <i class="fa-solid fa-ticket block text-2xl mb-2 text-gray-300"></i> No Coupons Available! Create a new one.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>