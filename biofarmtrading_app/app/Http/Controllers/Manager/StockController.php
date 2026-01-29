<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display the stock levels of the manager's shop.
     */
    public function index()
    {
        $user = auth()->user();
        $shop = $user->shop;
        
        if (!$shop) {
            abort(403, 'Aucune boutique assignée.');
        }

        // Get products with stock for this specific shop
        $products = Product::whereHas('shops', function($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with(['shops' => function($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        }])->get();

        // Get recent stock movements for this shop (supplies, sales, adjustments)
        $movements = StockMovement::where('shop_id', $shop->id)
            ->with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('manager.stock.index', compact('shop', 'products', 'movements'));
    }

    /**
     * Report a stock adjustment (loss, damage, etc).
     * Managers usually should not INCREASE stock arbitrarily (that's supply), so this might be negative only or explicitly labeled 'adjustment'.
     */
    public function adjust(Request $request)
    {
        $user = auth()->user();
        $shop = $user->shop;

        if (!$shop) {
            abort(403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer', // Can be positive (found extra) or negative (loss)
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        DB::beginTransaction();

        try {
            // Get current quantity
            $currentStock = 0;
            $pivot = $product->shops()->where('shop_id', $shop->id)->first();
            
            if ($pivot) {
                $currentStock = $pivot->pivot->quantity;
            }

            $newStock = $currentStock + $request->quantity;

            if ($newStock < 0) {
                 throw new \Exception("Le stock ne peut pas être négatif.");
            }

            // Update quantity
            $product->shops()->syncWithoutDetaching([
                $shop->id => ['quantity' => $newStock]
            ]);

            // Log movement
            StockMovement::create([
                'product_id' => $product->id,
                'shop_id' => $shop->id,
                'user_id' => $user->id,
                'quantity' => $request->quantity,
                'type' => $request->quantity > 0 ? 'adjustment_add' : 'adjustment_loss', 
                'note' => $request->reason
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Ajustement de stock enregistré.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
