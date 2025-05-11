<html>

<head>
    <style>
        * {
            box-sizing: border-box;
            font-size: 11px;
            font-family: sans-serif;
        }

        .header {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .header .logo {
            /* display: inline-block; */
            height: 100px;
            width: 18%;
        }

        .header .logo img {
            width: 100%;
            height: auto
        }

        .header .data {
            /* display: inline-block; */
            width: 69%;
            text-align: center
        }

        .data-name {
            font-weight: bold;
        }

        .data-ruc {
            /* display: inline-block; */
            width: 31%;
            border: 1px solid black;
            border-radius: 5px;
        }

        .data-ruc>div {
            padding: 5px;
            text-align: center;
        }

        .data-ruc>div.name {
            padding: 2px;
            background: #EBEBEB;
            font-weight: bold;
        }

        .user {
            display: inline-block;
            border: 1px solid black;
            border-radius: 5px;
            padding: 5px;
            margin-bottom: 15px;

        }

        .user>* {
            display: inline-block;
            vertical-align: top;
        }

        .user .w-15 {
            width: 15%;
        }

        .user .w-50 {
            width: 49%;
        }

        .user .w-20 {
            width: 19%;
        }

        .dates {
            display: inline-block;
            border: 1px solid black;
            border-radius: 5px;
            padding: 5px;
            margin-bottom: 15px;
            text-align: center;
            width: 98.5%;
        }

        .dates .w-25 {
            display: inline-block;
            width: 23%;
        }

        .dates .w-25>* {
            display: inline-block;
            width: 100%;
        }

        table.description {
            width: 100%;
            border: 1px solid black;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        table.description .row-1 {
            width: 10%
        }

        table.description .row-2 {
            width: 40%
        }

        table.description tr {
            height: 18px
        }

        .price-text {
            padding: 3px;
            border: 1px solid black;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .all {
            width: 100%;
            margin-bottom: 10px;
        }

        .all .observation {
            /* display: inline-block; */
            width: 60%;
            height: 50px;
            /* background: blue; */
            vertical-align: top;
        }

        .all .all-pay {
            /* display: inline-block; */
            width: 40%;
            border: 1px solid black;
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 5px;
        }

        .fechas {
            display: inline-block;
            border: 1px solid black;
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 15px;

        }

        .all .all-pay .left {
            display: inline-block;
            width: 63%;
            text-align: right
        }

        .all .all-pay .right {
            display: inline-block;
            width: 35%;
            text-align: right
        }

        .all .all-pay .bold {
            font-weight: bold;
        }

        .info-aside .qr {
            margin-top: 40px;
            margin-right: 20px;
            height: 150px;
            width: 150px;
            display: inline-block;
        }

        .info-aside .qr img {
            width: 100%;
        }

        .info-aside .info {
            display: inline-block;
            width: 75%;
            text-align: center;
            vertical-align: top;
        }

        .info-aside .info .method {
            border-radius: 5px;
            border: 1px solid black;
            padding: 5px;
            margin-bottom: 15px;
        }

        .info-aside .info .method .w-30 {
            display: inline-block;
            width: 32%;
        }

        .info-aside .info .method .w-30 b {
            display: block;
        }

        .info-aside .info .secondary {
            border-radius: 5px;
            border: 1px solid black;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bottom-fixed {
            margin-bottom: 0px;
        }

        .thead_table {
            background: #ebebeb;
        }

        .thead_table th {
            padding: 10px;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td class="logo">
                <img src="{{ public_path('assets/img/branding/logo-horizontal.png') }}" widht="100%" height="100%">
            </td>
            <td class="data">
                <div class="data-name">
                    {{ $business->nombre_comercial }}
                </div>
                <div class="data-direction">
                    {{ $business->direccion }}<br>
                    {{ $ubigeo["distrito"] }} - {{ $ubigeo["provincia"] }} - {{ $ubigeo["departamento"] }}<br>
                    Tel&eacute;fono: {{ ($business->telefono == null) ? '-' : $business->telefono }}<br>
                </div>
            </td>
            <td class="data-ruc">
                <div class="ruc">R.U.C. {{ $business->ruc }}</div>
                <div class="name"></div>
                <div class="name">COTIZACIÓN</div>
                <div class="name"></div>
                <div class="number">{{ $quote->serie . '-' . $quote->correlativo }}</div>
            </td>
        </tr>
    </table>
    <div class="user">
        <b class="w-15">NOMBRE</b>
        <span class="w-50">: {{ $client->nombres }}</span>
        <b class="w-15">MONEDA</b>
        <span class="w-20">: SOLES</span>

        <b class="w-15">RUC</b>
        <span class="w-50">: {{ $client->dni_ruc }}</span>
        <b class="w-15">VENDEDOR</b>
        <span class="w-20">: ADMINISTRADOR</span>
        <b class="w-15">DIRECCÓN</b>
        @if ($client->direccion == "-")
        <span class="w-50">: {{ $client->direccion }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        @else
        <span class="w-50">: {{ $client->direccion }}</span>
        @endif

    </div>

    <table class="description">
        <thead class="thead_table">
            <th class="row-1">#</th>
            <th class="row-1">CODIGO</th>
            <th class="row-2">DESCRIPCIÓN</th>
            <th class="row-1">CANT.</th>
            <th class="row-1">UND.</th>
            <th class="row-1">P.UNIT.</th>
            <th class="row-1">TOTAL</th>
        </thead>
        <tbody>
            @foreach ($detail as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ ($item["codigo_interno"] == null || $item == "") ? "" : $item["codigo_interno"] }}</td>
                <td>{{ $item["producto"] }}</td>
                <td class="center">{{ intval($item["cantidad"]) }}</td>
                <td class="center">{{ $item["unidad"] }}</td>
                <td class="center">{{ $item["precio_unitario"] }}</td>
                <td class="center">{{ $item["precio_total"] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="price-text">
        <span>SON: </span>
        <span>{{ $numero_letras }} CON 00/100 SOLES</span>
    </div>
    <table class="all">
        <tr>
            <td class="observation">
                <b>OBSERVACIONES:</b>
                <span>{{ $quote->observaciones }}</span>
            </td>
            <td class="all-pay">
                <div class="item">
                    <div class="left">OP. GRAVADAS: S/.</div>
                    <div class="right">{{ number_format(($quote->exonerada + $quote->gravada + $quote->inafecta), 2, '.', '') }}</div>
                </div>
                <div class="item">
                    <div class="left">IGV: S/.</div>
                    <div class="right">{{ $quote->igv }}</div>
                </div>
                <div class="item bold">
                    <div class="left">TOTAL A PAGAR: S/.</div>
                    <div class="right">{{ $quote->total }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="fechas">

        <div class="item">
            <div class="left">FECHA EMISION:</div>
            <div class="right">{{ $quote->fecha_emision }}</div>
        </div>
        <div class="item bold">
            <div class="left">FECHA VENCIMIENTO:</div>
            <div class="right">{{ $quote->fecha_vencimiento  }}</div>
        </div>
    </table>

    <table class="description">
        <thead class="thead_table">
            <tr>
                <th colspan="5" class="center">CUENTAS</th>
            </tr>
            <tr>
                <th class="row-1">#</th>
                <th class="row-1">BANCO</th>
                <th class="row-1">MONEDA</th>
                <th class="row-2"># CUENTA</th>
                <th class="row-2">CCI</th>
            </tr>


        </thead>
        <tbody>
            @foreach ($cuentas as $cuenta)
                <tr>
                    <td class="center">{{ $cuenta->id }}</td>
                    <td class="center">{{ $cuenta->nombre_ban }}</td>
                    <td class="center">{{ $cuenta->moneda }}</td>
                    <td class="center">{{ $cuenta->num_cuenta }}</td>
                    <td class="center">{{ $cuenta->cci }}</td>
                </tr>
            @endforeach
{{--            <tr>--}}
{{--                <td class="center">1</td>--}}
{{--                <td class="center">{{ $cuentas->nombre_ban }}</td>--}}
{{--                <td class="center">{{ $cuentas->moneda }}</td>--}}
{{--                <td class="center">{{ $cuentas->num_cuenta }}</td>--}}
{{--                <td class="center">{{ $cuentas->cci }}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td class="center">2</td>--}}
{{--                <td class="center">{{ $cuentas->nombre_ban1 }}</td>--}}
{{--                <td class="center">{{ $cuentas->moneda1 }}</td>--}}
{{--                <td class="center">{{ $cuentas->num_cuenta1 }}</td>--}}
{{--                <td class="center">{{ $cuentas->cci1 }}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td class="center">3</td>--}}
{{--                <td class="center">{{ $cuentas->nombre_ban2 }}</td>--}}
{{--                <td class="center">{{ $cuentas->moneda2 }}</td>--}}
{{--                <td class="center">{{ $cuentas->num_cuenta2 }}</td>--}}
{{--                <td class="center">{{ $cuentas->cci2 }}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td class="center">4</td>--}}
{{--                <td class="center">{{ $cuentas->nombre_ban3 }}</td>--}}
{{--                <td class="center">{{ $cuentas->moneda3 }}</td>--}}
{{--                <td class="center">{{ $cuentas->num_cuenta3 }}</td>--}}
{{--                <td class="center">{{ $cuentas->cci3 }}</td>--}}
{{--            </tr>--}}
        </tbody>
    </table>

</body>

</html>
