<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;

class StockController extends Controller
{
    /**
     * Show global stock overview and supply form.
     */
    public function index()
    {
        $products = Product::with(['shops'])->orderBy('name')->get();
        $shops = Shop::all();
        
        // Latest movements for history
        $movements = StockMovement::with(['product', 'shop', 'user'])
                        ->latest()
                        ->paginate(20);

        return view('admin.stock.index', compact('products', 'shops', 'movements'));
    }

    /**
     * Add supply to a specific shop.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $product = Product::findOrFail($request->product_id);
        
        DB::beginTransaction();

        try {
            // Get current quantity
            $currentStock = 0;
            $pivot = $product->shops()->where('shop_id', $shop->id)->first();
            
            if ($pivot) {
                $currentStock = $pivot->pivot->quantity;
            }

            // Update quantity with new supply
            $product->shops()->syncWithoutDetaching([
                $shop->id => ['quantity' => $currentStock + $request->quantity]
            ]);

            // Log movement
            StockMovement::create([
                'product_id' => $product->id,
                'shop_id' => $shop->id,
                'user_id' => auth()->id(),
                'quantity' => $request->quantity,
                'type' => 'supply', // Approvisionnement
                'note' => $request->note
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Stock approvisionné avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de l\'approvisionnement : ' . $e->getMessage());
        }
    }

    /**
     * Display Monthly Supply Report
     */
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $shops = Shop::all();
        $products = Product::orderBy('name')->get();

        // Get supplies for the selected month/year
        $supplies = StockMovement::where('type', 'supply')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        // Organize data: [product_id][shop_id] = total_quantity
        $data = [];
        foreach ($supplies as $supply) {
            if (!isset($data[$supply->product_id])) {
                $data[$supply->product_id] = [];
            }
            if (!isset($data[$supply->product_id][$supply->shop_id])) {
                $data[$supply->product_id][$supply->shop_id] = 0;
            }
            $data[$supply->product_id][$supply->shop_id] += $supply->quantity;
        }

        return view('admin.stock.monthly_report', compact('shops', 'products', 'data', 'month', 'year'));
    }

    /**
     * Show Bulk Supply Sheet Form (Bon de Sortie style)
     */
    public function supplySheet()
    {
        $products = Product::orderBy('name')->get();
        $shops = Shop::all();
        return view('admin.stock.supply_sheet', compact('products', 'shops'));
    }

    /**
     * Handle Bulk Supply Storage & PDF Generation
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'destination_name' => 'nullable|string',
            'bon_numero' => 'nullable|string|max:50',
            'products' => 'required|array',
            'products.*' => 'nullable|integer|min:0',
        ]);

        $shop = null;
        if ($request->shop_id) {
            $shop = Shop::find($request->shop_id);
        }
        $destinationName = $shop ? $shop->name : ($request->destination_name ?? 'Client Inconnu');

        $bonNumero = $request->bon_numero; // Optional reference

        DB::beginTransaction();
        try {
            $count = 0;
            $items = []; // For PDF

            foreach ($request->products as $productId => $quantity) {
                if ($quantity > 0) {
                    $product = Product::findOrFail($productId);
                    
                    // Only update stock if it's a valid internal Shop
                    if ($shop) {
                        // Get current stock
                        $pivot = $product->shops()->where('shop_id', $shop->id)->first();
                        $currentStock = $pivot ? $pivot->pivot->quantity : 0;

                        // Update stock (ADD for supply)
                        $product->shops()->syncWithoutDetaching([
                            $shop->id => ['quantity' => $currentStock + $quantity]
                        ]);

                        // Log movement
                        StockMovement::create([
                            'product_id' => $product->id,
                            'shop_id' => $shop->id,
                            'user_id' => auth()->id(),
                            'quantity' => $quantity,
                            'type' => 'supply',
                            'note' => $bonNumero ? "Bon de sortie N° $bonNumero" : 'Approvisionnement groupé'
                        ]);
                    }
                    
                    $items[] = [
                        'name' => $product->name,
                        'quantity' => $quantity
                    ];
                    $count++;
                }
            }

            DB::commit();

            if ($count == 0) {
                return back()->with('error', 'Aucune quantité saisie.');
            }

            // Generate PDF
            $pdf = Pdf::loadView('admin.stock.pdf_bon_sortie', compact('items', 'destinationName', 'bonNumero'))
                      ->setPaper('a5', 'portrait');
            $fileName = 'Bon_Sortie_' . ($bonNumero ? $bonNumero : date('dmY_His')) . '.pdf';
            
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
