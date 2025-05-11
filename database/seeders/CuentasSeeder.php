<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cuentas =
        [
            [
                'nombre_ban'    =>'BCP',
                'moneda'        =>'SOLES',
                'num_cuenta'    =>'191-147878787-52',
                'cci'           =>'192011-147878787-5278'
            ]
        ];

        foreach($cuentas as $cuenta)
        {
            $new_cuenta     = new \App\Models\Cuentas();
            foreach($cuenta as $k => $value)
            {
                $new_cuenta->{$k} = $value;
            }

            $new_cuenta->save();
        }
    }
}
