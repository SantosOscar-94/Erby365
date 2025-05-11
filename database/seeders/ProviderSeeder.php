<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients =
        [
            [
                'iddoc'         => 4,
                'dni_ruc'       => '20610316990',
                'nombres'       => 'MyPeru-Fac',
                'direccion'     => 'JR JICAMARCA',
                'codigo_pais'   => 'PE',
                'ubigeo'        => '150202',
                'telefono'      => ''
            ],

            [
                'iddoc'         => 2,
                'dni_ruc'       => '75333045',
                'nombres'       => 'Oscar Santos',
                'direccion'     => 'JR PALOMAR',
                'codigo_pais'   => 'PE',
                'ubigeo'        => '150202',
                'telefono'      => ''
            ]
        ];

        foreach($clients as $client)
        {
            $new_client     = new \App\Models\Provider();
            foreach($client as $k => $value)
            {
                $new_client->{$k} = $value;
            }

            $new_client->save();
        }
    }
}
