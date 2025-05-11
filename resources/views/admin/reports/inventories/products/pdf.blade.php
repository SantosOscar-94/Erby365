<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Productos</title>
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
            width: 220px;
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
            text-align: left;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>REPORTE DE PRODUCTOS</h4>
    </div>

    <div id="informacion">
        <table style="font-size: 15px;">
            <tr>
                <th class="th_informacion">RUC</th>
                <td class="td_informacion">: {{ $business->ruc }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EMPRESA</th>
                <td class="td_informacion">: {{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">CANTIDAD DE PRODUCTOS</th>
                <td class="td_informacion">: {{ $quantity }}</td>
            </tr>
            <tr>
                <th class="th_informacion">FECHA EMISION</th>
                <td class="td_informacion">: {{ date('Y-m-d')}}</td>
            </tr>
            <tr>
                <th class="th_informacion">VENDEDOR</th>
                <td class="td_informacion">: {{ $user_name }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th class="th_items border-solid" width="10%">Código</th>
                    <th class="td_informacion border-solid">Producto</th>
                    <th class="th_items border-solid" width="13%">Unidad</th>
                    <th class="th_items border-solid" width="10%">Marca</th>
                    <th class="th_items border-solid" width="10%">Presentaci&oacute;n</th>
                    <th class="th_items border-solid" width="10%">Precio compra</th>
                    <th class="th_items border-solid" width="10%">Precio venta</th>
                    <th class="th_items border-solid" width="10%">Precio venta por mayor</th>
                    <th class="th_items border-solid" width="10%">Precio venta distribuidor</th>
                    <th class="th_items border-solid" width="15%">Stock / Almac&eacute;n</th>
                    <th class="th_items border-solid" width="15%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $item)
                    <tr style="font-size: 12px;">
                        @if ($item->codigo_interno == null)
                            <td class="td_items border-solid">-</td>
                        @else
                            <td class="td_items border-solid">{{ $item->codigo_interno }}</td>
                        @endif
                        <td class="td_informacion border-solid">{{ $item->descripcion }}</td>
                        <td class="td_items border-solid">{{ $item->unidad }}</td>
                        <td class="td_items border-solid">{{ $item->marca }}</td>
                        <td class="td_items border-solid">{{ $item->presentacion }}</td>
                        <td class="td_items border-solid">{{ $item->precio_compra }}</td>
                        <td class="td_items border-solid">{{ $item->precio_venta }}</td>
                        <td class="td_items border-solid">{{ $item->precio_venta_por_mayor }}</td>
                        <td class="td_items border-solid">{{ $item->precio_venta_distribuidor }}</td>
                        <td class="td_items border-solid">
                            @if ($warehouse_selected)
                                @foreach ($stock_products as $stock)
                                    @if ($warehouse->id == $stock->idalmacen && $item->id == $stock->idproducto)
                                        <p class="pay_mode">
                                            {{ $warehouse['descripcion'] . ': ' . $stock->cantidad }}</p>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($warehouses as $warehouse)
                                    @foreach ($stock_products as $stock)
                                        @if ($warehouse['id'] == $stock['idalmacen'] && $item->id == $stock['idproducto'])
                                            <p class="pay_mode">
                                                {{ $warehouse['descripcion'] . ': ' . $stock['cantidad'] }}</p>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        </td>
                        <td class="td_items border-solid"></td>
                    </tr>
                @empty
                    <tr style="font-size: 12px;">
                        <td class="td_informacion border-solid" colspan="5"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
