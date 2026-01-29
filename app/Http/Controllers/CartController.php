<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(\Illuminate\Http\Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image_path,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    public function remove(\Illuminate\Http\Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produit retiré du panier.');
        }
    }

    public function update(\Illuminate\Http\Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if ($request->quantity > 0) {
                $cart[$request->id]["quantity"] = $request->quantity;
            } else {
                unset($cart[$request->id]);
            }
            session()->put('cart', $cart);
            session()->flash('success', 'Panier mis à jour.');
        }
    }
}
