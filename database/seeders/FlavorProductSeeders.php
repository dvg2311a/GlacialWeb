<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use app\Models\FlavorProduct;

class FlavorProductSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flavors = [
            [
                'name' => 'Chocolate',
                'description' => 'Sabor tradicional a chocolate utilizado en paletas, conos y sándwiches.',
            ],
            [
                'name' => 'Cocoa',
                'description' => 'Sabor clásico a leche con cocoa suave.',
            ],
            [
                'name' => 'Vainilla',
                'description' => 'Sabor base tradicional presente en sándwiches, conos bola, súper eskimo y panitas.',
            ],
            [
                'name' => 'Fresa',
                'description' => 'Sabor frutal tradicional presente en paletas de agua, leche, panitas y rellenas.',
            ],
            [
                'name' => 'Mora',
                'description' => 'Sabor a mora silvestre, utilizado en paletas y rellenos frutales.',
            ],
            [
                'name' => 'Tropical',
                'description' => 'Combinación refrescante de frutas tropicales en paleta.',
            ],
            [
                'name' => 'Galleta',
                'description' => 'Crema suave combinada con trozos de galleta crujiente.',
            ],
            [
                'name' => 'Avena',
                'description' => 'Paleta de leche tradicional elaborada a base de avena y canela.',
            ],
            [
                'name' => 'Coco',
                'description' => 'Helado cremoso tradicional con esencia y ralladura de coco.',
            ],
            [
                'name' => 'Dulce de Leche',
                'description' => 'Cremoso helado a base de dulce de leche y caramelo.',
            ],
            [
                'name' => 'Frutal Tricolor',
                'description' => 'Combinación cítrica y dulce multicapa (fresa, limón y mora azul) característica de la paleta Rocket.',
            ],
            [
                'name' => 'Mango',
                'description' => 'Paleta refrescante a base de pulpa de mango maduro.',
            ],
            [
                'name' => 'Napolitano',
                'description' => 'Trisabor clásico compuesto por capas de vainilla, fresa y chocolate en paleta y panita.',
            ],
            [
                'name' => 'Ron con Pasas',
                'description' => 'Receta tradicional de crema con pasas maceradas, clásica de las presentaciones en panita.',
            ],
            [
                'name' => 'Limón',
                'description' => 'Sabor cítrico y refrescante utilizado en la línea económica 2x1 y paletas de agua.',
            ],
            [
                'name' => 'Tamarindo',
                'description' => 'Sabor agridulce natural característico de la línea de agua.',
            ],
            [
                'name' => 'Naranja',
                'description' => 'Sabor cítrico infantil clásico de la línea Rolinpín.',
            ],
            [
                'name' => 'Chicle',
                'description' => 'Sabor dulce y distintivo de la línea infantil.',
            ],
            [
                'name' => 'Ponche Navideño',
                'description' => 'Sabor de temporada con notas especiadas de leche y vainilla.',
            ],
        ];

        foreach ($flavors as $flavor) {
            FlavorProduct::firstOrCreate(
                ['name' => $flavor['name']],
                ['description' => $flavor['description']]
            );
        }
    }
}
