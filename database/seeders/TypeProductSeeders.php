<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\TypeProduct;

class TypeProductSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Súper',
                'description' => 'Línea premium de mayor tamaño y precio (Súper Cono, Súper Sandwich, Súper Eskimo).',
            ],
            [
                'name' => 'De Leche',
                'description' => 'Paletas tradicionales cremosas a base de leche (Cocoa, Avena, Coco, Fresa de leche, Dulce de leche, Napolitano).',
            ],
            [
                'name' => 'De Agua',
                'description' => 'Paletas a base de agua y frutas (Mango, línea económica 2x1 de limón o tamarindo).',
            ],
            [
                'name' => 'De Cobertura',
                'description' => 'Paletas con capa crujiente exterior de chocolate o cobertura especial (Chocolate tradicional).',
            ],
            [
                'name' => 'Rellena',
                'description' => 'Paletas con centro líquido o cremoso de frutas, leche condensada o caramelo.',
            ],
            [
                'name' => 'Especiales / Infantiles',
                'description' => 'Presentaciones diseñadas para público infantil o combinaciones multicolores (Rocket tricolor, Rolinpín).',
            ],
            [
                'name' => 'Conos',
                'description' => 'Helados servidos en barquillo tradicional (Cono bola).',
            ],
            [
                'name' => 'Panitas',
                'description' => 'Presentaciones envasadas en vaso plástico o copa térmica (Panita 3 oz, Panita 8 oz).',
            ],
            [
                'name' => 'Temporada',
                'description' => 'Línea de edición limitada para festividades específicas (Navideña).',
            ],
            [
                'name' => 'Liquidación / Malos',
                'description' => 'Producto con merma o daño físico comercializado a precio de remate (Paletas malas, Sandwich/Conos malos).',
            ],
        ];

        foreach ($types as $type) {
            TypeProduct::firstOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description']]
            );
        }
    }
}
