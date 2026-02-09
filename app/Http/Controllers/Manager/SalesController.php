<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display current shop sales, form to add new sale manually.
     */
    public function index()
    {
        $user = auth()->user();

        // Ensure user is manager with shop
        if (!$user->shop_id) {
            abort(403, 'Aucune boutique assignée.');
        }

        $shop = $user->shop;

        // Get Pending Orders (from website)
        $pendingOrders = Order::where('shop_id', $shop->id)
                              ->where('status', 'pending')
                              ->with(['items.product'])
                              ->latest()
                              ->get();

        // Get Validated/Completed Sales for today
        $validatedOrders = Order::where('shop_id', $shop->id)
                             ->whereIn('status', ['delivered', 'completed'])
                             ->whereDate('updated_at', Carbon::today()) // Use updated_at for when it was validated/sold
                             ->with(['items.product'])
                             ->latest()
                             ->get();

        // Calculate today's total (only validated)
        $todaysTotal = $validatedOrders->sum('total_amount');

        // Products for sales form
        $products = Product::whereHas('shops', function($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        })->with(['shops' => function($q) use ($shop) {
            $q->where('shop_id', $shop->id);
        }])->get();


        return view('manager.sales.index', compact('shop', 'pendingOrders', 'validatedOrders', 'todaysTotal', 'products'));
    }

    /**
     * Validate a pending order.
     */
    public function validateOrder(Request $request, $orderId)
    {
        $user = auth()->user();
        if (!$user->shop_id) {
            abort(403);
        }
        $shop = $user->shop;
        
        $order = Order::where('id', $orderId)->where('shop_id', $shop->id)->firstOrFail();

        if ($order->status !== 'pending') {
            return back()->with('error', 'Cette commande a déjà été traitée.');
        }

        DB::beginTransaction();
        try {
            // Check and Decrement Stock
            foreach ($order->items as $item) {
                $product = $item->product;
                $shopProduct = $product->shops()->where('shop_id', $shop->id)->first();
                $currentStock = $shopProduct ? $shopProduct->pivot->quantity : 0;

                if ($currentStock < $item->quantity) {
                    throw new \Exception("Stock insuffisant pour " . $product->name . " (Requis: " . $item->quantity . ", Dispo: " . $currentStock . ")");
                }

                // Decrement
                $product->shops()->updateExistingPivot($shop->id, [
                    'quantity' => $currentStock - $item->quantity
                ]);
            }

            // Update Status
            $order->update([
                'status' => 'delivered', // Or 'completed', 'delivered' implies handed over
                'payment_status' => 'paid', // Assuming payment is collected on validation/delivery for now
                'updated_at' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Commande validée et stock mis à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur de validation: ' . $e->getMessage());
        }
    }

    /**
     * Store a manual sale made in store.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->shop_id) {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:cash,tmoney,flooz',
        ]);

        $shop = $user->shop;
        $totalAmount = 0;
        $orderItems = [];

        DB::beginTransaction();

        try {
            // Create Order
            $order = Order::create([
                'user_id' => $user->id, // Sold by manager
                'shop_id' => $shop->id,
                'status' => 'delivered', // Immediate sale
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'total_amount' => 0, // Calculated below
                'customer_name' => $request->customer_name ?? 'Client Magasin',
                'customer_phone' => $request->customer_phone ?? '',
                'delivery_address' => 'Vente au comptoir',
            ]);

            foreach ($request->items as $itemData) {
                if($itemData['quantity'] > 0) {
                     $product = Product::findOrFail($itemData['product_id']);
                     $unitType = $itemData['unit_type'] ?? 'carton';
                     $price = ($unitType === 'detail' && $product->price_detail) ? $product->price_detail : $product->price;
                     $quantityToDecrement = ($unitType === 'carton') ? ($product->units_per_case ?: 1) * $itemData['quantity'] : $itemData['quantity'];
                     
                     // Check Stock
                     $shopProduct = $product->shops()->where('shop_id', $shop->id)->first();
                     $currentStock = $shopProduct ? $shopProduct->pivot->quantity : 0;

                     if ($currentStock < $quantityToDecrement) {
                         throw new \Exception("Stock insuffisant pour " . $product->name . " (Requis: " . $quantityToDecrement . ", Dispo: " . $currentStock . ")");
                     }

                     // Decrement Stock
                     $product->shops()->updateExistingPivot($shop->id, [
                         'quantity' => $currentStock - $quantityToDecrement
                     ]);

                     // Add Order Item
                     $subtotal = $price * $itemData['quantity'];
                     $totalAmount += $subtotal;

                     \App\Models\OrderItem::create([
                         'order_id' => $order->id,
                         'product_id' => $product->id,
                         'quantity' => $itemData['quantity'],
                         'unit_type' => $unitType,
                         'price' => $price
                     ]);
                }
            }

            if ($totalAmount == 0) {
                throw new \Exception("La commande est vide.");
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('manager.sales.index')->with('success', 'Vente enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la vente: ' . $e->getMessage());
        }
    }

    /**
     * Monthly Report View
     */
    /**
     * Monthly Report View
     */
    public function report()
    {
        $user = auth()->user();
         if (!$user->shop_id) {
            abort(403);
        }

        $shopId = $user->shop_id;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Statistics per product and unit_type for the month
        $productStats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.shop_id', $shopId)
            ->whereMonth('orders.created_at', $currentMonth)
            ->whereYear('orders.created_at', $currentYear)
            ->select(
                'products.name', 
                'order_items.unit_type',
                DB::raw('SUM(order_items.quantity) as total_qty'), 
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'order_items.unit_type')
            ->orderBy('products.name')
            ->get();

        $monthTotal = $productStats->sum('total_revenue');

        return view('manager.sales.report', compact('productStats', 'monthTotal'));
    }

    /**
     * Download Monthly Report as PDF
     */
    public function downloadPDF()
    {
        $user = auth()->user();
         if (!$user->shop_id) {
            abort(403);
        }

        $shop = $user->shop;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Reuse logic (could be extracted to service)
        $productStats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.shop_id', $shop->id)
            ->whereMonth('orders.created_at', $currentMonth)
            ->whereYear('orders.created_at', $currentYear)
            ->select(
                'products.name', 
                'order_items.unit_type',
                DB::raw('SUM(order_items.quantity) as total_qty'), 
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'order_items.unit_type')
            ->orderBy('products.name')
            ->get();

        $monthTotal = $productStats->sum('total_revenue');
        $monthName = Carbon::now()->translatedFormat('F Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('manager.sales.pdf_report', compact('productStats', 'monthTotal', 'shop', 'monthName'));
        
        return $pdf->download('rapport_vente_'.$shop->name.'_'.date('m_Y').'.pdf');
    }
}
