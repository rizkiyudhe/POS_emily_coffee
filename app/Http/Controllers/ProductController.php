<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

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
        $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
        ]);
        $product = Product::create($request->all());
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
        $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'price' => 'required|integer',
        ]);
        $product->update($request->all());
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
