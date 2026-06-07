<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories']);
        $category = Category::create($request->all());
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create category',
            'description' => "Membuat kategori {$category->name}",
            'ip_address' => $request->ip()
        ]);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id
        ]);
        $category->update($request->all());
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update category',
            'description' => "Update kategori {$category->name}",
            'ip_address' => $request->ip()
        ]);
        return redirect()->route('categories.index')->with('success', 'Kategori diupdate');
    }

    public function destroy(Category $category, Request $request)
    {
        $category->delete();
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete category',
            'description' => "Hapus kategori {$category->name}",
            'ip_address' => $request->ip()
        ]);
        return redirect()->route('categories.index')->with('success', 'Kategori dihapus');
    }
}
