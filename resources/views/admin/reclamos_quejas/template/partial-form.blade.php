    <div id="main-container" class="mt-4 container" style="font-family: Lato;">
        <div class="mx-auto" style="min-height: 583px;">
            <div class="card">
                <header class="px-5 py-4">
                    <img class="" src="{{ asset('assets/img/logo-form-reclamos/logo_form_reclamos.png') }}"
                        alt="logo">
                    <hr>
                    <h3>LIBRO DE RECLAMACIONES</h3>
                    <h4>{{ $business->razon_social ?? '' }}<br>
                        RUC: {{ $business->ruc ?? '' }} <br>
                        {{ $business->direccion ?? '' }}
                    </h4>
                </header>
                <main>
                    <div class="bg-white px-5">
                        <form class="ant-form pt-5">
                            {{-- campos del form --}}
                            <div class="row">
                                <div class="row" style="padding-left: 12px; padding-right: 12px; row-gap: 50px">
                                    {{-- Datos del consumidor --}}
                                    <div class="row">
                                        <div class="">
                                            <h3 class="h3-field">DATOS DEL CONSUMIDOR
                                                RECLAMANTE</h3>
                                            <div class="row g-4">
                                                <div id="field-tipo_documento" class="col-12">
                                                    <div class="">
                                                        <h4>Tipo documento</h4>
                                                        <input readonly class="form-control"
                                                            value="{{ $reclamo['tipo_documento'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-documento_cliente" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Numero documento</h4>
                                                        <input type="text" readonly
                                                            value="{{ $reclamo['documento_cliente'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-nombre_cliente" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Nombre</h4>
                                                        <input type="text" readonly
                                                            value="{{ $reclamo['nombre_cliente'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-apellido_paterno" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Apellido Paterno</h4>
                                                        <input type="text" readonly
                                                            value="{{ $reclamo['apellido_paterno'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-apellido_materno" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Apellido Materno</h4>
                                                        <input type="text" readonly
                                                            value="{{ $reclamo['apellido_materno'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-direccion" class="col-12">
                                                    <div class="">
                                                        <h4>Direccion</h4>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $reclamo['direccion'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-correo" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Correo</h4>
                                                        <input readonlytype="text"
                                                            value="{{ $reclamo['correo'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-telefono" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Telefono</h4>
                                                        <input readonlytype="text" class="form-control"
                                                            value="{{ $reclamo['telefono'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-edad" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Edad</h4>
                                                        <input readonlytype="number" class="form-control"
                                                            value="{{ $reclamo['edad'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-padre_madre" class="col-12">
                                                    <div class="">
                                                        <h4>Padre o Madre</h4>
                                                        <input readonlytype="text"
                                                            value="{{ $reclamo['padre_madre'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Detalle del reclamo --}}
                                    <div class="row">
                                        <div class="">
                                            <h3 class="h3-field">DETALLE DEL RECLAMO Y
                                                PEDIDO DEL
                                                CONSUMIDOR</h3>
                                            <h4 class="h4-field">Reclamo : Disconformidad
                                                relacionada a
                                                los productos o servicios.
                                                Queja : Disconformidad no relacionada a los productos o servicios;
                                                o, malestar o descontento respecto a la atención al publico.</h4>
                                            <div class="row g-4 mt-2">
                                                <div id="field-fecha_incidente" class="col-12 col-md-6">
                                                    <div class="d-flex flex-column" style="position: relative">
                                                        <h4>Fecha Incidente</h4>
                                                        <input readonlytype="date" id="date" class="form-control"
                                                            value="{{ $reclamo['fecha_incidente'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-canal_compra" class="col-12 col-md-6">
                                                    <div class="">
                                                        <h4>Canal de compra</h4>
                                                        <input type="text"
                                                            value="{{ $reclamo['canal_compra'] ?? '' }}"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="field-tienda" class="col-12"
                                                    style="{{ isset($reclamo) && $reclamo['canal_compra'] === 'Tienda Fisica' ? '' : 'display: none;' }}">
                                                    <div class="ant-form-item">
                                                        <h4>Direccion de la tienda</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['tienda'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-direccion_tienda" class="col-12"
                                                    style="{{ isset($reclamo) && $reclamo['canal_compra'] === 'Tienda Fisica' ? '' : 'display: none;' }}">
                                                    <div class="ant-form-item">
                                                        <h4>Direccion de la tienda</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['direccion_tienda'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-bien_contratado" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <h4>Tipo de bien</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['bien_contratado'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-monto" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <h4>Monto del reclamo</h4>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">$</span>
                                                            <input readonly type="text" class="form-control"
                                                                value="{{ $reclamo['monto'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="field-descripcion_item" class="col-12">
                                                    <div class="ant-form-item">
                                                        <h4>Descripcion del Item</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['descripcion_item'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-op_queja_reclamo" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <h4>Tipo de solicitud</h4>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $reclamo['op_queja_reclamo'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-op_motivo" class="col-12 col-md-6">
                                                    <div class="ant-form-item">
                                                        <h4>Motivo</h4>
                                                        <input type="text" readonly class="form-control"
                                                            value="{{ $reclamo['op_motivo'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-detalle_reclamo" class="col-12">
                                                    <div class="ant-form-item">
                                                        <h4>Detalle del Reclamo</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['detalle_reclamo'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div id="field-pedido_realizado_a" class="col-12">
                                                    <div class="ant-form-item">
                                                        <h4>Pedido realizado a</h4>
                                                        <input readonly type="text" class="form-control"
                                                            value="{{ $reclamo['pedido_realizado_a'] ?? '' }}">
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
                                                        <input readonly type="text" class="form-control"
                                                            value=" {{ $reclamo['observaciones'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="padding-left: 12px; padding-right: 12px;">
                                        <div class="">
                                            <h3 class="h3-field">EVIDENCIA</h3>
                                            <div class="row">
                                                <div id="field-prueba_evidencia" class="col-12">
                                                    <div>
                                                        @if (isset($reclamo['observaciones']))
                                                            <img src=" {{ asset($reclamo['file_evidencia_path']) }}"
                                                                alt="Evidencia del Reclamo"
                                                                style="max-width: 100%; height: auto;">
                                                        @else
                                                            <img src="" alt="Evidencia del Reclamo"
                                                                style="max-width: 100%; height: auto;">
                                                        @endif
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
                            <hr>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>
