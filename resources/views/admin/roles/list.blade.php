@extends('admin.template')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Roles</h5>
                    </div>
                    <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo Rol</span></span></button>
                </div>
                <div class="p-3">
                    <div class="table-responsive-sm">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Descripci&oacute;n</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.roles.modals')
    </div>
@endsection
@section('scripts')
    @include('admin.roles.js-datatable')
    @include('admin.roles.js-store')
@endsection
