@extends('admin.template')
<style>
    .table-responsive {
        overflow-x: auto;
    }
</style>
@section('content')
<div class="container-xxl flex-grow-1 container-p-y" style="max-width: 1530px">
    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-md-6 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body" style="height: 85vh;">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon-search31">
                                    <i class="ti ti-barcode"></i>
                                </span>
                                <input type="text" class="form-control input-search-product" placeholder="Buscar por nombre, código o código de barras" name="input-search-product" autocomplete="off">
                                <span class="input-group-text btn-create-product" id="basic-addon11" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-original-title="Crear nuevo producto">
                                    <i class="ti ti-plus"></i>
                                </span>
                                <span class="input-group-text text-danger btn-clear-input" id="basic-addon11" style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-original-title="Limpiar descripción">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="content-pos-product" class="pos mt-3 p-3 rounded overflow-auto" style="height: calc(100% - 40px);">
                        <div id="wrapper-products" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Invoice -->

        <!-- Invoice Actions -->
        <div class="col-md-6 col-12 invoice-actions">
            <div class="card invoice-preview-card">
                <div class="card-body" style="padding: 1rem 1rem;">
                    <div class="d-flex justify-content-end flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                        <label></label>
                        <div class="container d-flex justify-content-start">
                            <div class="col-lg-3 text-center">
                                <label class="col-form-label">Divisa:</label>
                            </div>
                            <div class="col-lg-7">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">1 USD </span>
                                    <!-- <span class="input-group-text" id="basic-addon1">S/</span> -->
                                    <input type="text" id="tipo_cambio" class="form-control" name="tipo_cambio" value="0.00" readonly>
                                </div>
                            </div>

                        </div>

                        <a title="Enviar por WhatsApp" type="button" class="btn btn-success" href="/billings"><i class="fa-brands fa-whatsapp fa-2x"></i></a>&nbsp;&nbsp;&nbsp;

                        <button type="button" id="btn_tipo_precio" class="btn btn-primary"><i class="fa-solid fa-hand-holding-dollar fa-2x"></i></button>

                        <button title="Lista ventas" type="button" id="btn_modalventas" data-bs-toggle="modal" data-bs-target="#ModalListaVentas" class="btn btn-blue"><i class="fa-solid fa-receipt fa-2x"></i></button>
                        &nbsp;&nbsp;&nbsp;
                        <button title="Cajas" type="button" class="btn btn-blue" id="btn_modalproducto" data-bs-toggle="modal" data-bs-target="#modalCaja">
                            <i class="fa-solid fa-cash-register fa-2x"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-blue" ></button> -->
                        &nbsp;&nbsp;&nbsp;
                        <button title="De Soles a Dolares" type="button" class="btn btn-success" id="btn_cambioMoneda"><i class="fa-solid fa-dollar-sign fa-2x"></i></button>

                    </div>

                    <hr class="my-0">
                    <div style="height: 44vh;">
                        <div class="pos table-responsive-sm border-top pos overflow-auto" style="height: calc(100% - 0.5rem); ">
                            <table class="table m-0" style="font-size: 12.5px;">
                                <thead>
                                    <tr>
                                        <th width="8%" class="text-center">#</th>
                                        <th class="text-left" width="60%">Descripción&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th class="text-center" width="13%">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th>
                                        <th class="text-center" width="14%">Precio Unitario</th>
                                        <th class="text-center" width="10%">Total</th>
                                        <th class="text-right" width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="wrapper-tbody-pos"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card invoice-preview-card mt-2">
                    <div class="pos table-responsive-sm border-top">
                        <div class="card-body">
                            <div>
                                <div id="wrapper-totals"></div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <!-- VENTAS A CREDITOS
                                    <a class="btn btn-primary waves-effect waves-light mx-2" href="/create-credit">
                                        <span class="me-2">Venta a credito</span>
                                    </a> -->

                                    <a class="btn btn-primary waves-effect waves-light mx-2" href="/create-quote">
                                        <span class="me-2">Cotizar venta</span>
                                    </a>

                                    <button class="btn btn-danger waves-effect waves-light btn-cancel-pay">
                                        <span class="me-2">Cancelar venta</span>
                                    </button>

                                    <button class="btn btn-success waves-effect waves-light btn-process-pay" style="margin-left: 5px;">
                                        <span class="me-2 text-process">Procesar Pago</span>
                                        <span class="spinner-border spinner-border-sm me-1 d-none text-processing" role="status" aria-hidden="true"></span>
                                        <span class="text-processing d-none">Espere...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="ModalListaVentas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Lista de ventas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="mb-3 col-lg-2">
                    <label for="identificacion" class="form-label">Identificación:</label>
                    <input type="text" class="form-control" id="identificacion" placeholder="Número de identificación">
                </div>

                <div class="mb-3 col-lg-2">
                  <label for="fechaDesde" class="form-label">Fecha Desde:</label>
                  <input type="date" class="form-control" id="fechaDesde">
                </div>

                <div class="mb-3 col-lg-2">
                  <label for="fechaHasta" class="form-label">Fecha Hasta:</label>
                  <input type="date" class="form-control" id="fechaHasta">
                </div>

                <div class="mb-3 col-lg-2">
                  <label for="tipoComprobante" class="form-label">Tipo de Comprobante:</label>
                  <select class="form-select" id="tipoComprobante">
                    <option value="todos">Todos</option>
                    <option value="recibo">Boleta</option>
                    <option value="factura">Factura</option>
                    <option value="nota">Nota de Venta</option>

                  </select>
                </div>
                <div class="mb-3 col-lg-2">
                  <label for="product" class="form-label">Productos:</label>
                  <select class="form-select" id="product">
                  <option value="0">Todos</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->descripcion }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-lg-2 text-center d-flex">
                    <button type="button" class="btn btn-primary" id="btnBuscarFacturas">Buscar</button>
                </div>

              </div>

            </form>
            <div class="col-12">

              <!-- centro -->
              <div class="table-responsive form-container">
                <!-- Agrega tu propio botón para generar PDF -->

                <table id="tbllistado" class="table text-nowrap table-bordered text-center">
                  <thead>
                    <th hidden>ID</th>
                    <th>Fecha Emisión</th>
                    <th>Cliente</th>
                    <th>Producto atendido</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                    <th>opciones</th>
                  </thead>
                  <tbody id="bills">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
        <!-- /Invoice Actions -->
        @include('admin.pos.modals')
        @include('admin.clients.modal-register')
        @include('admin.products.modal-register')
    </div>
</div>
@endsection
@section('scripts')
    @include('admin.clients.js-register')
    @include('admin.products.js-register')
    @include('admin.pos.js-home')
    @include('admin.arching_cashes.js-datatable')
    @include('admin.arching_cashes.js-store')
@endsection
