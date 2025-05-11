<html>
    <head>
        <style>
            *{
                box-sizing: border-box;
                font-size: 11px;
                font-family: sans-serif;
            }
            .header{
                margin-bottom: 15px;
                font-size: 14px;
            }
            .header .logo{
                /* display: inline-block; */
                height: 100px;
                width: 18%;
            }
            .header .logo img{
                width: 100%;
                height: auto
            }
            .header .data{
                /* display: inline-block; */
                width: 69%;
                text-align: center
            }
            .data-name{
                font-weight: bold;
            }
            .data-ruc{
                /* display: inline-block; */
                width: 31%;
                border: 1px solid black;
                border-radius: 5px;
            }
            .data-ruc>div{
                padding: 5px;
                text-align: center;
            } 
            .data-ruc>div.name{
                background: #aaa
            }
            .user{
                display: inline-block;
                border: 1px solid black;
                border-radius: 10px;
                padding: 5px;
                margin-bottom: 15px;
                
            }
            .user>*{
                display: inline-block;
                vertical-align: top;
            }
            .user .w-15{
                width: 20%;
            }
            .user .w-50{
                width: 39%;
            }
            .user .w-20{
                width: 19%;
            }
            .dates{
                display: inline-block;
                border: 1px solid black;
                border-radius: 10px;
                padding: 5px;
                margin-bottom: 15px;
                text-align: center;
                width: 98.5%;
            }
            .dates .w-25{
                display: inline-block;
                width: 23%;
            }
            .dates .w-25>*{
                display: inline-block;
                width: 100%;
            }

            table.description{
                width: 100%;
                border: 1px solid black;
                border-radius: 10px;
                margin-bottom: 15px;
            }
            table.description .row-1{
                width: 10%
            }
            table.description .row-2{
                width: 40%
            }
            table.description tr{
                height: 18px
            }
            .price-text{
                padding: 3px;
                border: 1px solid black;
                border-radius: 10px;
                margin-bottom: 15px;
                font-weight: bold;
            }
            .all{
                width: 100%;
                margin-bottom: 10px;    
            }
            .all .observation{
                /* display: inline-block; */
                width: 60%;
                height: 50px;
                /* background: blue; */
                vertical-align: top;
            }
            .all .all-pay{
                /* display: inline-block; */
                width: 40%;
                border: 1px solid black;
                border-radius: 10px;
                margin-bottom: 15px;
                padding: 5px;
            }
            .all .all-pay .left{
                display: inline-block;
                width: 63%;
                text-align: right
            }
            .all .all-pay .right{
                display: inline-block;
                width: 35%;
                text-align: right
            }
            .all .all-pay .bold{
                font-weight: bold;
            }
            .info-aside .qr{
                margin-top: 40px;
                margin-right: 20px;
                height: 150px;
                width: 150px;
                display: inline-block;
            }
            .info-aside .qr img{
                width: 100%;
            }
            .info-aside .info{
                display: inline-block;
                width: 75%;
                text-align: center;
                vertical-align: top;
            }
            .info-aside .info .method{
                border-radius: 15px;
                border: 1px solid black;
                padding: 5px;
                margin-bottom: 15px;
            }
            .info-aside .info .method .w-30{
                display: inline-block;
                width: 32%;
            }
            .info-aside .info .method .w-30 b{
                display: block;
            }
            .info-aside .info .secondary{
                border-radius: 15px;
                border: 1px solid black;
                padding: 5px;
            }
            .center{
                text-align: center;
            }
            .right{
                text-align: right;
            }
        </style>
    </head>
    <body>
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/img/branding/logo_svg.jpg') }}" widht="100%" height="100%">
                </td>
                <td class="data">
                    <div class="data-name">
                        {{ $business->razon_social }}
                    </div>
                    <div class="data-direction">
                        {{ $business->direccion }}<br>
                        {{ $ubigeo["distrito"] }} - {{ $ubigeo["provincia"] }} - {{ $ubigeo["departamento"] }}<br>
                        <b>Tel&eacute;fono: </b> {{ ($business->telefono == null) ? '-' : $business->telefono }}
                    </div>
                </td>
                <td class="data-ruc">
                    <div class="ruc">R.U.C. {{ $business->ruc }}</div>
                    <div class="name">ORDEN DE TRASLADO</div>
                    <div class="number">{{ $transfer->serie }}-{{ $transfer->correlativo }}</div>
                </td>
            </tr>
        </table>
        <div class="user">
            <b class="w-15">FECHA DE EMISION</b>
            <span class="w-50">: {{ date('d-m-Y', strtotime($transfer->fecha_emision)) }}</span>
            <b class="w-15"></b>
            <span class="w-20"></span>
            
            <b class="w-15">ALMACEN DESPACHO</b>
            <span class="w-50">: {{ $transfer->almacen_despacho }}</span>
            <b class="w-15"></b>
            <span class="w-20"></span>
            <b class="w-15">ALMACEN DESTINO</b>
            <span class="w-50">: {{ $transfer->almacen_receptor }}</span>
            <b class="w-15"></b>
            <span class="w-20"></span>
            <b class="w-15">U. SOLICITA</b>
            <span class="w-50">: {{ mb_strtoupper($transfer->usuario_solicita) }}</span>
        </div>
        
        <table class="description">
            <thead>
                <th class="row-1">#</th>
                <th class="row-1">CODIGO</th>
                <th class="row-2">DESCRIPCIÓN</th>
                <th class="row-1">CANT.</th>
                <th class="row-1">UND.</th>
            </thead>
            <tbody>
                @foreach ($detalle as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="center">{{ ($item["codigo_interno"] == null) ? '-' : $item["codigo_interno"] }}</td>
                    <td>{{ $item['producto'] }}</td>
                    <td class="center">{{ $item["cantidad"] }}</td>
                    <td class="center">{{ $item["unidad"] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="all">
           <tr>
                <td class="observation">
                    <b>OBSERVACIONES</b>
                    <span>{{ $transfer->observaciones }}</span>
                </td>
            </tr>
        </table>
    </body>
</html>