<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        $shops = \App\Models\Shop::all();
        return view('checkout.index', compact('cart', 'shops'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,tmoney,flooz',
            'shop_id' => 'required|exists:shops,id',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_type,delivery',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Commande impossible, panier vide.');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'delivery_address' => $request->delivery_address,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        foreach($cart as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);
        }

        session()->forget('cart');

        event(new \App\Events\OrderCreated($order));

        return redirect()->route('order.success', $order->id)->with('success', 'Votre commande a été enregistrée avec succès !');
    }

    public function success($id)
    {
        $order = \App\Models\Order::with('items.product')->findOrFail($id);

        // Security: Check if order belongs to user or user has special roles
        $user = auth()->user();
        if ($user) {
            if ($order->user_id !== $user->id && !in_array($user->role, ['admin', 'manager', 'rh'])) {
                abort(403, 'Accès non autorisé à cette commande.');
            }
        } else {
            // For guests, we could use session check, but if user is not logged in 
            // and auth is required for checkout, then we reject.
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
