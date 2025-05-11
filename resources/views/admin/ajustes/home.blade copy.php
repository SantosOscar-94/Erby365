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




<!-- Imagen de portada -->


<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const imagePreviewContainer = document.getElementById('image-preview');

        // Validar si se ha seleccionado un archivo
        if (file) {
            const img = new Image();
            img.src = URL.createObjectURL(file);

            img.onload = function() {
                const width = img.width;
                const height = img.height;

                // Verificar el tamaño de la imagen
                if (width === 318 && height === 159) {
                    // Si la imagen tiene el tamaño correcto, mostrarla
                    preview.src = img.src;
                    preview.style.display = 'block';
                    imagePreviewContainer.style.display = 'block';
                } else {
                    // Si la imagen no tiene el tamaño correcto, ocultar y mostrar un mensaje
                    preview.style.display = 'none';
                    alert('La imagen debe tener un tamaño de 318 x 159 píxeles.'); // Mensaje de error
                }
            };

            img.onerror = function() {
                // Manejo de errores si no se puede cargar la imagen
                alert('El archivo seleccionado no es una imagen válida.');
            };
        } else {
            // Si no se selecciona ningún archivo, ocultar la previsualización
            preview.style.display = 'none';
            imagePreviewContainer.style.display = 'none';
        }
    }
</script>


@endsection
@section('content')


<section class="basic-select2">
    <div class="row">

        <!-- Congratulations Card -->
        <div class="col-8 col-md-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ajustes General de Reclamos / Quejas </h5>
                    <form id="form-info" class="form form-vertical">
                        @csrf
                        <div class="row">

                            <div class="row">


                                <br> </br>






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


                            <div class="col-md-4 text-end"> <!-- Alineación a la derecha -->
                                <br> </br>
                                <div class="form-group">
                                    <label class="control-label">Imágen Portada</label>
                                    <br> </br>
                                    <div class="uploader">
                                        <div tabindex="0" class="el-upload el-upload--text">
                                            <i class="el-icon-plus uploader-icon"></i>
                                            <input type="file" name="file" class="el-upload__input" onchange="previewImage(event)">
                                        </div>
                                    </div>
                                    <!-- Aquí se mostrará la imagen cargada -->
                                    <div id="image-preview" style="margin-top: 10px; display: none;">
                                        <img id="preview" src="" alt="Previsualización" style="max-width: 100%; display: none;" />
                                    </div>
                                    <!-- Descripción sobre el tamaño de imagen -->
                                    <p style="margin-top: 10px; font-size: 14px; color: #ffffff;">
                                        Tamaño recomendado para la portada: <strong>318 x 159 píxeles</strong>. Asegúrate de que la imagen tenga un formato compatible (JPEG, PNG).
                                    </p>
                                </div>

                                <div class="col-12 text-end mb-2"> <!-- Cambia text-end a text-start -->
                                    <button type="button" class="btn btn-primary btn-save-info">
                                        <span class="text-save-info">Guardar</span>
                                        <span class="spinner-border spinner-border-sm me-1 d-none text-saving-info" role="status" aria-hidden="true"></span>
                                        <span class="text-saving-info d-none">Guardando...</span>
                                    </button>
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
                <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create-client" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo Correo</span></span></button>
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


</div>




@endsection
@section('scripts')
@include('admin.cuentas.js-home')

@endsection