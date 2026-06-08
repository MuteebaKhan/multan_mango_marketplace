<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join The Vault - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-gray-900 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="max-w-2xl w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-normal text-[#1b3322] serif-heading uppercase tracking-wide">Create Royal Account</h2>
            <p class="text-xs italic text-[#c59d3f] tracking-widest uppercase font-medium mt-1">Register to explore or list premium Multani mango varieties</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">I want to register as a:</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center justify-center p-3 border rounded cursor-pointer transition hover:bg-gray-50 border-gray-200">
                        <input type="radio" name="role" value="customer" checked onclick="toggleFarmFields(false)" class="mr-2 accent-[#c59d3f]">
                        <span class="text-sm font-medium text-gray-700">Mango Buyer / Customer</span>
                    </label>
                    <label class="flex items-center justify-center p-3 border rounded cursor-pointer transition hover:bg-gray-50 border-gray-200">
                        <input type="radio" name="role" value="vendor" onclick="toggleFarmFields(true)" class="mr-2 accent-[#c59d3f]">
                        <span class="text-sm font-medium text-gray-700">Mango Orchard Vendor</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Phone Number (Optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Address (Optional)</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
            </div>

            <div id="vendor_fields" class="hidden p-5 bg-[#fcf9f2] rounded-lg border border-[#c59d3f]/20 space-y-4">
                <h4 class="text-xs font-bold text-[#1b3322] tracking-wider uppercase border-b border-gray-200 pb-1">Orchard / Farm Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Registered Farm Name</label>
                        <input type="text" id="farm_name" name="farm_name" placeholder="e.g., Al-Rehman Mango Farm" class="w-full px-4 py-2 bg-white border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Orchard Location / City</label>
                        <input type="text" id="farm_location" name="farm_location" placeholder="e.g., Jalalpur Pirwala, Multan" class="w-full px-4 py-2 bg-white border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-sm">
                <a class="text-xs text-gray-400 font-semibold hover:text-gray-600 uppercase tracking-wider" href="{{ route('login') }}">Already registered?</a>
                <button type="submit" class="px-6 py-2.5 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest rounded transition uppercase">Register Account</button>
            </div>
        </form>
    </div>

    <script>
        function toggleFarmFields(show) {
            const fieldsDiv = document.getElementById('vendor_fields');
            const nameInput = document.getElementById('farm_name');
            const locInput = document.getElementById('farm_location');
            
            if (show) {
                fieldsDiv.classList.remove('hidden');
                nameInput.setAttribute('required', 'required');
                locInput.setAttribute('required', 'required');
            } else {
                fieldsDiv.classList.add('hidden');
                nameInput.removeAttribute('required');
                locInput.removeAttribute('required');
                nameInput.value = '';
                locInput.value = '';
            }
        }
    </script>
</body>
</html>
