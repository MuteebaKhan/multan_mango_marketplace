<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Farm; // <-- New Import for Sidebar Farms List
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    
    public function index(Request $request) 
    {
        $farms = Farm::all();

        $query = Product::query()->latest();

        if ($request->has('farm') && $request->farm != '') {
            $query->where('farm_id', $request->farm);
        }

        $products = $query->paginate(6)->withQueryString(); 

        return view('products.index', compact('products', 'farms'));  
    }

    
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    
    public function store(Request $request)
{
    $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'grade' => ['required', 'string', 'in:royal,export,premium'], 
        'price_per_kg' => ['required', 'numeric', 'min:0'],
        'stock_quantity_kg' => ['required', 'integer', 'min:0'],
        'harvest_date' => ['required', 'date'], 
        'image_url' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], 
    ]);

    $vendorFarm = Auth::user()->farm;

    if (!$vendorFarm) {
        return redirect()->route('dashboard')->with('error', 'No registered Farm found.');
    }

    $imagePath = null;
    if ($request->hasFile('image_url')) {
        $imagePath = $request->file('image_url')->store('products', 'public');
    }

    $vendorFarm->products()->create([
        'category_id' => $request->category_id,
        'grade' => $request->grade, // Save Grade
        'name' => $request->name,
        'description' => $request->description,
        'price_per_kg' => $request->price_per_kg,
        'stock_quantity_kg' => $request->stock_quantity_kg,
        'harvest_date' => $request->harvest_date, 
        'image_url' => $imagePath,
    ]);

    return redirect()->route('dashboard')->with('success', '✨ Premium Mango Batch successfully listed with Quality Grade!');
}

    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); 
        return view('products.edit', compact('product', 'categories'));
    }

    
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'price_per_kg'      => 'required|numeric|min:0',
            'stock_quantity_kg' => 'required|numeric|min:0',
            'description'       => 'required|string|min:10',
            'image_url'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            $path = $request->file('image_url')->store('products', 'public');
            $data['image_url'] = $path;
        } else {
            unset($data['image_url']);
        }

        $product->update($data);

        return redirect()->route('dashboard')->with('success', 'Mango batch updated successfully!');
    }

    
    public function destroy(Product $product)
    {
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }
    
        $product->delete();
    
        return redirect()->route('products.index')->with('success', 'Product has been removed from the vault.');
    }
}