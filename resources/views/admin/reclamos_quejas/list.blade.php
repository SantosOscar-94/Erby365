@extends('admin.template')
@section('styles')
    <style>
        .text-dni-ruc {
            color: var(--bs-blue);
            font-weight: 500;
            letter-spacing: 1px;
        }
    </style>
    @include('admin.reclamos_quejas.template.partial-form-styles')
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Reclamos y Quejas</h5>
                    </div>
                    <a class="dt-button create-new btn btn-primary waves-effect waves-light btn-create"
                        href="{{ route('admin.reclamos_form') }}" target="_blanket">
                        <span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Realizar una
                                Queja</span></span>
                    </a>

                </div>
                <div class="container">
                    <form id="form-cashes" method="POST" class="form form-vertical" action="#" target="_blank">
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
                                $user = App\Models\User::with('roles')
                                    ->where('id', auth()->user()['id'])
                                    ->first();
                                $role = $user->roles->first();
                            @endphp

                            @if ($role->name == 'SUPERADMIN' || $role->name == 'ADMIN')
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label" for="user">Tipo de Reclamo / Queja</label>
                                    <select class="select2-size-sm form-control" id="user" name="op_queja_reclamo">
                                        <option value="0">TODOS</option>
                                        <option value="Queja">Quejas</option>
                                        <option value="Reclamo">Reclamos</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-3 mb-3">
                                    <label class="form-label" for="warehouse">Tienda</label>
                                    <select class="select2-size-sm form-control" id="warehouse" name="warehouse">
                                        <option value="0">TODOS</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->descripcion }}">
                                                {{ $warehouse->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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

                                        <span class="spinner-border spinner-border-sm text-searching d-none" role="status"
                                            aria-hidden="true"></span>
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
                                    <th>Ticket</th>
                                    <th>Fecha / Hora</th>
                                    <th>Tipo (Reclamo o Queja)</th>
                                    <th>Cliente</th>
                                    <th>Correo</th>
                                    <th>Tienda</th>
                                    <th>Monto del reclamo</th>
                                    <th>Estado</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.reclamos_quejas.modals')
    </div>
@endsection
@section('scripts')
    @include('admin.reclamos_quejas.js-datatable')
    @include('admin.reclamos_quejas.js-store')
    @include('admin.reclamos_quejas.js-formulario')
@endsection
