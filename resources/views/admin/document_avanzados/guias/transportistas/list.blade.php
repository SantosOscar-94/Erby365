@extends('admin.template')
@section('content')
<div class="row" id="basic-table">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="card-title mb-0">Listados de transportistas</h5>
                </div>
                <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create-client" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo Transportistas</span></span></button>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table id="table" class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="8%">#</th>
                                <th>Nombre</th>
                                <th width="10%">Tipo Documento</th>
                                <th width="20%">Numero</th>
                                <th width="20%">MTC</th>
                                <th width="10%">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.document_avanzados.guias.transportistas.modal-register')
    @include('admin.document_avanzados.guias.transportistas.modals')
    
</div>
@endsection
@section('scripts')
@include('admin.document_avanzados.guias.transportistas.js-datatable')
@include('admin.document_avanzados.guias.transportistas.js-register')
@include('admin.document_avanzados.guias.transportistas.js-store')

@endsection