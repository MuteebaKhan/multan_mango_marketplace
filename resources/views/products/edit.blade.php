<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mango Batch - Multan Mango Treasures</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght=400;700&family=Plus+Jakarta+Sans:wght=400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#fcf9f2] text-gray-900 py-12">

    <div class="max-w-3xl mx-auto p-8 bg-white rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-3xl font-normal text-[#1b3322] serif-heading mb-1 uppercase tracking-wide">Modify Mango Batch</h2>
        <p class="text-xs italic text-[#c59d3f] tracking-widest uppercase font-medium mb-8">Update pricing, stock, or visuals for {{ $product->name }}</p>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Mango Variety / Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded focus:ring-1 focus:ring-[#c59d3f] focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Price per KG (PKR)</label>
                    <input type="number" step="0.01" name="price_per_kg" value="{{ old('price_per_kg', $product->price_per_kg) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded focus:ring-1 focus:ring-[#c59d3f] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Total Available Stock (KG)</label>
                    <input type="number" step="0.01" name="stock_quantity_kg" value="{{ old('stock_quantity_kg', $product->stock_quantity_kg) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded focus:ring-1 focus:ring-[#c59d3f] focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Mango Type / Category</label>
                <select name="category_id" required class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded focus:ring-1 focus:ring-[#c59d3f] focus:outline-none text-gray-700">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            👑 {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Product Description</label>
                <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-gray-200 rounded focus:ring-1 focus:ring-[#c59d3f] focus:outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Mango Batch Image (Leave empty to keep current)</label>
                
                @if($product->image_url)
                    <div class="flex items-center gap-4 mb-3 p-3 bg-gray-50 rounded border border-gray-100">
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="Current" class="h-16 w-16 object-cover rounded shadow-sm">
                        <span class="text-xs text-gray-400 italic">Current active image on marketplace</span>
                    </div>
                @endif

                <input type="file" name="image" class="w-full px-3 py-2 border border-gray-200 rounded text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>

            <div class="pt-4 flex justify-between items-center border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="text-xs tracking-wider text-gray-400 hover:text-gray-600 font-bold uppercase">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#c59d3f] hover:bg-[#b08732] text-white font-semibold text-xs tracking-widest rounded shadow transition duration-200 uppercase">
                    Update Batch Details
                </button>
            </div>
        </form>
    </div>

</body>
</html>