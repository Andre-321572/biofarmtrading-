<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Bio Farm',
            'email' => 'admin@biofarmtrading.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        // Shops
        \App\Models\Shop::create([
            'name' => 'Boutique Cacaveli',
            'address' => 'Cacaveli, Lomé, Togo',
            'phone' => '+228 90 00 00 01'
        ]);

        \App\Models\Shop::create([
            'name' => 'Boutique Hedzranawoe',
            'address' => 'Hedzranawoe, Lomé, Togo',
            'phone' => '+228 90 00 00 02'
        ]);

        // Categories
        $driedFruits = \App\Models\Category::create([
            'name' => 'Fruits Séchés',
            'slug' => 'fruits-seches',
            'description' => 'Fruits 100% naturels séchés sans conservateurs.'
        ]);

        $juices = \App\Models\Category::create([
            'name' => 'Jus de Fruits',
            'slug' => 'jus-de-fruits',
            'description' => 'Jus de fruits 100% naturels pressés à froid.'
        ]);

        // Products
        \App\Models\Product::create([
            'category_id' => $driedFruits->id,
            'name' => 'Ananas Séché Bio',
            'slug' => 'ananas-seche-bio',
            'description' => 'Tranches d\'ananas Cayenne Lisse séchées naturellement, certifiées ECOCERT. 100% naturel sans sucre ajouté.',
            'price' => 1500,
            'stock' => 100,
        ]);

        \App\Models\Product::create([
            'category_id' => $driedFruits->id,
            'name' => 'Mangue Séchée Bio',
            'slug' => 'mangue-sechee-bio',
            'description' => 'Mangues biologiques séchées au soleil. Un goût intense et une texture fondante.',
            'price' => 2000,
            'stock' => 50,
        ]);

        \App\Models\Product::create([
            'category_id' => $driedFruits->id,
            'name' => 'Banane Séchée Bio',
            'slug' => 'banane-sechee-bio',
            'description' => 'Rondelles de bananes biologiques séchées. Riche en potassium et en énergie.',
            'price' => 1200,
            'stock' => 60,
        ]);

        \App\Models\Product::create([
            'category_id' => $driedFruits->id,
            'name' => 'Papaye Séchée Bio',
            'slug' => 'papaye-sechee-bio',
            'description' => 'Papaye biologique séchée, riche en fibres et en vitamine C.',
            'price' => 1500,
            'stock' => 80,
        ]);

        \App\Models\Product::create([
            'category_id' => $juices->id,
            'name' => 'Pur Jus d\'Ananas Bio',
            'slug' => 'jus-ananas-bio',
            'description' => 'Jus d\'ananas Cayenne Lisse pur jus, certifié Bio et HACCP. Sans conservateurs.',
            'price' => 1000,
            'stock' => 200,
        ]);

        \App\Models\Product::create([
            'category_id' => $driedFruits->id,
            'name' => 'Citronnelle Séchée Bio',
            'slug' => 'citronnelle-sechee-bio',
            'description' => 'Feuilles de citronnelle séchées pour infusions relaxantes et parfumées.',
            'price' => 1000,
            'stock' => 120,
        ]);
    }
}
