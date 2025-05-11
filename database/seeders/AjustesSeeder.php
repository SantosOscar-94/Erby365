<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AjustesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Ajustes =
        [
            [
                'correo'         => 'Demo@gmail.com',
                'responsable'         => 'Abogado'
            ]
        ];

        foreach($Ajustes as $ajustes)
        {
            $new_ajustes     = new \App\Models\Ajustes();
            foreach($ajustes as $k => $value)
            {
                $new_ajustes->{$k} = $value;
            }

            $new_ajustes->save();
        }

      
    }
}
