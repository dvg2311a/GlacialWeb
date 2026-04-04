<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;

use function Symfony\Component\Clock\now;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAdministrator = User::create([
            'name' => 'Maykeling Massiel',
            'surname' => 'Gonzalez',
            'direction' => 'Parque central',
            'cellphone' => '8918 7562',
            'sex' => 'F',
            'email' => 'may@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('may@gmail.com')
        ]);

        $userAdministrator -> assignRole('Administrador');

        $userGerente = User::create([
            'name' => 'David',
            'surname' => 'Gonzalez',
            'direction' => 'Parque infantil',
            'cellphone' => '8851 2161',
            'sex' => 'M',
            'email' => 'dav@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('dav@gmail.com')
        ]);

        $userGerente -> assignRole('Gerente');
    }
}
