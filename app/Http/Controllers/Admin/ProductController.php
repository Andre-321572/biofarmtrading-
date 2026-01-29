<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // Only admin can create products
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
             abort(403, 'Accès refusé.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            // 'stock' => 'required|integer|min:0', // Global stock might differ from shop stock
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);
        $data['stock'] = 0; // Default global stock or sum? keeping 0 for now as we use shops.

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product = Product::create($data);

        // Initialize stock for all shops to 0
        $shops = \App\Models\Shop::all();
        foreach ($shops as $shop) {
             $product->shops()->attach($shop->id, ['quantity' => 0]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        
        // If manager, load their specific stock
        $shopStock = 0;
        if (auth()->user()->role === 'manager' && auth()->user()->shop_id) {
            $shopProduct = $product->shops()->where('shop_id', auth()->user()->shop_id)->first();
            $shopStock = $shopProduct ? $shopProduct->pivot->quantity : 0;
        }

        return view('admin.products.edit', compact('product', 'categories', 'shopStock'));
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();

        // Rules depend on role
        if ($user->role === 'admin') {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                //'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $data = $request->except('image');
            $data['slug'] = Str::slug($request->name);

            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $path = $request->file('image')->store('products', 'public');
                $data['image_path'] = $path;
            }

            $product->update($data);

        } elseif ($user->role === 'manager') {
            // Manager only updates stock
            $request->validate([
                'stock' => 'required|integer|min:0',
            ]);
            
            if ($user->shop_id) {
                // Update pivot
                // Check if attached
                if (!$product->shops()->where('shop_id', $user->shop_id)->exists()) {
                    $product->shops()->attach($user->shop_id, ['quantity' => $request->stock]);
                } else {
                    $product->shops()->updateExistingPivot($user->shop_id, ['quantity' => $request->stock]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit/Stock mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }
}
