@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }

        .select-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
            /* Ajusta el ancho según sea necesario */
        }

        #warehouses_filter {
            width: calc(100% - 30px);
            /* Ajusta el ancho para dejar espacio para el icono */
        }

        .clear-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            /* Tamaño de la "X" */
            color: #000;
            /* Color de la "X" */
        }
    </style>
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="card-title mb-0">
                            <h5 class="card-title mb-0">Gesti&oacute;n de Productos</h5>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-inline-flex">
                                <button
                                    class="dt-button create-new btn btn-primary waves-effect waves-light btn-create-product"
                                    style="margin-right: 3px;" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span
                                            class="d-none d-sm-inline-block">Nuevo Producto</span></span></button>
                                <button
                                    class="dt-button create-new btn btn-success waves-effect waves-light btn-upload ml-2"
                                    tabindex="0"><span><i class="ti ti-upload me-sm-1"></i><span
                                            class="d-none d-sm-inline-block">Cargar Excel</span></span></button>
                            </div>
                        </div>
                    </div>

                    {{-- Sección para los filtros --}}
                    <div class="row">
                        <div class="col-12 col-md-7 mb-3">
                            <div class="form-group">
                                <label class="form-label" for="dni_ruc">Tiendas</label>
                                <div class="select-wrapper">
                                    <select class="select2-size-sm form-control" id="warehouses_filter" name="warehouses[]"
                                        multiple>
                                        <option value="0" selected>TODOS</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="clear-filters" class="clear-icon">&times;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Descripci&oacute;n</th>
                                    <th width="10%">Und.</th>
                                    <th width="15%">Precio Compra</th>
                                    <th width="13%">Precio Venta</th>
                                    <th width="10%">Estado</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.products.modal-register')
        @include('admin.products.modals')
    </div>
@endsection
@section('scripts')
    @include('admin.products.js-datatable')
    @include('admin.products.js-register')
    @include('admin.products.js-store')
@endsection
