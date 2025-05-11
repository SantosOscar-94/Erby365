<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Arqueo de Caja</title>
    <style>
        #cabecera {
            text-align: center;
            text-decoration: underline;
        }

        body {
            font-family: sans-serif;
        }

        .th_informacion {
            text-align: left;
            width: 200px;
        }

        .td_informacion {
            text-align: left;
        }

        .th_items,
        .td_items {
            text-align: center;
        }

        #table_items {
            width: 100%;
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
        <table style="font-size: 15px;">
            <tr>
                <th class="th_informacion">RUC</th>
                <td class="td_informacion">{{ $business->ruc }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EMPRESA</th>
                <td class="td_informacion">{{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">FECHA</th>
                <td class="td_informacion">{{ date('d-m-Y H:i:s', strtotime($cash->created_at)) }}</td>
            </tr>

            <tr>
                <th class="th_informacion">MONTO INICIAL</th>
                <td class="td_informacion">S/{{ number_format($cash->monto_inicial, 2, '.', '') }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
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
                    <tr style="font-size: 12px;">
                        <td class="td_items border-solid">{{ $item->tipo_pago }}</td>
                        <td class="td_items border-solid">{{ $item->cant }}</td>
                        <td class="td_items border-solid">{{ $item->moneda }}</td>
                        <td class="td_items border-solid">{{ $item->monto }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
