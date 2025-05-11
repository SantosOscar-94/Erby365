@extends('admin.template')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Arqueo de Cajas</h5>
                    </div>
                    <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Aperturar Caja</span></span></button>
                </div>
                <div class="container">
                    <form id="form-cashes" method="POST" class="form form-vertical"
                    action="#" target="_blank">
                        <div class="row">
                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_inicial">Fecha Inicial</label>
                                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial"
                                    value="{{ date('Y-m-01') }}">
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_final">Fecha Final</label>
                                <input type="date" class="form-control" id="fecha_final" name="fecha_final"
                                    value="{{ date('Y-m-t') }}">
                            </div>

                            @php
                                $user = App\Models\User::with('roles')->where('id', auth()->user()['id'])->first();
                                $role = $user->roles->first();
                            @endphp

                            @if ($role->name == "SUPERADMIN" || $role->name == "ADMIN")
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label" for="user">Vendedor</label>
                                    <select class="select2-size-sm form-control" id="user" name="user">
                                        <option value="0">TODOS</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-5 mb-3">
                                    <label class="form-label" for="warehouse">Tienda</label>
                                    <select class="select2-size-sm form-control" id="warehouse" name="warehouse">
                                        <option value="0">TODOS</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            @endif
                        
                            <!-- <div class="col-6 col-md-3 mb-3">
                                <label class="form-label" for="document">Tipo de Documento</label>
                                <select class="select2-size-sm form-control" id="document" name="document">
                                    <option value="0">TODOS</option>
                                    <option value="206">COMPRAS</option>
                                    <option value="306">TRASLADOS</option>
                                    @foreach ($type_documents as $type_document)
                                        <option value="{{ $type_document->id }}">
                                            {{ $type_document->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div> -->
                        </div>

                        <div class="row">
                            <div class="mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <button id="wrapper-input-reniec" class="btn btn-info btn-search" type="button"
                                        id="button-addon2" onclick="load_datatable()">
                                        <span class="text-search">
                                            <i class="ti ti-search" style="font-size: 15px; margin-bottom: 2px;"></i>
                                            <span class="input-text-reniec"> Buscar</span>
                                        </span>

                                        <span class="spinner-border spinner-border-sm text-searching d-none"
                                            role="status" aria-hidden="true"></span>
                                        <span class="ml-25 align-middle text-searching d-none" style="font-size: 14px;">
                                            <span style="margin-left: 3px;">Buscando...</span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Fecha de apertura</th>
                                    <th>Fecha de cierre</th>
                                    <th>Vendedor</th>
                                    <th>Tienda</th>
                                    <th>Monto apertura</th>
                                    <th>Monto cierre</th>
                                    <th>Productos vendidos</th>
                                    <th>Documentos emitidos</th>
                                    <th>Estado</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.arching_cashes.modals')
    </div>
@endsection
@section('scripts')
    @include('admin.arching_cashes.js-datatable')
    @include('admin.arching_cashes.js-store')
@endsection