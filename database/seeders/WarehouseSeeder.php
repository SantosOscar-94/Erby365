<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses =
        [
            [
                'descripcion'           => 'Tienda Principal',
                'direccion'             =>  'Av. Sentimientos',
                'telefono'             =>  '9999999999'
            ],
            [
                'descripcion'           => 'Tienda 02',
                'direccion'             =>  'Av. Palomar',
                'telefono'             =>  '9999999999'
            ],
            [
                'descripcion'           => 'Tienda 03',
                'direccion'             =>  'Av. Rinconada',
                'telefono'             =>  '9999999999'
            ],




        ];

        foreach($warehouses as $store)
        {
            $new_store     = new \App\Models\Warehouse();
            foreach($store as $k => $value)
            {
                $new_store->{$k} = $value;
            }
            $new_store->save();
        }
    }
}
