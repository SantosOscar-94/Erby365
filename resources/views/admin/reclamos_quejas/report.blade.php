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
        <h4>REPORTE DE ARQUEO DE CAJA</h4>
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
                <th class="th_informacion">FECHA APERTURA</th>
                <td class="td_informacion">{{ date('d-m-Y H:i:s', strtotime($cash->created_at)) }}</td>
            </tr>
            
            <tr>
                <th class="th_informacion">FECHA CIERRE</th>
                <td class="td_informacion">{{ $cash->created_at == $cash->updated_at ? "No disponible" : date('d-m-Y H:i:s', strtotime($cash->updated_at)) }}</td>
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
                    <!-- <th colspan="3" class="th_items border-solid"></th> -->
                    <th colspan="5" class="th_items border-solid"></th>
                    <th colspan="4" class="th_items border-solid">Referencia</th>
                </tr>
                <tr style="font-size: 12px;">
                    <th class="th_items border-solid">FECHA</th>
                    <th class="th_items border-solid">TRANSACCIÓN</th>
                    <th class="th_items border-solid">BANCO</th>
                    <th class="th_items border-solid">N° DE OPERACIÓN</th>
                    <th class="th_items border-solid">MONTO</th>
                    <th class="th_items border-solid">DOC</th>
                    <th class="th_items border-solid">SERIE</th>
                    <th class="th_items border-solid">NÚMERO</th>
                    <th class="th_items border-solid">MONTO</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($billings as $item)
                    <tr style="font-size: 12px;">
                        <td class="td_items border-solid">{{ date('d-m-Y', strtotime($item["fecha_emision"])) }}</td>
                        <!-- <td class="td_items border-solid">{{ $item["serie"] ."-". $item["correlativo"] }}</td> -->
                        <td class="td_items border-solid">
                            @foreach ($pagos as $pago)
                                @foreach ($pago as $item_pago)
                                    @if ($item_pago->idfactura == $item["id"] && $item["idtipo_comprobante"] == $item_pago->idtipo_comprobante)
                                    <p class="pay_mode">{{ $item_pago->tipo_pago }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        </td>
                        <td class="td_items border-solid">
                            @foreach ($pagos as $pago)
                                @foreach ($pago as $item_pago)
                                    @if ($item_pago->idfactura == $item["id"] && $item["idtipo_comprobante"] == $item_pago->idtipo_comprobante)
                                        @if ($item_pago->tipo_pago == "Yape")
                                            <p class="pay_mode">BCP</p>
                                        @elseif ($item_pago->tipo_pago == "Tarjeta de crédito")
                                            <p class="pay_mode">Interbank</p>
                                        @elseif ($item_pago->tipo_pago == "Plin")
                                            <p class="pay_mode">BBVA</p>
                                        @else
                                            <p class="pay_mode">{{$item_pago->cuenta}}</p>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                            
                        </td>
                        <td class="text-left border-solid">
                            @foreach ($pagos as $pago)
                                @foreach ($pago as $item_pago)
                                    @if ($item_pago->idfactura == $item["id"] && $item["idtipo_comprobante"] == $item_pago->idtipo_comprobante)
                                        <p class="pay_mode">{{ $item_pago->referencia }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        </td>
                        <td class="td_items border-solid">
                            @foreach ($pagos as $pago)
                                @foreach ($pago as $item_pago)
                                    @if ($item_pago->idfactura == $item["id"] && $item["idtipo_comprobante"] == $item_pago->idtipo_comprobante)
                                        <p class="pay_mode">{{ $item_pago->monto }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        </td>
                        <td class="td_items border-solid">
                            @if (substr($item["serie"], 0, 1) == "F")
                                FT
                            @elseif (substr($item["serie"], 0, 1) == "N")
                                NV
                            @elseif (substr($item["serie"], 0, 1) == "B")
                                BV
                            @else
                                No aplica
                            @endif
                        </td>
                        <td class="td_items border-solid">{{ $item["serie"] }}</td>
                        <td class="td_items border-solid">{{ $item["correlativo"] }}</td>
                        <td class="td_items border-solid">{{ $item["total"] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="text-right border-solid text-danger">Gastos S/ &nbsp;</td>
                    <td class="td_items border-solid text-danger">-{{ number_format($sum_bills, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right border-solid">Total Ventas S/ &nbsp;</td>
                    <td class="td_items border-solid">{{ number_format($monto_ventas, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="8" class="text-right border-solid">Total S/ &nbsp;</td>
                    <td class="td_items border-solid">{{ number_format($total, 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
