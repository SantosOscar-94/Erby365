@extends('admin.template')
@section('styles')
<style>
    body{overflow-x:hidden;}
</style>
@endsection
@section('content')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header mt-2">
                    <h5 class="card-title">Nueva Compra</h5>
                </div>
                <div class="card-body">
                    <form id="form_save_buy" class="form form-vertical">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="idtipo_comprobante">Tipo Comprobante</label>
                                <select class="form-control" id="idtipo_comprobante"
                                    name="idtipo_comprobante">
                                    @foreach ($type_documents_p as $type_document)
                                        <option value="{{ $type_document->id }}">{{ $type_document->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="serie">Serie</label>
                                <input type="text" id="serie" class="form-control text-uppercase"
                                    name="serie" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="correlativo">N&uacute;mero</label>
                                <input type="text" id="correlativo" class="form-control text-uppercase"
                                    name="correlativo" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_emision">Fecha de emisión</label>
                                <input type="date" id="fecha_emision" class="form-control"
                                    name="fecha_emision" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" id="fecha_vencimiento" class="form-control"
                                    name="fecha_vencimiento" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-1 mb-3"  style="padding-top: 10px;">
                                <button title="De Soles a Dolares" type="button" class="btn btn-success" id="btn_cambioMoneda"><i class="fa-solid fa-dollar-sign fa-2x"></i></button>
                            </div>

                            <div class="col-12 col-md-5 mb-3">
                                <label class="form-label" for="dni_ruc">Proveedor</label>
                                <small class="text-primary fw-bold btn-create-provider" style="cursor: pointer">[+ Nuevo]</small>
                                <select class="select2-size-sm form-control" id="dni_ruc" name="dni_ruc">
                                        <option></option>
                                        @foreach ($providers as $provider)
                                            <option value="{{ $provider->id }}">
                                                {{ $provider->dni_ruc . ' - ' . $provider->nombres }}</option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="idalmacen">Estalecimiento</label>
                                <select class="select2-size-sm form-control" id="idalmacen" name="idalmacen">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->descripcion }}</option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="tipo_cambio">Tipo de cambio</label>
                                    <input type="text" id="tipo_cambio" class="form-control"
                                        name="tipo_cambio" value="{{session('cambio')}}" readonly>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="modo_pago">Pago:</label>
                                    <select class="form-control" id="modo_pago" name="modo_pago">
                                        @foreach ($modo_pagos as $modo_pago)
                                            <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

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
                                                <th class="text-center" width="14%">Precio Unitario</th>
                                                <th class="text-center" width="10%">Total</th>
                                                <th class="text-right" width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_buys"></tbody>
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

                            <div class="col-12">
                                <div class="col-12 text-end">
                                    <a href="{{ route('admin.buys') }}" class="btn btn-secondary">Cancelar</a>
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
        </div>
    </div>

    @include('admin.buys.modals-create')
    @include('admin.providers.modal-register')
    @include('admin.products.modal-register')
@endsection
@section('scripts')
@include('admin.buys.js-create')
@include('admin.providers.js-register')
@include('admin.products.js-register')
@endsection
