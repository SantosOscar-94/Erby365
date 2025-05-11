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
    <div class="row">
        <!-- Congratulations Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Conductores :
                        <div class="text-end mb-4 d-inline p-6 float-end">
                            <button class="dt-button btn btn-primary waves-effect waves-light btn-create-client" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo Conductor</span></span></button>
                        </div>
                    </h5>
                    <form id="form-purchases-general" method="POST" class="form form-vertical"
                        action="{{ route('admin.export_purchases_general') }}" target="_blank">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-12 col-md-2 mb-3">--}}
                            {{-- <label class="form-label" for="fecha_inicial">Fecha Inicial</label>--}}
                            {{-- <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial"--}}
                            {{-- value="{{ date('Y-m-01') }}">--}}
                            {{-- </div>--}}

                            {{-- <div class="col-12 col-md-2 mb-3">--}}
                            {{-- <label class="form-label" for="fecha_final">Fecha Final</label>--}}
                            {{-- <input type="date" class="form-control" id="fecha_final" name="fecha_final"--}}
                            {{-- value="{{ date('Y-m-t') }}">--}}
                            {{-- </div>--}}

                            {{-- <div class="col-12 col-md-5 mb-3">--}}
                            {{-- <label class="form-label" for="idtipo_documento">Tipo de Documento</label>--}}
                            {{-- <select class="select2-size-sm form-control" id="idtipo_documento" name="idtipo_documento">--}}
                            {{-- <option value="0">TODOS</option>--}}
                            {{-- @foreach ($type_documents as $type_document)--}}
                            {{-- <option value="{{ $type_document->id }}">--}}
                            {{-- {{ $type_document->descripcion }}</option>--}}
                            {{-- @endforeach--}}
                            {{-- </select>--}}
                            {{-- </div>--}}
                        </div>

                        <div class="row">
                            <div class="mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <button id="wrapper-input-reniec" class="btn btn-info btn-search" type="button"
                                        id="button-addon2">
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
                                        <button type="buttom" class="btn btn-danger btn_export_pdf" name="export_pdf"
                                            value="1">
                                            <i class="fa fa-file-pdf"></i> <span style="margin-left: 3px;">PDF</span>
                                        </button>

                                        <button type="buttom" class="btn btn-success btn_export_excel"
                                            name="export_excel" value="1">
                                            <i class="fa fa-file-excel"></i> <span style="margin-left: 3px;">Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="mb-4 mt-5">

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="card-title" style="font-size: 15px;">Se encontraron <span
                                    class="quantity">0</span> registros</h6>
                            <div class="table-responsive-sm mt-3">
                                <table class="table table-sm mb-0 fs--1">
                                    <thead>

                                        <tr> 
                                            <th class="text-center">#</th>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">Tipo de documento</th>
                                            <th class="text-center">Número</th>
                                            <th class="text-center">Licencia</th>
                                            <th class="text-center">Acciones</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody id="wrapper_tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('admin.document_avanzados.guias.conductores.modal-register')
@endsection
@section('scripts')
@include('admin.document_avanzados.guias.conductores.js-home')
@endsection