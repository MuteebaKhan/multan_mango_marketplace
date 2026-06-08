<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List New Harvest - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-gray-900 min-h-screen py-12 px-4">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-normal text-[#1b3322] serif-heading uppercase tracking-wide">List New Mango Batch</h2>
            <p class="text-xs italic text-[#c59d3f] tracking-widest uppercase font-medium mt-1">Introduce your fresh harvest to royal buyers</p>
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

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-5">  
            @csrf

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Mango Variety / Category</label>
                <select name="category_id" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                    <option value="" disabled selected>Select Variety (e.g., Chaunsa, Sindhri)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>


            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Batch Title / Product Name</label>
                <input type="text" name="name" placeholder="e.g., Export Quality Anwar Ratol (A-Grade)" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Price (PKR per KG)</label>
                    <input type="number" name="price_per_kg" step="0.01" placeholder="e.g., 350" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>




                
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Available Stock (in KGs)</label>
                    <input type="number" name="stock_quantity_kg" placeholder="e.g., 500" required class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]">
                </div>
            </div>

            <div>
    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Upload Harvest Image</label>
    <div class="flex items-center justify-center w-full">
        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-3 text-[#c59d3f]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="text-xs text-gray-500 font-semibold">Click to upload mango photo</p>
                <p class="text-[10px] text-gray-400 mt-1">PNG, JPG or JPEG (Max: 2MB)</p>
            </div>
            <input type="file" name="image_url" accept="image/*" required class="hidden" />
        </label>
    </div>
</div>

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-1">Orchard Notes / Description</label>
                <textarea name="description" rows="4" placeholder="Describe the sweetness, size, and harvest date of this batch..." class="w-full px-4 py-2 border border-gray-200 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#c59d3f]"></textarea>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-sm">
                <a class="text-xs text-gray-400 font-semibold hover:text-gray-600 uppercase tracking-wider" href="{{ route('dashboard') }}">&larr; Back to Dashboard</a>
                <button type="submit" class="px-6 py-2.5 bg-[#1b3322] hover:bg-[#132418] text-[#e2b653] font-bold text-xs tracking-widest rounded transition uppercase">Publish Listing</button>
            </div>
        </form>
    </div>

</body>
</html>