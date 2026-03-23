<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('category')->orderBy('name')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:empanada,papa_rellena',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'active' => 'boolean',
        ]);

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:empanada,papa_rellena',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->hasSales()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'No se puede eliminar el producto porque tiene ventas registradas.');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
