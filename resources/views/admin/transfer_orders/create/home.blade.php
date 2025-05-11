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
                <div class="card-header mt-1">
                    <h5 class="card-title">Nueva &Oacute;rden de Traslado</h5>
                </div>
                <div class="card-body">
                    <form id="form_save_transfer" class="form form-vertical">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="serie">Serie</label>
                                <input type="text" id="serie" class="form-control text-uppercase"
                                    name="serie" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label" for="correlativo">N&uacute;mero</label>
                                <input type="text" id="correlativo" class="form-control text-uppercase"
                                    name="correlativo" />
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="fecha_emision">Fecha de emisión</label>
                                <input type="date" id="fecha_emision" class="form-control"
                                    name="fecha_emision" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" id="fecha_vencimiento" class="form-control"
                                    name="fecha_vencimiento" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="almacen_despacho">Almac&eacute;n Despacho</label>
                                    <select class="form-control" id="almacen_despacho" name="almacen_despacho">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="almacen_receptor">Almac&eacute;n Destino</label>
                                    <select class="form-control" id="almacen_receptor" name="almacen_receptor"></select>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label" for="observaciones">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control text-uppercase" cols="8" rows="3"></textarea>
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
                                                <th class="text-right" width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody__transfer"></tbody>
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
                                    <a href="{{ route('admin.transfer_orders') }}" class="btn btn-secondary">Cancelar</a>
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
        
        @include('admin.transfer_orders.create.modals-home')
    </div>

@endsection
@section('scripts')
@include('admin.transfer_orders.create.js-home')
@endsection
