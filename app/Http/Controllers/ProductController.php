<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Product::with('category');

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = \App\Models\Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
