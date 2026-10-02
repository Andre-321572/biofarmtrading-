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
        User::updateOrCreate(
            ['email' => 'admin@biofarmtrading.com'],
            [
                'name' => 'Admin Bio Farm',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Achat Coopérative User
        User::updateOrCreate(
            ['email' => 'achat@biofarmtrading.com'],
            [
                'name' => 'Achat Coopérative',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'achat_cooperative',
            ]
        );

        // Responsable Production (RP) User
        $this->call(RpUserSeeder::class);

        // Ressources Humaines (RH) User
        $this->call(RhUserSeeder::class);

        // Ouvriers Bio Farm (Jour & Nuit)
        $this->call(BioFarmWorkersSeeder::class);

        // Shops
        \App\Models\Shop::firstOrCreate(
            ['name' => 'Boutique Cacaveli'],
            [
                'address' => 'Cacaveli, Lomé, Togo',
                'phone' => '+228 90 00 00 01'
            ]
        );

        \App\Models\Shop::firstOrCreate(
            ['name' => 'Boutique Hedzranawoe'],
            [
                'address' => 'Hedzranawoe, Lomé, Togo',
                'phone' => '+228 90 00 00 02'
            ]
        );

        // Categories
        $driedFruits = \App\Models\Category::firstOrCreate(
            ['slug' => 'fruits-seches'],
            [
                'name' => 'Fruits Séchés',
                'description' => 'Fruits 100% naturels séchés sans conservateurs.'
            ]
        );

        $juices = \App\Models\Category::firstOrCreate(
            ['slug' => 'jus-de-fruits'],
            [
                'name' => 'Jus de Fruits',
                'description' => 'Jus de fruits 100% naturels pressés à froid.'
            ]
        );

        // Products
        \App\Models\Product::firstOrCreate(
            ['slug' => 'ananas-seche-bio'],
            [
                'category_id' => $driedFruits->id,
                'name' => 'Ananas Séché Bio',
                'description' => 'Tranches d\'ananas Cayenne Lisse séchées naturellement, certifiées ECOCERT. 100% naturel sans sucre ajouté.',
                'price' => 1500,
                'stock' => 100,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'mangue-sechee-bio'],
            [
                'category_id' => $driedFruits->id,
                'name' => 'Mangue Séchée Bio',
                'description' => 'Mangues biologiques séchées au soleil. Un goût intense et une texture fondante.',
                'price' => 2000,
                'stock' => 50,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'banane-sechee-bio'],
            [
                'category_id' => $driedFruits->id,
                'name' => 'Banane Séchée Bio',
                'description' => 'Rondelles de bananes biologiques séchées. Riche en potassium et en énergie.',
                'price' => 1200,
                'stock' => 60,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'papaye-sechee-bio'],
            [
                'category_id' => $driedFruits->id,
                'name' => 'Papaye Séchée Bio',
                'description' => 'Papaye biologique séchée, riche en fibres et en vitamine C.',
                'price' => 1500,
                'stock' => 80,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'jus-ananas-bio'],
            [
                'category_id' => $juices->id,
                'name' => 'Pur Jus d\'Ananas Bio',
                'description' => 'Jus d\'ananas Cayenne Lisse pur jus, certifié Bio et HACCP. Sans conservateurs.',
                'price' => 1000,
                'stock' => 200,
            ]
        );

        \App\Models\Product::firstOrCreate(
            ['slug' => 'citronnelle-sechee-bio'],
            [
                'category_id' => $driedFruits->id,
                'name' => 'Citronnelle Séchée Bio',
                'description' => 'Feuilles de citronnelle séchées pour infusions relaxantes et parfumées.',
                'price' => 1000,
                'stock' => 120,
            ]
        );
    }
}
