<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Arqueo de Caja</title>
    <style>
        #cabecera,
        #cabecera2 {
            text-align: center;
            text-decoration: underline;
        }

        body {
            font-family: sans-serif;
            margin-left: 0 !important;
        }

        .th_informacion {
            text-align: left;
            width: 70%;
        }

        .td_informacion {
            text-align: left;
        }

        .th_items,
        .td_items {
            text-align: center;
            font-size: 9px;
        }

        #table_items {
            width: 90%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #thead_items {
            width: 100%;
        }

        .border-solid {
            border: 1px solid #dee2e6
        }

        .text-right {
            text-align: right;
        }

        .text-danger {
            color: rgb(234, 84, 85);
        }

        .pay_mode {
            margin-bottom: 0px; 
            margin-top: 0px; 
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>RESUMEN DE ARQUEO DE CAJA</h4>
    </div>

    <div id="informacion">
        <table style="font-size: 10px;">
            <tr>
                <th class="th_informacion">RUC</th>
                <td class="td_informacion">{{ $business->ruc }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EMPRESA</th>
                <td class="td_informacion">{{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">TIENDA</th>
                <td class="td_informacion">{{ $business->direccion }}</td>
            </tr>

            <tr>
                <th class="th_informacion">VENDEDOR</th>
                <td class="td_informacion">{{ \App\Models\User::find($cash->idusuario)->nombres  }}</td>
            </tr>

            <tr>
                <th class="th_informacion">ESTADO DE CAJA</th>
                <td class="td_informacion">{{ $cash->estado == 1 ? 'Abierta' : 'Cerrada' }}</td>
            </tr>

            <tr>
                <th class="th_informacion">APERTURA</th>
                <td class="td_informacion">{{ date('d-m-Y H:i:s', strtotime($cash->fecha_inicio)) }}</td>
            </tr>

            <tr>
                <th class="th_informacion">CIERRE</th>
                <td class="td_informacion">{{ $cash->estado == 2 ? date('d-m-Y H:i:s', strtotime($cash->fecha_fin)) : 'No disponible' }}</td>
            </tr>
            
        </table>

        <div id="cabecera2">
            <h6>FLUJO DE DINERO</h6>
        </div>

        <table style="font-size: 10px;">
            <tr>
                <th class="th_informacion">SALDO INICIAL</th>
                <td class="td_informacion">S/{{ number_format($cash->monto_inicial, 2, '.', '') }}</td>
            </tr>

            <tr>
                <th class="th_informacion">INGRESO DE DINERO</th>
                <td class="td_informacion">S/{{ number_format($ingresos, 2, '.', '') }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EGRESO DE DINERO</th>
                <td class="td_informacion">S/{{ number_format($egresos, 2, '.', '') }}</td>
            </tr>

            <tr>
                <th class="th_informacion">SALDO FINAL</th>
                <td class="td_informacion">S/{{ $cash->estado == 2 ? number_format($cash->monto_final, 2, '.', '') : 'No disponible' }}</td>
            </tr>
        </table>
    </div>

    <div id="cabecera2">
        <h6>DETALLE DE DOCUMENTOS</h6>
    </div>

    <div id="items">
        <table id="table_items" style="">
            <thead id="thead_items">
                <tr>
                    <th class="th_items border-solid">TIPO DOCUMENTO</th>
                    <th class="th_items border-solid">CANTIDAD</th>
                    <th class="th_items border-solid">MONEDA</th>
                    <th class="th_items border-solid">MONTO</th>
                </tr>
            </thead>

            <tbody>
                
                <tr>
                    <td class="td_items border-solid"> FACTURA</td>
                    <td class="td_items border-solid">{{ $factura->count() }}</td>
                    <td class="td_items border-solid">PEN</td>
                    <td class="td_items border-solid">{{ number_format($factura->sum('total'), 2, '.', '') }}</td>
                </tr>

                <tr>
                    <td class="td_items border-solid"> BOLETA</td>
                    <td class="td_items border-solid">{{ $boleta->count() }}</td>
                    <td class="td_items border-solid">PEN</td>
                    <td class="td_items border-solid">{{ number_format($boleta->sum('total'), 2, '.', '') }}</td>
                </tr>

                <tr>
                    <td class="td_items border-solid"> NOTA DE VENTA</td>
                    <td class="td_items border-solid">{{ $nv->count() }}</td>
                    <td class="td_items border-solid">PEN</td>
                    <td class="td_items border-solid">{{ number_format($nv->sum('total'), 2, '.', '') }}</td>
                </tr>

                <tr>
                    <td class="td_items border-solid"> NOTA DE CRÉDITO</td>
                    <td class="td_items border-solid">{{ $nc->count() }}</td>
                    <td class="td_items border-solid">PEN</td>
                    <td class="td_items border-solid">-{{ number_format($nc->sum('total'), 2, '.', '') }}</td>
                </tr>
               
                <tr>
                    <td class="td_items border-solid" colspan="3">TOTAL</td>
                    <td class="td_items border-solid">{{ number_format(($factura->sum('total') + $boleta->sum('total') + $nv->sum('total') - $nc->sum('total')), 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="cabecera2">
        <h6>RESUMEN POR MÉTODO DE PAGO</h6>
    </div>

    <div id="items">
        <table id="table_items" style="">
            <thead id="thead_items">
                <tr>
                    <th class="th_items border-solid">MÉTODO DE PAGO</th>
                    <th class="th_items border-solid">CANTIDAD DE OPERACIONES</th>
                    <th class="th_items border-solid">MONEDA</th>
                    <th class="th_items border-solid">MONTO</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pagos as $item)
                    <tr>
                        <td class="td_items border-solid">{{ $item->tipo_pago }}</td>
                        <td class="td_items border-solid">{{ $item->cant }}</td>
                        <td class="td_items border-solid">{{ $item->moneda }}</td>
                        <td class="td_items border-solid">{{ number_format($item->monto, 2, '.', '') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>