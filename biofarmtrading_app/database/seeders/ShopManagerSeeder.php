<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ShopManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Retrieve Shops
        $shopCacaveli = Shop::where('name', 'Boutique Cacaveli')->first();
        if (!$shopCacaveli) {
            $shopCacaveli = Shop::create([
                'name' => 'Boutique Cacaveli',
                'address' => 'Cacaveli, Lomé, Togo',
                'phone' => '+228 90 00 00 01'
            ]);
        }

        $shopHedzranawoe = Shop::where('name', 'Boutique Hedzranawoe')->first();
        if (!$shopHedzranawoe) {
            $shopHedzranawoe = Shop::create([
                'name' => 'Boutique Hedzranawoe',
                'address' => 'Hedzranawoe, Lomé, Togo',
                'phone' => '+228 90 00 00 02'
            ]);
        }

        // 2. Create Managers
        // Cacaveli Manager
        User::updateOrCreate(
            ['email' => 'cacaveli@biofarm.com'],
            [
                'name' => 'Manager Cacaveli',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'shop_id' => $shopCacaveli->id,
            ]
        );

        // Hedzranawoe Manager
        User::updateOrCreate(
            ['email' => 'hedzranawoe@biofarm.com'],
            [
                'name' => 'Manager Hedzranawoe',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'shop_id' => $shopHedzranawoe->id,
            ]
        );

        echo "Managers created successfully.\n";

        // 3. Initialize Inventory for all products
        // Distribute stock equally or set a default.
        // We will assume 50 items for each product in each shop for start.

        $products = Product::all();
        
        foreach ($products as $product) {
            // Check if already in pivot to avoid resetting on subsequent runs if not desired
            // But updateOrCreate is safe for 'ensure it exists'
            
            // Cacaveli
            if (!$shopCacaveli->products()->where('product_id', $product->id)->exists()) {
                $shopCacaveli->products()->attach($product->id, ['quantity' => 50]);
            }

            // Hedzranawoe
            if (!$shopHedzranawoe->products()->where('product_id', $product->id)->exists()) {
                 $shopHedzranawoe->products()->attach($product->id, ['quantity' => 50]);
            }
        }
        
        echo "Inventory initialized for " . $products->count() . " products in both shops.\n";
    }
}
