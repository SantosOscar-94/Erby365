@extends('admin.template')
@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
</style>
@endsection
@section('content')
<section class="basic-select2">
<div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Direcciones de Partida</h5>
                    </div>
                    <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nueva Direccion</span></span></button>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Dirección</th>
                                    <th width="20%">Ubigeo</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.document_avanzados.guias.direcciones_partida.modal-register')
    </div>
</section>

@endsection
@section('scripts')
@include('admin.document_avanzados.guias.direcciones_partida.js-home')
@include('admin.document_avanzados.guias.direcciones_partida.js-datatable')
@include('admin.document_avanzados.guias.direcciones_partida.js-store')
@endsection