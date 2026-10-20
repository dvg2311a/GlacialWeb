<?php

namespace Database\Seeders;

use App\Models\FlavorProduct;
use App\Models\Freezer;
use App\Models\Product;
use App\Models\TypeProduct;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $freezer = Freezer::where('number_freezer', '01')->firstOrFail();

        $products = [
            [
                'name' => 'Chocolate',
                'description' => 'Paleta cremosa con sabor a chocolate.',
                'shape' => 'Paleta',
                'type_product' => 'De Leche',
                'flavor' => 'Chocolate',
            ],
            [
                'name' => 'Fresa',
                'description' => 'Paleta cremosa con sabor a fresa.',
                'shape' => 'Paleta',
                'type_product' => 'De Leche',
                'flavor' => 'Fresa',
            ],
            [
                'name' => 'Fruta Rellena',
                'description' => 'Paleta de fruta con relleno cremoso.',
                'shape' => 'Paleta',
                'type_product' => 'Rellena',
                'flavor' => 'Mango',
            ],
            [
                'name' => 'Cono Vainilla',
                'description' => 'Helado de vainilla servido en cono.',
                'shape' => 'Cono',
                'type_product' => 'Conos',
                'flavor' => 'Vainilla',
            ],
            [
                'name' => 'Panita Napolitana',
                'description' => 'Presentación en panita con sabores de vainilla, fresa y chocolate.',
                'shape' => 'Panita',
                'type_product' => 'Panitas',
                'flavor' => 'Napolitano',
            ],
        ];

        foreach ($products as $product) {
            $typeProduct = TypeProduct::where('name', $product['type_product'])->firstOrFail();
            $flavor = FlavorProduct::where('name', $product['flavor'])->firstOrFail();

            Product::firstOrCreate(
                ['name' => $product['name']],
                [
                    'description' => $product['description'],
                    'shape' => $product['shape'],
                    'wholesale_price' => 20,
                    'retail_price' => 30,
                    'purchase_price' => 15,
                    'bad_price' => 10,
                    'freezer_id' => $freezer->id,
                    'type_product_id' => $typeProduct->id,
                    'flavor_id' => $flavor->id,
                ]
            );
        }
    }
}
