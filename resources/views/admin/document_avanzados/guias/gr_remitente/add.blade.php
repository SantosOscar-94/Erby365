@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row">
{{--        <div class="col-12">--}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Agregar guía de remisión - Remitente</h4>
                </div>
                <div class="card-body">
                    <form class="row" id="form_gr_remitente">
                        @csrf
                        <div class="form-group col-3 mb-3">
                            <label for="establecimiento">Establecimiento *</label>
                            <select class="form-control select2" id="establecimiento" name="establecimiento" required>
                                <option value="">Selecciona una oficina principal</option>
                                @foreach(\App\Models\Warehouse::all() as $warehouse)
                                    <option value="{{$warehouse->id }}">{{ $warehouse->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="serie">Serie *</label>
                            <select class="form-control select2" id="serie" name="serie" required>
                                <option value="">Selecciona una serie</option>
                                <option value="T001">T001</option>
                            </select>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="fecha-emision">Fecha de emisión *</label>
                            <input type="date" class="form-control" id="fecha-emision" name="fecha-emision" required>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="fecha-traslado">Fecha de traslado *</label>
                            <input type="date" class="form-control" id="fecha-traslado" name="fecha-traslado" required>
                        </div>
                        <div class="form-group col-3 mb-3">
                            <label for="cliente">Cliente *
                                <small class="text-primary fw-bold btn-create-client" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="cliente" name="cliente" required>
                                <option value="0">Selecciona un cliente</option>
                                @foreach(\App\Models\Client::all() as $client)
                                    <option value="{{$client->id}}">{{$client->dni_ruc}} - {{$client->nombres}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3 mb-3">
                            <label for="destintario">Destinatario *
                                <small class="text-primary fw-bold btn-create-client" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="destintario" name="destintario" required>
                                <option value="0">Selecciona un destintario</option>
                                @foreach(\App\Models\Client::all() as $client)
                                    <option value="{{$client->id}}">{{$client->dni_ruc}} - {{$client->nombres}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-3 mb-3">
                            <label for="modo-traslado">Tipo de traslado *</label>
                            <select class="form-control select2" id="modo-traslado" name="modo-traslado" required>
                                <option value="">Selecciona un modo de traslado</option>
                                <option value="Transporte privado">Transporte privado</option>
                                <option value="Transporte privado">Transporte público</option>
                            </select>
                        </div>
                        <div class="form-group col-6 mb-3">
                            <label for="motivo-traslado">Motivo de traslado *</label>
                            <select class="form-control select2" id="motivo-traslado" name="motivo-traslado" required>
                                <option value="">Selecciona un motivo de traslado</option>
                                <option value="Venta">Venta</option>
                                <option value="Compra">Compra</option>
                                <option value="Traslado entre establecimientos de la misma empresa">Traslado entre establecimientos de la misma empresa</option>
                                <option value="Importación">Importación</option>
                                <option value="Exportación">Exportación</option>
                                <option value="Otros no comprendido en ningún código del presente catálogo">Otros no comprendido en ningún código del presente catálogo</option>
                                <option value="Venta sujeta a confirmación del comprador">Venta sujeta a confirmación del comprador</option>
                                <option value="Traslado emisor itinerante de comprobantes de pago (Aquí no se está considerando el traslado a zona primaria)">Traslado emisor itinerante de comprobantes de pago (Aquí no se está considerando el traslado a zona primaria)</option>
                                <option value="Venta con entrega a terceros">Venta con entrega a terceros</option>
                                <option value="Consignación">Consignación</option>
                                <option value="Devolución">Devolución</option>
                                <option value="Recojo de bienes transformados">Recojo de bienes transformados</option>
                                <option value="Traslado de bienes para transformación">Traslado de bienes para transformación</option>
                            </select>
                        </div>
{{--                        <div class="form-group col-5 mb-3">--}}
{{--                            <label for="desc-motivo-traslado">Descripción del motivo de traslado</label>--}}
{{--                            <textarea class="form-control" id="desc-motivo-traslado" name="desc-motivo-traslado" rows="3"></textarea>--}}
{{--                        </div>--}}
                        <div class="form-group col-3 mb-3">
                            <label for="unidad-medida">Unidad de medida *</label>
                            <select class="form-control select2" id="unidad-medida" name="unidad-medida" required>
                                <option value="">Selecciona una unidad de medida</option>
                                <option value="1">Kilogramo</option>
                                <option value="2">Tonelada</option>
                            </select>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="peso-total">Peso total *</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-minus-peso" onclick="decrement('peso-total')">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="peso-total" name="peso-total" value="0" min="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-plus-peso" onclick="increment('peso-total')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="num-paquetes">Número de paquetes</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-minus-paquetes" onclick="decrement('num-paquetes')">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="num-paquetes" name="num-paquetes" value="0" min="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-plus-paquetes" onclick="increment('num-paquetes')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="num-carga">Número de carga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-minus-carga" onclick="decrement('num-paquetes')">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="num-carga" name="num-carga" value="1" min="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-plus-carga" onclick="increment('num-paquetes')">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-3 mb-3">
                            <label for="orden-pedido">Orden de pedido</label>
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Pedidos Externos">
                              <i class="fas fa-info-circle"></i>
                            </span>
                            <input type="text" class="form-control" id="orden-pedido" name="orden-pedido">
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="orden-compra">Orden de compra</label>
                            <input type="text" class="form-control" id="orden-compra" name="orden-compra">
                        </div>
                        <div class="form-group col-3 mb-3">
                            <label for="referencia">Referencia</label>
                            <select class="form-control select2" id="referencia" name="referencia">
                                <option value="">Selecciona una referencia</option>
                                <option value="Seleccionar">Seleccionar</option>
                            </select>
                        </div>
                        <div class="form-group col-2 mb-3">
                            <label for="vehicular-conf">Conf. vehicular:</label>
                            <select class="form-control" id="vehicular-conf">
                                <option value="">-SELECCIONE-</option>
                                <option value="L1">L1</option>
                                <option value="M1">M1</option>
                                <option value="C2">C2</option>
                                <option value="C3">C3</option>
                                <option value="C2RB1">C2RB1</option>
                                <option value="T2S1">T2S1</option>
                                <option value="T2S2">T2S2</option>
                                <option value="T283">T283</option>
                                <option value="T381">T381</option>
                                <option value="T3S2">T3S2</option>
                                <option value="T3S3">T3S3</option>
                            </select>
                        </div>
                        <div class="form-group col-5 mb-3">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                        </div>

                        <hr>
                        <h4>Datos de envío</h4>

                        <div class="form-group col-12 mb-3">
                            <label for="punto-partida">Punto de partida
                                <small class="text-primary fw-bold btn-create-startPoint" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="punto-partida" name="punto-partida" required>
                                <option value="0">Selecciona un punto de partida</option>
                                @foreach(\App\Models\Direccion_Partida::all() as $item)
                                    <option value="{{$item->id}}">{{$item->ubigeo}} - {{$item->direccion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="punto-llegada">Punto de llegada
                                <small class="text-primary fw-bold btn-create-startPoint" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="punto-llegada" name="punto-llegada" required>
                                <option value="0">Selecciona un punto de llegada</option>
                                @foreach(\App\Models\Direccion_Partida::all() as $item)
                                    <option value="{{$item->id}}">{{$item->ubigeo}} - {{$item->direccion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <h4>Datos del modo de traslado</h4>

                        <div class="form-group col-12 mb-3">
                            <label for="datos-conductor">Datos del conductor
                                <small class="text-primary fw-bold btn-create-driver" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="datos-conductor" name="datos-conductor" required>
                                <option value="0">Selecciona un conductor</option>
                                @foreach(\App\Models\Conductores::all() as $item)
                                    <option value="{{$item->id}}">{{$item->dni_ruc}} - {{$item->nombres}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="datos-vehiculo">Datos del vehiculo
                                <small class="text-primary fw-bold btn-create-vehicle" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="datos-vehiculo" name="datos-vehiculo" required>
                                <option value="0">Selecciona un vehiculo</option>
                                @foreach(\App\Models\Vehiculos::all() as $item)
                                    <option value="{{$item->id}}">{{$item->marca}} - {{$item->modelo}} - {{$item->num_placa}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="datos-conductor2">Datos del conductor secundario
                                <small class="text-primary fw-bold btn-create-driver" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="datos-conductor2" name="datos-conductor2" required>
                                <option value="0">Selecciona un conductor secundario</option>
                                @foreach(\App\Models\Conductores::all() as $item)
                                    <option value="{{$item->id}}">{{$item->dni_ruc}} - {{$item->nombres}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="datos-vehiculo2">Datos del vehiculo secundario
                                <small class="text-primary fw-bold btn-create-vehicle" style="cursor: pointer">[+
                                    Nuevo]</small>
                            </label>
                            <select class="form-control select2" id="datos-vehiculo2" name="datos-vehiculo2" required>
                                <option value="0">Selecciona un vehiculo secundario</option>
                                @foreach(\App\Models\Vehiculos::all() as $item)
                                    <option value="{{$item->id}}">{{$item->marca}} - {{$item->modelo}} - {{$item->num_placa}}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <div class="row invoice-add mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th width="8%" class="text-center">#</th>
                                            <th class="">Descripción</th>
                                            <th class="text-center">Und.</th>
                                            <th class="text-center" width="13%">&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;</th>
                                            <th class="text-center" width="14%">Peso</th>
                                            <th class="text-right" width="5%"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody_gr_remitente"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6 d-flex align-items-end mt-2">
                                <div class="form-group">
                                    <button type="button"
                                            class="btn btn-primary btn-add-product waves-effect waves-float waves-light"
                                            data-repeater-create="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-plus mr-25">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span class="align-middle">Agregar Producto</span>
                                    </button>
                                </div>
                            </div>

                        <div class="col-12">
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.gr_remitente') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="button" class="btn btn-primary btn-save">
                                    <span class="text-save">Guardar </span>
                                    <span class="spinner-border spinner-border-sm text-saving d-none" role="status"
                                          aria-hidden="true"></span>
                                    <span class="ml-25 align-middle text-saving d-none">Guardando...</span>
                                </button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
{{--        </div>--}}
    </div>

    <div class="modal fade" id="modalAddToProduct" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <form id="form_save_to_product" class="modal-content" onsubmit="event.preventDefault()">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddToProductTitle">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="serie">Producto</label>
                            <small class="text-primary font-weight-bold btn-create-product ws-normal"
                                   style="cursor: pointer">[+
                                Nuevo]</small>
                            <select name="product" id="product" class="form-control">
                                @foreach (\App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->descripcion . ' - S/' . $product->precio_compra }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label" for="correlativo">Cantidad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-minus-paquetes" onclick="decrement('cantidad')">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="cantidad" name="cantidad" value="0" min="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-plus-paquetes" onclick="increment('cantidad')">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label" for="peso">Peso</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-minus-paquetes" onclick="decrement('peso')">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="peso" name="peso" value="0" min="0" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-plus-paquetes" onclick="increment('peso')">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-primary btn-save">
                                <span class="text-save">Guardar</span>
                                <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                      aria-hidden="true"></span>
                                <span class="text-saving d-none">Guardando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('admin.clients.modal-register')
    @include('admin.document_avanzados.guias.conductores.modal-register')
    @include('admin.document_avanzados.guias.direcciones_partida.modal-register')
    @include('admin.document_avanzados.guias.vehiculos.modal-register')
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaActual = new Date();
        const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
        const fechaFormateada = new Intl.DateTimeFormat('es-ES', opciones).format(fechaActual);

        // Convertir fecha a YYYY-MM-DD
        const [dia, mes, anio] = fechaFormateada.split('/');
        const fechaFinal = `${anio}-${mes}-${dia}`;

        document.getElementById('fecha-emision').value = fechaFinal;
        document.getElementById('fecha-traslado').value = fechaFinal;

        $('#form_gr_remitente select').select2();
        // $('#tipo_documento1').select2('destroy');
        // $('#tipo_documento2').select2('destroy');

        //$('#form_save_to_product select[name="product"]').select2();
        $('#form_save_to_product select[name="product"]').select2({
            dropdownParent: $('#modalAddToProduct .modal-body'),
            placeholder: "[SELECCIONE]",
        });

        $('body').on('click', '.btn-add-product', function() {
            event.preventDefault();
            $('#modalAddToProduct').modal('show');
        })

        function load_cart() {
            $.ajax({
                url: "{{ route('admin.load_cart_gr_remitente') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}"
                },
                success: function(r) {
                    if (!r.status) {
                        toast_msg(r.msg, r.title, r.type);
                        return;
                    }

                    $('#tbody_gr_remitente').html(r.html_cart);
                },
                dataType: 'json'
            });
        }
        load_cart();

        $('body').on('click', '#form_save_to_product .btn-save', function() {
            event.preventDefault();
            let select_product = $('#form_save_to_product select[name="product"]').val(),
                cantidad = parseFloat($('#form_save_to_product input[name="cantidad"]').val()),
                peso = parseFloat($('#form_save_to_product input[name="peso"]').val());
            if (select_product.trim() == "") {
                toast_msg('Debe seleccionar un producto', 'warning');
                return;
            }
            if (cantidad <= 0) {
                toast_msg('Ingrese una cantidad válida', 'warning');
                return;
            }
            if (peso <= 0) {
                toast_msg('Ingrese un peso válido', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('admin.add_product_gr_remitente') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    id: select_product,
                    cantidad: cantidad,
                    peso: peso
                },
                beforeSend: function() {
                    $('#form_save_to_product .btn-save').prop('disabled', true);
                    $('#form_save_to_product .text-save').addClass('d-none');
                    $('#form_save_to_product .text-saving').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_save_to_product .btn-save').prop('disabled', false);
                        $('#form_save_to_product .text-save').removeClass('d-none');
                        $('#form_save_to_product .text-saving').addClass('d-none');
                        toast_msg(r.msg, r.type);
                        return;
                    }

                    toast_msg(r.msg, r.type);
                    $('#form_save_to_product .btn-save').prop('disabled', false);
                    $('#form_save_to_product .text-save').removeClass('d-none');
                    $('#form_save_to_product .text-saving').addClass('d-none');
                    $('#form_save_to_product input[name="cantidad"]').val('1');
                    $('#form_save_to_product input[name="peso"]').val('0');
                    $('#form_save_to_product select[name="product"]').val('').trigger('change');
                    $('#form_save_to_product select[name="product"]').select2({
                        dropdownParent: $('#modalAddToProduct .modal-body'),
                        placeholder: "[SELECCIONE]",
                    });

                    load_cart();
                },
                dataType: "json"
            });
        });

        $('body').on('click', '.btn-delete-product', function() {
            event.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.delete_product_gr_remitente') }}",
                method: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    id: id
                },
                success: function(r) {
                    if (!r.status) {
                        toast_msg(r.msg, r.title, r.type);
                        return;
                    }
                    load_cart();
                },
                dataType: 'json'
            });
            return;
        });

        $('body').on('click', '#form_gr_remitente .btn-save', function() {
            event.preventDefault();
            let form = $('#form_gr_remitente').serialize();
            $.ajax({
                url: "{{ route('admin.save_gr_remitente') }}",
                method: "POST",
                data: form,
                beforeSend: function() {
                    $('#form_gr_remitente .btn-save').prop('disabled', true);
                    $('#form_gr_remitente .text-save').addClass('d-none');
                    $('#form_gr_remitente .text-saving').removeClass('d-none');
                },
                success: function(r) {
                    if (!r.status) {
                        $('#form_gr_remitente .btn-save').prop('disabled', false);
                        $('#form_gr_remitente .text-save').removeClass('d-none');
                        $('#form_gr_remitente .text-saving').addClass('d-none');
                        $(`input[name="${r.invalid}"]`).addClass('is-invalid');
                        if (r.errors) {
                            $.each(r.errors, function(attribute, messages) {
                                // Iterar sobre cada mensaje de error
                                messages.forEach(function(message) {
                                    toast_msg(message, 'danger'); // O el tipo que prefieras
                                });
                            });
                        } else {
                            // Si hay un mensaje genérico
                            toast_msg(r.msg, r.type);
                        }
                        return;
                    }

                    $('#form_gr_remitente .btn-save').prop('disabled', false);
                    $('#form_gr_remitente .text-save').removeClass('d-none');
                    $('#form_gr_remitente .text-saving').addClass('d-none');
                    $('#form_gr_remitente select[name="modo_pago"] option[value="1"]').prop('selected', true);
                    $('#form_gr_remitente')[0].reset();

                    document.getElementById('fecha-emision').value = fechaFinal;
                    document.getElementById('fecha-traslado').value = fechaFinal;

                    toast_msg(r.msg, r.type);
                    load_cart();
                },
                dataType: "json"
            });
        });
    });

    function increment(element) {
        var input = document.getElementById(element);
        input.value = parseInt(input.value) + 1;
    }

    function decrement(element){
        var input = document.getElementById(element);
        input.value = parseInt(input.value) - 1;
    }

    // var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    // var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    //     return new bootstrap.Popover(popoverTriggerEl)
    // })
</script>

@section('scripts')
    @include('admin.clients.js-register')
    @include('admin.document_avanzados.guias.conductores.js-register')
    @include('admin.document_avanzados.guias.direcciones_partida.js-store')
    @include('admin.document_avanzados.guias.vehiculos.js-store')
@endsection
{{--@section('scripts')--}}
{{--    @include('admin.document_avanzados.guias.gr_remitente.js-home')--}}
{{--@endsection--}}
