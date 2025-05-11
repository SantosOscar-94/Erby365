<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
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

        .tachado {
        text-decoration:line-through;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>Kardex</h4>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th colspan="4" class="th_items border-solid">Información general</th>
                    <th colspan="1" class="th_items border-solid">Ingresos</th>
                    <th colspan="1" class="th_items border-solid">Salidas</th>
                    <th colspan="1" class="th_items border-solid">Saldo</th>
                </tr>
                <tr>
                    <th class="th_items border-solid">Fecha</th>
                    <th class="th_items border-solid">Tipo documento</th> 
                    <th class="th_items border-solid">Documento</th>
                    <th class="th_items border-solid">Producto</th>
                    <th class="th_items border-solid">Cantidad</th>
                    <!-- <th class="th_items border-solid">Costo unitario</th>
                    <th class="th_items border-solid">Costo total</th> -->
                    <th class="th_items border-solid">Cantidad</th>
                    <!-- <th class="th_items border-solid">Costo unitario</th>
                    <th class="th_items border-solid">Costo total</th> -->
                    <th class="th_items border-solid">Cantidad</th>
                    <!-- <th class="th_items border-solid">Costo unitario</th>
                    <th class="th_items border-solid">Costo total</th> -->
                </tr>
            </thead>

            <tbody>
                @foreach ($kardex as $item)
                    <tr style="font-size: 12px;">
                        <td class="td_items border-solid">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                        <td class="td_items border-solid">{{$item->documentTypeId}}</td>
                        <td class="td_items border-solid">{{$item->document}}</td>
                        <td class="td_items border-solid">{{$item->product}}</td>
                        <td class="td_items border-solid">{{$item->cant1 ?? 0}}</td>
                        <!-- <td class="td_items border-solid">{{$item->price1 ?? 0 }}</td>
                        <td class="td_items border-solid">{{$item->total1 ?? 0 }}</td> -->
                        <td class="td_items border-solid">{{$item->cant2 ?? 0}}</td>
                        <!-- <td class="td_items border-solid">{{$item->price2 ?? 0 }}</td>
                        <td class="td_items border-solid">{{$item->total2 ?? 0 }}</td> -->
                        <td class="td_items border-solid">{{$item->cant3 ?? 0}}</td>
                        <!-- <td class="td_items border-solid">{{$item->price3 ?? 0 }}</td> -->
                        <!-- <td class="td_items border-solid">{{$item->total3 ?? 0 }}</td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
