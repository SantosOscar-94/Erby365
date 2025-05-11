<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nombres'   => 'Administrador',
            'user'      => 'superadmin',
            'password'  => bcrypt('superadmin@'),
            'estado'    => 1,
            'idcaja'    => 1,
            'idalmacen' => 1
        ])->assignRole('SUPERADMIN');

        User::create([
            'nombres'   => 'Oscar Santos',
            'user'      => 'admin',
            'password'  => bcrypt('admin123.'),
            'estado'    => 1,
            'idcaja'    => 1,
            'idalmacen' => 2
        ])->assignRole('ADMIN');

        User::create([
            'nombres'   => 'Vendedor',
            'user'      => 'user2',
            'password'  => bcrypt('user123.'),
            'estado'    => 1,
            'idcaja'    => 1,
            'idalmacen' => 3
        ])->assignRole('VENDEDOR');
    }
}
