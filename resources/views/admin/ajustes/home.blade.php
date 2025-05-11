@extends('admin.template')
@section('styles')
<style>
    body {
        overflow-x: hidden;
    }


    /* Centrado vertical */
    .uploader {
        margin-top: 20px;
    }

    .el-upload {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }


</style>


@endsection
@section('content')
<section class="basic-select2">
    <div class="row">
        <!-- Congratulations Card -->
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ajustes General de Reclamos / Quejas </h5>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form id="form_info" class="form form-vertical" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="control-label">Imágen Portada</label>
                                    <br>
                                    <div class="uploader">
                                        <div tabindex="0" class="el-upload el-upload--text">
                                            <i class="el-icon-plus uploader-icon"></i>
                                            <input type="file" name="portada_reclamos" class="el-upload__input" id="imageInput">
                                        </div>
                                    </div>
                                    <br>
                                    <!-- Contenedor para la imagen previsualizada -->
                                    <img id="imagePreview" src="" alt="Vista previa" style="display:none; max-width: 300px;" />
                                </div>

                                <!-- Cambia text-end a text-start -->
                                <button type="submit" class="btn btn-primary btn-save-info">
                                    <span class="text-save-info">Guardar</span>
                                    <span class="spinner-border spinner-border-sm me-1 d-none text-saving-info"
                                        role="status" aria-hidden="true"></span>
                                    <span class="text-saving-info d-none">Guardando...</span>
                                </button>
                            </form>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Descargar mi QR</label>
                                <br> </br>
                                <div class="uploader">
                                    <!-- Envolviendo la imagen en un enlace -->
                                    <a href="http://127.0.0.1:8000/form_reclamos" target="_blank">
                                        <img src="{{ asset('assets/img/qr/qr.png') }}" alt="QR Image" style="width: 130px !important;">
                                    </a>
                                    <br> <!-- Salto de línea para separar el botón de la imagen -->
                                    <a id="downloadBtn" class="btn btn-success mt-2" href="{{ asset('assets/img/qr/qr.png') }}" download="mi_qrcode.jpg">
                                        Descargar QR
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<br> </br>

    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Correos </h5>
                    </div>
                    <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nueva Correo</span></span></button>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Correo</th>
                                    <th width="20%">Contraseña</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.ajustes.modals')
    </div>
@endsection
@section('scripts')
@include('admin.ajustes.js-home')
@include('admin.ajustes.js-datatable')
@include('admin.ajustes.js-store')

@endsection