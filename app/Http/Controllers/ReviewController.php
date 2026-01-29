<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Requires admin moderation
        ]);

        return redirect()->back()->with('success', 'Merci ! Votre avis a été publié.');
    }
}
