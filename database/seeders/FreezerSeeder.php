<?php

namespace Database\Seeders;

use App\Models\Freezer;
use App\Models\TypeFreezer;
use Illuminate\Database\Seeder;

class FreezerSeeder extends Seeder
{
    public function run(): void
    {
        $horizontal = TypeFreezer::where('name', 'Congelador horizontal')->firstOrFail();
        $exhibidor = TypeFreezer::where('name', 'Congelador exhibidor')->firstOrFail();

        $freezers = [
            [
                'number_freezer' => '01',
                'status' => 'Activo',
                'type_freezer_id' => $horizontal->id,
            ],
            [
                'number_freezer' => '02',
                'status' => 'Activo',
                'type_freezer_id' => $exhibidor->id,
            ],
            [
                'number_freezer' => '03',
                'status' => 'Activo',
                'type_freezer_id' => $horizontal->id,
            ],
        ];

        foreach ($freezers as $freezer) {
            Freezer::firstOrCreate(
                ['number_freezer' => $freezer['number_freezer']],
                [
                    'status' => $freezer['status'],
                    'type_freezer_id' => $freezer['type_freezer_id'],
                ]
            );
        }
    }
}
