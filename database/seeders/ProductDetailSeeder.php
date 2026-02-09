<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductDetailSeeder extends Seeder
{
    public function run()
    {
        // 330ml Bottles
        Product::where('name', 'like', '%330ml%')->update([
            'price_detail' => 500,
            'units_per_case' => 16
        ]);

        // 1L Bottles
        // joossi ananas biologique 1700Fcfa
        Product::where('name', 'like', '%JOOSSI%Ananas%')
               ->where('name', 'like', '%1L%')
               ->where('name', 'not like', '%gingembre%')
               ->where('name', 'not like', '%Épicé%')
               ->where('name', 'not like', '%bissap%')
               ->update([
                   'price_detail' => 1700,
                   'units_per_case' => 6
               ]);

        // joossi ananas-gingembre biologique 2000Fcfa
        Product::where('name', 'like', '%JOOSSI%Ananas%Gingembre%')
               ->where('name', 'like', '%1L%')
               ->update([
                   'price_detail' => 2000,
                   'units_per_case' => 6
               ]);

        // joossi ananas-Épicé biologique 2000 Fcfa
        Product::where('name', 'like', '%JOOSSI%Ananas%Épicé%')
               ->where('name', 'like', '%1L%')
               ->update([
                   'price_detail' => 2000,
                   'units_per_case' => 6
               ]);

        // joossi ananas-bissap biologique 2000 Fcfa
        Product::where('name', 'like', '%JOOSSI%Ananas%Bissap%')
               ->where('name', 'like', '%1L%')
               ->update([
                   'price_detail' => 2000,
                   'units_per_case' => 6
               ]);
               
        // Generic fallback for any other 1L JOOSSI
        Product::where('name', 'like', '%JOOSSI%')
               ->where('name', 'like', '%1L%')
               ->whereNull('price_detail')
               ->update([
                   'price_detail' => 1700,
                   'units_per_case' => 6
               ]);
    }
}
