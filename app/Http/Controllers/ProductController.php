<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        $query = Product::with('category');
        if ($request->search) $query->where('name', 'like', "%{$request->search}%")->orWhere('sku', 'like', "%{$request->search}%");
        if ($request->category_id) $query->where('category_id', $request->category_id);
        $products = $query->paginate(10);
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'stock' => 'integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }
        $product = Product::create($validated);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'create product', 'description' => "Tambah produk {$product->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('products.index')->with('success', 'Produk ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'price' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product->update($validated);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'update product', 'description' => "Update produk {$product->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('products.index')->with('success', 'Produk diupdate');
    }

    public function destroy(Product $product, Request $request)
    {
        $product->delete();
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'delete product', 'description' => "Hapus produk {$product->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('products.index')->with('success', 'Produk dihapus');
    }
}
