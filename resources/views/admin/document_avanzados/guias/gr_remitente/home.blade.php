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
                        <h5 class="card-title">Listado Remitentes:
                            <div class="text-end mb-4 d-inline p-6 float-end">
                                <a href="{{ route('admin.add_gr_remitente') }}"
                                   class="dt-button create-new btn btn-primary waves-effect waves-light btn-create"
                                   tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span
                                            class="d-none d-sm-inline-block">Nueva Guia</span></span></a>
                                <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create"
                                        tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span
                                            class="d-none d-sm-inline-block">Generar Comprobante desde multiples guias</span></span>
                                </button>
                            </div>
                        </h5>
                        <form id="form-sales-general1" method="POST" class="form form-vertical"
                              action="#" target="_blank">
                            @csrf
{{--                            <div class="row">--}}
{{--                                <div class="col-12 col-md-2 mb-3">--}}
{{--                                    <label class="form-label" for="fecha_inicial">Fecha Inicial</label>--}}
{{--                                    <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial"--}}
{{--                                           value="{{ date('Y-m-01') }}">--}}
{{--                                </div>--}}

{{--                                <div class="col-12 col-md-2 mb-3">--}}
{{--                                    <label class="form-label" for="fecha_final">Fecha Final</label>--}}
{{--                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final"--}}
{{--                                           value="{{ date('Y-m-t') }}">--}}
{{--                                </div>--}}

{{--                                <div class="col-12 col-md-5 mb-3">--}}
{{--                                    <label class="form-label" for="idclient">Cliente</label>--}}
{{--                                    <select class="select2-size-sm form-control" id="idclient" name="idclient">--}}
{{--                                        <option value="0">TODOS</option>--}}
{{--                                        @foreach ($clients as $client)--}}
{{--                                            <option value="{{ $client->id }}">--}}
{{--                                                {{ $client->dni_ruc . ' - ' . $client->nombres }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}

{{--                                <div class="col-12 col-md-3 mb-3">--}}
{{--                                    <label class="form-label" for="document">Documento</label>--}}
{{--                                    <select class="select2-size-sm form-control" id="document" name="document">--}}
{{--                                        <option value="0">TODOS</option>--}}
{{--                                        @foreach ($type_documents as $type_document)--}}
{{--                                            <option value="{{ $type_document->id }}">--}}
{{--                                                {{ $type_document->descripcion }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="row">
                                <div class="mt-3">
                                    <div class="col-12 d-flex justify-content-between">
                                        <div>
                                            <button id="wrapper-input-reniec" class="btn btn-info btn-search"
                                                    type="button"
                                                    id="button-addon2">
                                            <span class="text-search">
                                                <i class="ti ti-search"
                                                   style="font-size: 15px; margin-bottom: 2px;"></i>
                                                <span class="input-text-reniec"> Buscar</span>
                                            </span>

                                                <span class="spinner-border spinner-border-sm text-searching d-none"
                                                      role="status" aria-hidden="true"></span>
                                                <span class="ml-25 align-middle text-searching d-none"
                                                      style="font-size: 14px;">
                                                <span style="margin-left: 3px;">Buscando...</span></span>
                                            </button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="mb-4 mt-5">

                        <div class="row">
                            <div class="p-3">
                                <div class="table-responsive">
                                    <table id="table" class="table table-sm">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="10%" class="text-center">Fecha Emisión</th>
                                            <th class="text-left">Cliente</th>
                                            <th width="9%" class="text-center">Número</th>
                                            <th width="7%" class="text-center">Estado</th>
                                            <th width="7%" class="text-center">Fecha Envío</th>
{{--                                            <th width="7%" class="text-center">Comprobante</th>--}}
                                            <th width="4%" class="text-center">XML</th>
                                            <th width="4%" class="text-center">CDR</th>
                                            <th width="7%" class="text-center">PDF</th>
                                            <th width="10%" class="text-center">Acciones</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @include('admin.document_avanzados.guias.gr_remitente.js-home')
    @include('admin.document_avanzados.guias.gr_remitente.js-datatable')
@endsection
