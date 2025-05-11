<?php

namespace Database\Seeders;

use App\Models\ListadoDetra;
use Illuminate\Database\Seeder;

class ListadoDetraccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ListadoDetra::insert([
            [
                'codigo' => '001',
                'descripcion' => 'Azúcar y melaza de caña',
                'porcentaje' => 10.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '003',
                'descripcion' => 'Alcohol etílico',
                'porcentaje' => 10.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '005',
                'descripcion' => 'Maíz amarillo duro',
                'porcentaje' => 4.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '008',
                'descripcion' => 'Madera',
                'porcentaje' => 4.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '016',
                'descripcion' => 'Aceite de pescado',
                'porcentaje' => 10.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '019',
                'descripcion' => 'Arrendamiento de bienes',
                'porcentaje' => 10.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '020',
                'descripcion' => 'Mantenimiento y reparación de bienes muebles',
                'porcentaje' => 12.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '022',
                'descripcion' => 'Otros servicios empresariales',
                'porcentaje' => 12.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '023',
                'descripcion' => 'Leche',
                'porcentaje' => 4.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '025',
                'descripcion' => 'Fabricación de bienes por encargo',
                'porcentaje' => 10.00,
                'tipo_operacion' => '1001',
            ],
            [
                'codigo' => '027',
                'descripcion' => 'Servicio de transporte de carga',
                'porcentaje' => 4.00,
                'tipo_operacion' => '1004',
            ],
            [
                'codigo' => '12',
                'descripcion' => 'test',
                'porcentaje' => 40.00,
                'tipo_operacion' => '1004',
            ],
            [
                'codigo' => '121',
                'descripcion' => 'test',
                'porcentaje' => 40.00,
                'tipo_operacion' => '1004',
            ],
        ]);
    }
}
