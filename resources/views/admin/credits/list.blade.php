@extends('admin.template')
@section('styles')
    <style>
        .text-dni-ruc {
            color: var(--bs-blue);
            font-weight: 500;
            letter-spacing: 1px;
        }
    </style>
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Ventas Generales</h5>
                    </div>
                    <a href="{{ route('admin.create_credit') }}"
                        class="dt-button create-new btn btn-primary waves-effect waves-light">
                        <span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nueva Venta</span></span>
                    </a>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">Documento</th>
                                    <th width="12%">Fecha Emisión</th>
                                    <th width="12%">Fecha Vencimiento</th>
                                    <th width="10%">Estado</th>
                                    <th width="14%">Cliente</th>
                                    <th width="8%">Moneda</th>
                                    <th width="8%">XML</th>
                                    <th width="8%">CDR</th>
                                    <th width="5%">Gravado</th>
                                    <th width="5%">IGV</th>
                                    <th width="8%">Total</th>
                                    <th width="8%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.billings.modal-send-wpp')
    </div>
@endsection
@section('scripts')
    @include('admin.credits.js-datatable')
    @include('admin.quotes.js-store')
@endsection
