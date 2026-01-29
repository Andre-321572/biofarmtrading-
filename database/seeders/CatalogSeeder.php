<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer les catégories existantes pour éviter les doublons avec le DatabaseSeeder si besoin
        // ou simplement s'assurer que les noms ne clashent pas.
        // Ici on va créer ou récupérer les catégories.

        // =============== 1. JOOSSI - Jus de Fruits Biologiques ===============
        $catJuice = Category::firstOrCreate(
            ['slug' => 'jus-fruits-biologiques'],
            [
                'name' => 'JOOSSI - Jus de Fruits Bio',
                'description' => 'Jus de fruits 100% naturels et biologiques, sans conservateurs (Joossi).'
            ]
        );

        $juiceProducts = [
            [
                'name' => 'JOOSSI Ananas',
                'volume' => '330ml',
                'price' => 400,
                'description' => 'Pur jus d\'ananas biologique certifié. Saveur naturelle.',
            ],
            [
                'name' => 'JOOSSI Ananas',
                'volume' => '1L (Carton)',
                'price' => 9500, // Prix Paquet
                'description' => 'Pur jus d\'ananas biologique certifié. Format familial (Paquet).',
            ],
            [
                'name' => 'JOOSSI Ananas-Gingembre',
                'volume' => '330ml',
                'price' => 500,
                'description' => 'Un mélange tonique d\'ananas et de gingembre bio.',
            ],
            [
                'name' => 'JOOSSI Ananas-Gingembre',
                'volume' => '1L (Carton)',
                'price' => 11500, // Prix Paquet
                'description' => 'Un mélange tonique d\'ananas et de gingembre bio. Format familial (Paquet).',
            ],
            [
                'name' => 'JOOSSI Ananas-Épicé',
                'volume' => '330ml',
                'price' => 500,
                'description' => 'Jus d\'ananas relevé d\'une touche épicée.',
            ],
            [
                'name' => 'JOOSSI Ananas-Épicé',
                'volume' => '1L',
                'price' => 2000,
                'description' => 'Jus d\'ananas relevé d\'une touche épicée. Format 1L.',
            ],
            [
                'name' => 'JOOSSI Ananas-Bissap',
                'volume' => '330ml',
                'price' => 500,
                'description' => 'La douceur de l\'ananas mariée à la fleur d\'hibiscus (Bissap).',
            ],
             [
                'name' => 'JOOSSI Ananas-Bissap',
                'volume' => '1L',
                'price' => 2000,
                'description' => 'La douceur de l\'ananas mariée à la fleur d\'hibiscus (Bissap). Format 1L.',
            ],
        ];

        foreach ($juiceProducts as $p) {
            $fullName = $p['name'] . ' - ' . $p['volume'];
            Product::updateOrCreate(
                ['slug' => Str::slug($fullName)],
                [
                    'name' => $fullName,
                    'category_id' => $catJuice->id,
                    'price' => $p['price'],
                    'stock' => 50, // Stock par défaut
                    'description' => $p['description'],
                    // 'image_path' => null // On laisse null pour le moment
                ]
            );
        }

        // =============== 2. KINU SEC - Fruits Séchés Biologiques ===============
        $catDried = Category::firstOrCreate(
            ['slug' => 'fruits-seches-biologiques'],
            [
                'name' => 'KINU SEC - Fruits Séchés Bio',
                'description' => 'Fruits séchés biologiques, KINU SEC, saveurs intenses.'
            ]
        );

        $driedProducts = [
            [
                'name' => 'KINU SEC Ananas',
                'volume' => '200g',
                'price' => 1000,
                'description' => 'Tranches d\'ananas séchées bio. Snack sain et énergisant.',
            ],
            [
                'name' => 'KINU SEC Ananas',
                'volume' => '0,5 Kg',
                'price' => 3000,
                'description' => 'Tranches d\'ananas séchées bio. Format économique 500g.',
            ],
            [
                'name' => 'KINU SEC Papaye',
                'volume' => '200g',
                'price' => 1000,
                'description' => 'Papaye séchée bio. Douceur tropicale.',
            ],
             [
                'name' => 'KINU SEC Papaye',
                'volume' => '0,5 Kg',
                'price' => 3000,
                'description' => 'Papaye séchée bio. Format économique 500g.',
            ],
            [
                'name' => 'KINU SEC Cocktail',
                'volume' => '200g',
                'price' => 1000,
                'description' => 'Mélange de fruits séchés tropicaux.',
            ],
        ];

        foreach ($driedProducts as $p) {
            $fullName = $p['name'] . ' - ' . $p['volume'];
            Product::updateOrCreate(
                ['slug' => Str::slug($fullName)],
                [
                    'name' => $fullName,
                    'category_id' => $catDried->id,
                    'price' => $p['price'],
                    'stock' => 50,
                    'description' => $p['description'],
                    // 'image_path' => null
                ]
            );
        }
    }
}
