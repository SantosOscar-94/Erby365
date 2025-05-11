<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BusineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $businesses =
            [
                [
                    'ruc'               => '10753330459',
                    'razon_social'      => 'Oscar Esliban De Los Santos Shapiama',
                    'direccion'         => 'A.H. ARRIBA PERU -CAL. 37 ,Mz 9 Lote 9',
                    'codigo_pais'       => 'PE',
                    'ubigeo'            => '150132',
                    'telefono'          => '',
                    'url_api'           => 'https://apidemoprueba.erby365.com/',
                    'email_accounting'  => 'demoerby365@gmail.com',
                    'urbanizacion'      => '',
                    'local'             => '',
                    'nombre_comercial'  => 'Demo Erby365',
                    'usuario_sunat'     => 'HEYDI010',
                    'clave_sunat'       => 'Heydi0102',
                    'clave_certificado' => 'Alva123456',
                    'certificado'       => '10753330459.pfx',
                    'servidor_sunat'    => 3,
                    'pago'              => 1,
                    'cuenta_detraciones' => '0061-241-25'
                ]
            ];

        foreach ($businesses as $business) {
            $new_business = new \App\Models\Business();
            foreach ($business as $k => $value) {
                $new_business->{$k} = $value;
            }

            $new_business->save();
        }
    }
}
