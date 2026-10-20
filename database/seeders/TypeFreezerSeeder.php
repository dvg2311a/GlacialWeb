<?php

namespace Database\Seeders;

use App\Models\TypeFreezer;
use Illuminate\Database\Seeder;

class TypeFreezerSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Congelador horizontal',
                'description' => 'Freezer horizontal para almacenamiento general de productos congelados.',
            ],
            [
                'name' => 'Congelador exhibidor',
                'description' => 'Freezer exhibidor para conservar y mostrar productos para la venta.',
            ],
        ];

        foreach ($types as $type) {
            TypeFreezer::firstOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description']]
            );
        }
    }
}
