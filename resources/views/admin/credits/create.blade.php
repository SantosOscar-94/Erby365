@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header mt-2">
                    <h5 class="card-title">Nueva venta</h5>
                </div>
                <div class="card-body">
                    <form id="form_save_credit" class="form form-vertical">
                        @csrf
                        <div class="row">

                            <!-- Tipo Comprobante -->
                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="idtipo_comprobante">Tipo Comprobante</label>
                                <select class="form-control" id="idtipo_comprobante" name="idtipo_comprobante">
                                    @foreach ($type_documents_p as $type_document)
                                        <option value="{{ $type_document->id }}">
                                            {{ $type_document->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="tipo_operacion" class="form-label">
                                        Tipo de operación
                                    </label>
                                    <select class="form-control" id="tipo_operacion" name="tipo_operacion">
                                        <option selected value="1">Venta Interna</option>
                                        <option value="2">Exportacion de Bienes</option>
                                        <option value="3">Ventas no domiciliados que no califican como exportacion
                                        </option>
                                        <option value="4">Operación Sujeta a Detracción</option>
                                        <option value="5">Operación Sujeta a Detracción - Servicios de Transporte Carga
                                        </option>
                                        <option value="6">Compra interna</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-1 mb-3"></div>

                            <!-- Serie -->
{{--                            <div class="col-12 col-md-2 mb-3">--}}
{{--                                <label class="form-label" for="serie" disabled>Serie</label>--}}
{{--                                <input type="text" id="serie" class="form-control text-uppercase"--}}
{{--                                       name="serie" value="{{ $serie->serie }}" />--}}
{{--                            </div>--}}

{{--                            <div class="col-12 col-md-2 mb-3">--}}
{{--                                <label class="form-label" for="correlativo" disabled>N&uacute;mero</label>--}}
{{--                                <input type="text" id="correlativo" class="form-control text-uppercase"--}}
{{--                                       name="correlativo" value="{{ $serie->correlativo }}" />--}}
{{--                            </div>--}}


                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_emision">Fecha de emisión</label>
                                <input type="date" id="fecha_emision" class="form-control" name="fecha_emision"
                                    value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" id="fecha_vencimiento" class="form-control" name="fecha_vencimiento"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-7 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="dni_ruc">Cliente</label>
                                    <small class="text-primary fw-bold btn-create-client" style="cursor: pointer">[+
                                        Nuevo]</small>
                                    <select class="select2-size-sm form-control" id="dni_ruc" name="dni_ruc">
                                        <option></option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->dni_ruc . ' - ' . $client->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-1 mb-3"></div>

                            <div class="col-12 col-md-2 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="modo_pago">Moneda:</label>
                                    <select class="form-control" id="modo_pago" name="modo_pago">
                                        <option value="1">Soles</option>
                                        <option value="2">Dolar Americano</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="tipo_cambio">Tipo de cambio</label>
                                    <input type="text" id="tipo_cambio" class="form-control" name="tipo_cambio"
                                        value="{{ session('cambio') }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- informacion adicitonal - tap --}}
                        <div class="row border-top no-gutters">
                            <div class="col-xl-12 col-md-12">
                                <div role="tablist" aria-multiselectable="true" class="el-collapse">
                                    <div class="el-collapse-item">
                                        <div role="tab" aria-controls="el-collapse-content-1825"
                                            aria-describedby="el-collapse-content-1825">
                                            <div role="button" id="el-collapse-head-1825" tabindex="0"
                                                class="el-collapse-item__header"><span class="ml-2">Información
                                                    Adicional</span><i
                                                    class="el-collapse-item__arrow el-icon-arrow-right"></i></div>
                                        </div>
                                        <div role="tabpanel" aria-hidden="true" aria-labelledby="el-collapse-head-1825"
                                            id="el-collapse-content-1825" class="el-collapse-item__wrap"
                                            style="display: none;">
                                            <div class="el-collapse-item__content">
                                                <div class="col-xl-12 col-md-12">
                                                    <div class="row">
                                                        <div class="col-xl-3 col-md-3 col-12 pt-2 pb-2"><span
                                                                class="mr-2">Comprobante
                                                                contingencia</span>
                                                            <div role="switch" class="el-switch"><input type="checkbox"
                                                                    name="" true-value="true"
                                                                    class="el-switch__input"><!----><span
                                                                    class="el-switch__core"
                                                                    style="width: 40px;"></span><!----></div>
                                                        </div>
                                                        <div class="col-xl-3 col-md-3 col-12 pt-2 pb-2"><span
                                                                class="mr-2">
                                                                Pago anticipado
                                                            </span>
                                                            <div role="switch" class="el-switch"><input type="checkbox"
                                                                    name="" true-value="true"
                                                                    class="el-switch__input"><!----><span
                                                                    class="el-switch__core"
                                                                    style="width: 40px;"></span><!----></div>
                                                        </div>
                                                        <div class="col-xl-3 col-md-3 col-12 pt-2 pb-2"><span
                                                                class="mr-2">Deducción de
                                                                anticipados</span>
                                                            <div role="switch" class="el-switch"><input type="checkbox"
                                                                    name="" true-value="true"
                                                                    class="el-switch__input"><!----><span
                                                                    class="el-switch__core"
                                                                    style="width: 40px;"></span><!----></div>
                                                        </div>
                                                        <div class="col-xl-3 col-md-3 col-12 pt-2 pb-2"><span
                                                                class="mr-2">
                                                                Comprobante con
                                                                retención
                                                            </span>
                                                            <div role="switch" class="el-switch"><input type="checkbox"
                                                                    name="" true-value="true"
                                                                    class="el-switch__input"><!----><span
                                                                    class="el-switch__core"
                                                                    style="width: 40px;"></span><!----></div>
                                                        </div> <!---->
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-md-12"><!----> <!----> <!----></div>
                                                <div class="row p-2">
                                                    <div class="col-12">
                                                        <div class="form-group"><label class="control-label">
                                                                Guías
                                                            </label>
                                                            <table style="width: 100%;">
                                                                <tr>
                                                                    <td>
                                                                        <div class="el-select el-select--small"><!---->
                                                                            <div
                                                                                class="el-input el-input--small el-input--suffix">
                                                                                <!----><input type="text"
                                                                                    autocomplete="off"
                                                                                    placeholder="Seleccionar"
                                                                                    class="el-input__inner"><!----><span
                                                                                    class="el-input__suffix"><span
                                                                                        class="el-input__suffix-inner"><i
                                                                                            class="el-select__caret el-input__icon el-icon-arrow-up"></i><!----><!----><!----><!----><!----></span><!----></span><!----><!---->
                                                                            </div>
                                                                            <div class="el-select-dropdown el-popper"
                                                                                style="display: none;">
                                                                                <div class="el-scrollbar" style="">
                                                                                    <div class="el-select-dropdown__wrap el-scrollbar__wrap"
                                                                                        style="margin-bottom: -26px; margin-right: -26px;">
                                                                                        <ul
                                                                                            class="el-scrollbar__view el-select-dropdown__list">
                                                                                            <!---->
                                                                                            <li
                                                                                                class="el-select-dropdown__item selected">
                                                                                                <span>Guia de remisión
                                                                                                    remitente</span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="el-select-dropdown__item">
                                                                                                <span>Guía de remisión
                                                                                                    transportista</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div
                                                                                        class="el-scrollbar__bar is-horizontal">
                                                                                        <div class="el-scrollbar__thumb"
                                                                                            style="transform: translateX(0%);">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="el-scrollbar__bar is-vertical">
                                                                                        <div class="el-scrollbar__thumb"
                                                                                            style="transform: translateY(0%);">
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!---->
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="el-input el-input--small"><!----><input
                                                                                type="text" autocomplete="off"
                                                                                class="el-input__inner"><!----><!----><!----><!---->
                                                                        </div>
                                                                    </td>
                                                                    <td align="right"><button type="button"
                                                                            class="btn waves-effect waves-light btn-sm btn-danger"><i
                                                                                class="fa fa-trash"></i></button></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="el-select el-select--small"><!---->
                                                                            <div
                                                                                class="el-input el-input--small el-input--suffix">
                                                                                <!----><input type="text"
                                                                                    autocomplete="off"
                                                                                    placeholder="Seleccionar"
                                                                                    class="el-input__inner"><!----><span
                                                                                    class="el-input__suffix"><span
                                                                                        class="el-input__suffix-inner"><i
                                                                                            class="el-select__caret el-input__icon el-icon-arrow-up"></i><!----><!----><!----><!----><!----></span><!----></span><!----><!---->
                                                                            </div>
                                                                            <div class="el-select-dropdown el-popper"
                                                                                style="display: none;">
                                                                                <div class="el-scrollbar" style="">
                                                                                    <div class="el-select-dropdown__wrap el-scrollbar__wrap"
                                                                                        style="margin-bottom: -26px; margin-right: -26px;">
                                                                                        <ul
                                                                                            class="el-scrollbar__view el-select-dropdown__list">
                                                                                            <!---->
                                                                                            <li
                                                                                                class="el-select-dropdown__item">
                                                                                                <span>Guia de remisión
                                                                                                    remitente</span>
                                                                                            </li>
                                                                                            <li
                                                                                                class="el-select-dropdown__item selected">
                                                                                                <span>Guía de remisión
                                                                                                    transportista</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div
                                                                                        class="el-scrollbar__bar is-horizontal">
                                                                                        <div class="el-scrollbar__thumb"
                                                                                            style="transform: translateX(0%);">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="el-scrollbar__bar is-vertical">
                                                                                        <div class="el-scrollbar__thumb"
                                                                                            style="transform: translateY(0%);">
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!---->
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="el-input el-input--small"><!----><input
                                                                                type="text" autocomplete="off"
                                                                                class="el-input__inner"><!----><!----><!----><!---->
                                                                        </div>
                                                                    </td>
                                                                    <td align="right"><button type="button"
                                                                            class="btn waves-effect waves-light btn-sm btn-danger"><i
                                                                                class="fa fa-trash"></i></button></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"><label class="control-label"><a
                                                                                href="#"><i
                                                                                    class="fa fa-plus font-weight-bold text-info"></i>
                                                                                <span
                                                                                    style="color: rgb(119, 119, 119);">Agregar
                                                                                    guía</span></a></label></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 py-2">
                                                        <div class="form-group"><label class="control-label">Orden de
                                                                Compra
                                                                <i class="el-tooltip fa fa-info-circle item"
                                                                    aria-describedby="el-tooltip-2984"
                                                                    tabindex="0"></i></label>
                                                            <div class="el-textarea el-input--small">
                                                                <textarea autocomplete="off" class="el-textarea__inner" style="min-height: 32px;"></textarea><!---->
                                                            </div> <!---->
                                                        </div>
                                                    </div>
                                                    <div class="col-12 py-2">
                                                        <div class="form-group"><label
                                                                class="control-label">Observaciones</label>
                                                            <div class="el-textarea el-input--small">
                                                                <textarea autocomplete="off" class="el-textarea__inner" style="min-height: 32px; height: 32px;"></textarea><!---->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 py-2">
                                                        <div class="form-group"><label class="control-label">N°
                                                                Placa</label>
                                                            <div class="el-textarea el-input--small">
                                                                <textarea autocomplete="off" class="el-textarea__inner" style="min-height: 32px;"></textarea><!---->
                                                            </div> <!---->
                                                        </div>
                                                    </div> <!---->
                                                    <div class="col-12 py-2"><span class="mr-3">Mostrar términos y
                                                            condiciones.</span>
                                                        <div role="switch" aria-checked="true"
                                                            class="el-switch is-checked"><input type="checkbox"
                                                                name="" true-value="true"
                                                                class="el-switch__input"><!----><span
                                                                class="el-switch__core"
                                                                style="width: 40px;"></span><!----></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!---->
                        </div>

                        <div class="row invoice-add">
                            <div class="col-md-12">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th width="8%" class="text-center">#</th>
                                                <th class="">Descripción</th>
                                                <th class="text-center">Und.</th>
                                                <th class="text-center" width="13%">
                                                    &nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;</th>
                                                <th class="text-center" width="14%">Precio Unitario</th>
                                                <th class="text-center" width="10%">Total</th>
                                                <th class="text-right" width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_credits"></tbody>
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

                            <div class="col-md-12 d-flex justify-content-end -0 p-sm-4">
                                <div id="wrapper_totals" class="invoice-calculations"></div>
                            </div>
                        </div>

                        {{-- opciones condicon de pago --}}
                        <div class="row mt-5">

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    {{-- pago contado --}}
                                    <table id="op-pago-contado" class="text-left table">
                                        <thead>
                                            <tr><!---->
                                                <th style="width: 120px;">
                                                    Método
                                                    de
                                                    pago
                                                </th>
                                                <th style="width: 120px;">
                                                    Destino
                                                    <i class="el-tooltip fa fa-info-circle item"
                                                        aria-describedby="el-tooltip-8338" tabindex="0"></i>
                                                </th>
                                                <th style="width: 100px;">
                                                    Referencia
                                                    <i class="el-tooltip fa fa-info-circle item"
                                                        aria-describedby="el-tooltip-9485" tabindex="0"></i>
                                                </th>
                                                <th style="width: 100px;">
                                                    Glosa
                                                    <i class="el-tooltip fa fa-info-circle item"
                                                        aria-describedby="el-tooltip-4476" tabindex="0"></i>
                                                </th>
                                                <th style="width: 100px;">
                                                    Monto
                                                </th>
                                                <th style="width: 30px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><!---->
                                                <td>
                                                    <div class="el-select el-select--small"><!---->
                                                        <select class="form-control" name="" id="">
                                                            <option value="1">Efectivo</option>
                                                            <option value="2">Tarjeta de crédito</option>
                                                            <option value="3">Tarjeta de debito</option>
                                                            <option value="4">Transferencia</option>
                                                            <option value="5">Tarjeta crédito visa</option>
                                                            <option value="6">Contado contraentrega</option>
                                                            <option value="7">Contado</option>
                                                            <option value="8">VENDEMAS</option>
                                                            <option value="9">YAPE</option>
                                                            <option value="10">PLIN</option>
                                                            <option value="11">Transf.BCP SOLES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-select el-select--small"><!---->
                                                        <select class="form-control" name="" id="">
                                                            <option value="1">BANCO DE
                                                                CREDITO DEL PERU - PEN - BCP CTA CTE
                                                                SOLES</option>
                                                            <option value="2">BANCO DE
                                                                CREDITO DEL PERU - USD - BCP CTA CTE
                                                                DOLARES</option>
                                                            <option value="3">CAJA GENERAL - Administrador</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="text" autocomplete="off" class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="text" autocomplete="off" class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="text" autocomplete="off" class="form-control">
                                                    </div>
                                                </td>
                                                <td class="text-center"><button type="button"
                                                        class="btn waves-effect waves-light btn-sm btn-danger"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="5" class="py-3">
                                                    <label class="control-label"><a href="#"><i
                                                                class="fa fa-plus font-weight-bold text-info"></i> <span
                                                                style="color: rgb(119, 119, 119);">Agregar
                                                                pago</span></a>
                                                    </label>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    {{-- pago credito --}}
                                    <table width="100%" id="op-pago-credito" class="text-left table"
                                        style="display: none">
                                        <thead>
                                            <tr>
                                                <th style="width: 120px;">
                                                    Método
                                                    de
                                                    pago
                                                </th>
                                                <th class="text-left" style="width: 100px;">
                                                    Fecha
                                                </th>
                                                <th class="text-left" style="width: 100px;">
                                                    Monto
                                                </th>
                                                <th style="width: 30px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="text" autocomplete="off"
                                                            value="FACTURA A 30 DIAS" class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="date" autocomplete="off" class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small"><!---->
                                                        <input type="text" autocomplete="off" class="form-control">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    {{-- pago credito --}}
                                    <table width="100%" id="op-pago-credito-cuotas" class="text-left table"
                                        style="display: none;">
                                        <thead>
                                            <tr>
                                                <th style="width: 120px;">Número de cuotas</th>
                                                <th style="width: 120px;">Valor de las cuotas</th>
                                                <th style="width: 120px;">Tipo de cuotas</th>
                                                <th style="width: 120px;">¿Cuota inicial?</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="el-input el-input--small">
                                                        <input class="form-control" type="number" min="1"
                                                            value="1" name="numero_cuotas" id="cuotas" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small">
                                                        <input id="valor_cuotas" class="form-control" type="number"
                                                            readonly name="valor_cuotas" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small">
                                                        <select class="form-control" name="tipo_cuota" id="tipo_cuotas"
                                                            required>
                                                            <option value="Semanal">Semanal</option>
                                                            <option value="Quincenal">Quincenal</option>
                                                            <option value="Mensual">Mensual</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="el-input el-input--small">
                                                        <select class="form-control" name="tipo_cuota" required>
                                                            <option value="0">No</option>
                                                            <option value="1">Si</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <div class="col-12 text-end">
                                <a href="{{ route('admin.credits') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="button" class="btn btn-primary btn-save">
                                    <span class="text-save">Guardar </span>
                                    <span class="spinner-border spinner-border-sm text-saving d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="ml-25 align-middle text-saving d-none">Guardando...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.credits.modal-detracion')
    @include('admin.credits.modals-create')
    @include('admin.clients.modal-register')
    @include('admin.products.modal-add-cart')
    @include('admin.products.modal-register')
@endsection
@section('scripts')
    @include('admin.credits.js-create')
    @include('admin.clients.js-register')
    @include('admin.products.js-register')
@endsection
