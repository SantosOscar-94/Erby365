<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kardex</title>
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
            text-align: left;
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
                    <th colspan="4" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Información general</th>
                    <th colspan="1" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Ingresos</th>
                    <th colspan="1" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Salidas</th>
                    <th colspan="1" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Saldo</th>
                </tr>
                <tr>
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Fecha</th>
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Tipo documento</th> 
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Documento</th>
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Producto</th>
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Cantidad</th>
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo unitario</th> -->
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo total</th> -->
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Cantidad</th>
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo unitario</th> -->
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo total</th> -->
                    <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Cantidad</th>
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo unitario</th> -->
                    <!-- <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Costo total</th> -->
                </tr>
            </thead>

            <tbody>
            @foreach ($kardex as $item)
                    <tr style="font-size: 12px;">
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->documentTypeId}}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->document}}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->product}}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->cant1 ?? 0}}</td>
                        <!-- <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->price1 ?? 0 }}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->total1 ?? 0 }}</td> -->
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->cant2 ?? 0}}</td>
                        <!-- <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->price2 ?? 0 }}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->total2 ?? 0 }}</td> -->
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->cant3 ?? 0}}</td>
                        <!-- <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->price3 ?? 0 }}</td>
                        <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{$item->total3 ?? 0 }}</td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>