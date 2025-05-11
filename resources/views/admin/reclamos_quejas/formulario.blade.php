<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon/icon-login.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />

    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>

    <title>Formulario - Quejas y Reclamos</title>
    @include('admin.reclamos_quejas.template.partial-form-styles')
    <style>
        @media(min-width: 767px) {
            #main-container {
                max-width: 60% !important;
            }
        }
    </style>
</head>

<body>
    <div id="main-container" class="mt-4 container" style="font-family: Lato;">
        <div class="mx-auto" style="min-height: 583px;">
            <div class="card">
                <header class="px-5 py-4">
                    <img class="" src="{{ asset('assets/img/logo-form-reclamos/logo_form_reclamos.png') }}"
                        alt="logo">
                    <hr>
                    <h3>LIBRO DE RECLAMACIONES</h3>
                    <h4>{{ $business->razon_social }}<br>
                        RUC: {{ $business->ruc }} <br>
                        {{ $business->direccion }}
                    </h4>
                </header>
                <main>
                    <div class="bg-white px-5">
                        <form id="form_reclamos" class="ant-form pt-5" enctype="multipart/form-data" method="POST">
                            @csrf
                            {{-- campos del form --}}
                            <div class="row">
                                <div class="row" style="padding-left: 12px; padding-right: 12px; row-gap: 50px">
                                    <div class="row">
                                        <div class="">
                                            <h3 class="h3-field">DATOS DEL CONSUMIDOR
                                                RECLAMANTE</h3>
                                            <div class="row g-3">
                                                <div id="field-tipo_documento" class="col-12">
                                                    <div class="">
                                                        <select class="form-select" aria-label="Default select example"
                                                            name="tipo_documento">
                                                            <option disabled selected>Tipo documento
                                                            </option>
                                                            @foreach ($identity_type as $item)
                                                                <option value="{{ $item->descripcion_documento }}">
                                                                    {{ $item->descripcion_documento }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-documento_cliente" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="number" name="documento_cliente" <input
                                                            type="number" name="documento_cliente"
                                                            placeholder="Numero de documento" class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-nombre_cliente" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="text" name="nombre_cliente"
                                                            placeholder="Nombre del cliente" class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-apellido_paterno" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="text" name="apellido_paterno"
                                                            placeholder="Apellido paterno" class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-apellido_materno" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="text" name="apellido_materno"
                                                            placeholder="Apellido materno" class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-direccion" class="col-12">
                                                    <div class="">
                                                        <input type="text" name ="direccion" class="form-control"
                                                            placeholder="Direccion">
                                                    </div>
                                                </div>
                                                <div id="field-correo" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="email" name="correo" class="form-control"
                                                            <input type="email" name="correo" class="form-control"
                                                            placeholder="Correo">
                                                    </div>
                                                </div>
                                                <div id="field-telefono" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="text" name="telefono" class="form-control"
                                                            placeholder="Telefono">
                                                    </div>
                                                </div>
                                                <div id="field-edad" class="col-12 col-md-6">
                                                    <div class="">
                                                        <input type="number" name="edad" class="form-control"
                                                            placeholder="Edad">
                                                    </div>
                                                </div>
                                                <div id="field-padre_madre" class="col-12">
                                                    <div class="">
                                                        <input type="text" name="padre_madre" class="form-control"
                                                            placeholder="Padre o Madre">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="row-gap: 30px">
                                        <div class="">
                                            <h3 class="h3-field">DETALLE DEL RECLAMO Y
                                                PEDIDO DEL
                                                CONSUMIDOR</h3>
                                            <h4 class="h4-field">Reclamo : Disconformidad
                                                relacionada a
                                                los productos o servicios.
                                                Queja : Disconformidad no relacionada a los productos o servicios;
                                                o, malestar o descontento respecto a la atención al publico.</h4>
                                            <div class="row g-3 mt-2">
                                                <div id="field-fecha_incidente" class="col-12 col-md-6">
                                                    <div class="d-flex flex-column" style="position: relative">
                                                        <input type="date" name="fecha_incidente" id="date"
                                                            class="form-control">
                                                        <span class="place-holder">Fecha Incidente</span>
                                                    </div>
                                                </div>
                                                <div id="field-canal_compra" class="col-12 col-md-6">
                                                    <div class="">
                                                        <select name="canal_compra" class="form-select">
                                                            <option value="" disabled selected>Canal de Compra
                                                            </option>
                                                            <option value="Tienda Virtual">Tienda Virtual</option>
                                                            <option value="Tienda Fisica">Tienda Fisica</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-tienda" class="col-12 col-md-6"
                                                    style="display: none;">
                                                    <div class="">
                                                        <select name="tienda" class="form-select">
                                                            <option value="" disabled selected>
                                                                Seleciona la
                                                                tienda
                                                            </option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->descripcion }}">
                                                                    {{ $warehouse->descripcion }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-direccion_tienda" class="col-12"
                                                    style="display: none;">
                                                    <div class="ant-form-item">
                                                        <input type="text" name="direccion_tienda"
                                                            class="form-control"
                                                            placeholder="Dirección del Establecimiento">
                                                    </div>
                                                </div>
                                                <div id="field-direccion_tienda" class="col-12"
                                                    style="display: none;">
                                                    <div class="ant-form-item">
                                                        <input type="text" name="direccion_tienda"
                                                            class="form-control"
                                                            placeholder="Dirección del Establecimiento">
                                                    </div>
                                                </div>
                                                <div id="field-bien_contratado" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <select name="bien_contratado" class="form-select">
                                                            <option disabled selected>Identificacion del
                                                                Bien Contrado</option>
                                                            <option value="Producto">Producto</option>
                                                            <option value="Servicio">Servicio</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-monto" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">$</span>
                                                            <input type="text" name="monto" class="form-control"
                                                                placeholder="Monto a Reclamar">
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="field-descripcion_item" class="col-12">
                                                    <div class="ant-form-item">
                                                        <input type="text" class="form-control"
                                                            name="descripcion_item"
                                                            placeholder="Descripcion del Producto">
                                                    </div>
                                                </div>
                                                <div id="field-op_queja_reclamo" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <select name="op_queja_reclamo" class="form-select">
                                                            <option value="0" disabled selected>Queja/Reclamo
                                                            </option>
                                                            <option value="Queja">Queja</option>
                                                            <option value="Reclamo">Reclamo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-op_motivo" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <select name="op_motivo" class="form-select">
                                                            <option value="0" disabled selected>Motivo de la
                                                                Queja
                                                                o
                                                                Reclamo</option>
                                                            <option value="Mala atencion - Tienda">Mala atencion -
                                                                Tienda</option>
                                                            <option value="Mala atencion - Tienda">Mala atencion -
                                                                Tienda</option>
                                                            <option value="2">Producto</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="field-detalle_reclamo" class="col-12">
                                                    <div class="ant-form-item">
                                                        <input type="text" name="detalle_reclamo"
                                                            class="form-control" placeholder="Detalle del Reclamo">
                                                    </div>
                                                </div>
                                                <div id="field-pedido_realizado_a" class="col-12">
                                                    <div class="ant-form-item">
                                                        <input type="text" class="form-control"
                                                            name="pedido_realizado_a"
                                                            placeholder="Pedido realizado a Provedor">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row" style="padding-left: 12px; padding-right: 12px;">
                                        <div class="">
                                            <h3 class="h3-field">OBSERVACIONES Y ACCIONES ADOPTADAS
                                                POR EL PROVEEDOR</h3>
                                            <div class="row"
                                                style="margin-left: -12px; margin-right: -12px; row-gap: 24px;">
                                                <div id="field-observaciones" class="col-12">
                                                    <div class="ant-form-item">
                                                        <input type="text" name="observaciones"
                                                            class="form-control" placeholder="Observaciones">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 12px; padding-right: 12px;">
                                        <div class="">
                                            <h3 class="h3-field">EVIDENCIA</h3>
                                            <div class="row">
                                                <div id="field-file_evidencia" class="">
                                                    <div class="ant-form-item">
                                                        <div class="input-group mb-3">
                                                            <input type="file" name="file_evidencia"
                                                                class="form-control" id="inputGroupFile02"
                                                                accept="image/jpeg,image/png">
                                                            <label class="input-group-text"
                                                                for="inputGroupFile02">Upload</label>
                                                        </div>
                                                        <span>(Comprobante de pago, foto del producto. etc)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 12px; padding-right: 12px;">
                                        <div class="">
                                            <h3 class="h3-field">IMPORTANTE</h3>
                                            <h4 class="h4-field">* La formulación del reclamo no
                                                impide acudir a otras vías de solución de controversias ni es
                                                requisito previo para interponer una denuncia ante Indecopi. <br>
                                                * Sin perjuicio del derecho de los consumidores de iniciar las
                                                acciones correspondientes ante las autoridades competentes, los
                                                proveedores están obligados a atender los reclamos presentados por
                                                sus consumidores y dar respuesta a los mismos en un plazo no mayor
                                                de quince (15) días hábiles improrrogables (vigente desde
                                                22/05/2022).<br><br>

                                                Se le informa que sus datos personales facilitados mediante este
                                                formulario serán usados con la finalidad de gestionar, dar
                                                respuesta, así como llevar un registro de las reclamaciones y de
                                                cumplir con las normas de protección al consumidor.</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- butones del form --}}
                            <hr>
                            <div class="row py-5">
                                <div class="">
                                    <div class="d-flex" style="justify-content: space-between; gap: 24px;">
                                        <div class="" style="">
                                            <div class="d-flex" style="gap: 16px;">
                                                <div class="ant-space-item">
                                                    <button type="submit" id="submit-form"
                                                        class="btn btn-primary">Enviar</button>
                                                </div>
                                                <div class="ant-space-item">
                                                    <button type="submit" class="btn btn-primary">Imprimir</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ant-space-item">
                                            <button type="button" class="btn btn-link">
                                                Limpiar Formulario</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ant-col"></div>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
            @include('admin.reclamos_quejas.template.partial-form-footer')
        </div>
    </div>



    {{-- @include('admin.reclamos_quejas.js-formulario') --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/feather/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pnotify/pnotify.custom.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    @include('admin.reclamos_quejas.js-formulario')
    @include('admin.reclamos_quejas.js-formulario')
</body>

</html>
