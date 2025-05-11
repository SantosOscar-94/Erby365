@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
    <style>
    .table-responsive-sm {
        overflow-x: auto;
    }
</style>
@endsection
@section('content')
<div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title col-12">
                        <h5 class="card-title">Cuentas por cobrar</h5>

                        <form id="form-accounts_receivable" method="POST" class="form form-vertical"
                            action="{{ route('admin.download_accounts_receivable') }}" target="_blank">
                            @csrf
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
                                    <div class="col-12 col-md-2 mb-3">
                                        <label class="form-label" for="user">Vendedor</label>
                                        <select class="select2-size-sm form-control" id="user" name="user">
                                            <option value="0">TODOS</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->nombres }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label" for="warehouse">Tienda</label>
                                        <select class="select2-size-sm form-control" id="warehouse" name="warehouse">
                                            <option value="0">TODOS</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" @if ($warehouse->id == auth()->user()->idalmacen) selected @endif >
                                                    {{ $warehouse->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                @endif

                                <div class="col-6 col-md-2 mb-3">
                                    <label class="form-label" for="document">Tipo de Documento</label>
                                    <select class="select2-size-sm form-control" id="document" name="document">
                                        <option value="0">TODOS</option>
                                        @foreach ($type_documents as $type_document)
                                            <option value="{{ $type_document->id }}">
                                                {{ $type_document->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-2 mb-3">
                                    <label class="form-label" for="orden">Orden de compra</label>
                                    <input type="text" class="form-control" id="orden" name="orden" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="mt-3">
                                    <div class="col-12 d-flex justify-content-between">
                                        <button class="btn btn-info btn-search" type="button" onclick="load_datatable()">
                                            <span class="text-search">
                                                <i class="ti ti-search" style="font-size: 15px; margin-bottom: 2px;"></i>
                                                <span class="input-text-reniec"> Buscar</span>
                                            </span>

                                            <span class="spinner-border spinner-border-sm text-searching d-none"
                                                role="status" aria-hidden="true"></span>
                                            <span class="ml-25 align-middle text-searching d-none" style="font-size: 14px;">
                                                <span style="margin-left: 3px;">Buscando...</span></span>
                                        </button>

                                        <div>
                                            <button type="button" class="btn btn-danger btn_export_pdf" name="export_pdf"
                                                value="1">
                                                <i class="fa fa-file-pdf"></i> <span style="margin-left: 3px;">PDF</span>
                                            </button>

                                            <button type="button" class="btn btn-success btn_export_excel"
                                                name="export_excel" value="1">
                                                <i class="fa fa-file-excel"></i> <span style="margin-left: 3px;">Excel</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Fecha emisión</th>
                                    <th class="text-center">fecha vencimiento</th>
                                    <th class="text-center">comprobante</th>
                                    <th class="text-center">cliente</th>
                                    <th class="text-center">vendedor</th>
                                    <th class="text-center">deuda</th>
                                    <th class="text-center">días de retraso</th>
                                    <th class="text-center">total</th>
                                    <th class="text-center">opciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @include('admin.reports.accounts_receivable.modals')
</div>
@endsection
@section('scripts')
    @include('admin.reports.accounts_receivable.js-datatable')
    @include('admin.reports.accounts_receivable.js-home')
@endsection
