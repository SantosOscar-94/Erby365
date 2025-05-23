<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $series     =
        [
            [
                'serie'                         => 'F001',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 1,
                'idcaja'                        => 1
            ],

            [
                'serie'                         => 'B001',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 2,
                'idcaja'                        => 1
            ],

            [
                'serie'                         => 'BC01',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 2,
                'idcaja'                        => 1
            ],


            [
                'serie'                         => 'FC01',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 1,
                'idcaja'                        => 1
            ],
            [
                'serie'                         => 'NV01',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 7,
                'idcaja'                        => 1
            ],





            [
                'serie'                         => 'F002',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 1,
                'idcaja'                        => 2
            ],

            [
                'serie'                         => 'B002',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 2,
                'idcaja'                        => 2
            ],

            [
                'serie'                         => 'BC02',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 2,
                'idcaja'                        => 2
            ],

            [
                'serie'                         => 'FC02',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 1,
                'idcaja'                        => 2
            ],

            [
                'serie'                         => 'NV02',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 7,
                'idcaja'                        => 2
            ],



            
            [
                'serie'                         => 'F003',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 1,
                'idcaja'                        => 3
            ],

            [
                'serie'                         => 'B003',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 2,
                'idcaja'                        => 3
            ],

            [
                'serie'                         => 'BC03',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 2,
                'idcaja'                        => 3
            ],

            [
                'serie'                         => 'FC03',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 1,
                'idcaja'                        => 3
            ],

            [
                'serie'                         => 'NV03',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 7,
                'idcaja'                        => 3
            ],


            
            [
                'serie'                         => 'F004',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 1,
                'idcaja'                        => 4
            ],

            [
                'serie'                         => 'B004',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 2,
                'idcaja'                        => 4
            ],

            [
                'serie'                         => 'BC04',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 2,
                'idcaja'                        => 4
            ],

            [
                'serie'                         => 'FC04',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 1,
                'idcaja'                        => 4
            ],

            [
                'serie'                         => 'NV04',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 7,
                'idcaja'                        => 4
            ],


            
            [
                'serie'                         => 'F005',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 1,
                'idcaja'                        => 5
            ],

            [
                'serie'                         => 'B005',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 2,
                'idcaja'                        => 5
            ],

            [
                'serie'                         => 'BC05',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 2,
                'idcaja'                        => 5
            ],

            [
                'serie'                         => 'FC05',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 6,
                'idtipo_documento_relacionado'  => 1,
                'idcaja'                        => 5
            ],

            [
                'serie'                         => 'NV05',
                'correlativo'                   => '00000001',
                'idtipo_documento'              => 7,
                'idcaja'                        => 5
            ]
        ];




        foreach($series as $serie)
        {
            $new_serie  = new \App\Models\Serie();
            foreach($serie as $k => $value)
            {
                $new_serie->{$k}    = $value;
            }

            $new_serie->save();
        }
    }
}
