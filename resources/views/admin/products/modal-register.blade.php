<div class="modal fade" id="modalAddProduct" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save_product" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProductTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body">
                            <div class="col-xl-12">
                                <div class="card-header">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" role="tab"
                                                data-bs-toggle="tab" data-bs-target="#information"
                                                aria-controls="information"
                                                aria-selected="true" data-id="1">Informaci&oacute;n</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                                data-bs-target="#warehouses" aria-controls="warehouses"
                                                aria-selected="false">Almacenes</button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body mt-4 mb-4">
                                    <div class="tab-content p-0">
                                        <div class="tab-pane fade show active" data-id="1" id="information" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12 col-md-4 mb-3">
                                                    <label class="form-label" for="codigo_interno">C&oacute;digo
                                                        Interno</label>
                                                    <input type="text" id="codigo_interno" class="form-control"
                                                        name="codigo_interno" />
                                                </div>

                                                <div class="col-12 col-md-4 mb-3">
                                                    <label class="form-label" for="codigo_barras">C&oacute;digo
                                                        Barras</label>
                                                    <input type="text" id="codigo_barras" class="form-control"
                                                        name="codigo_barras" />
                                                </div>

                                                <div class="col-12 col-md-4 mb-3">
                                                    <label class="form-label" for="idunidad">Unidad</label>
                                                    <select name="idunidad" id="idunidad" class="form-control">
                                                        <option value=""></option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <label class="form-label"
                                                        for="descripcion">Descripci&oacute;n</label>
                                                    <input type="text" id="descripcion"
                                                        class="form-control text-uppercase" name="descripcion" />
                                                    <div class="invalid-feedback">El campo no debe estar vacío.</div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label" for="marca">Marca</label>
                                                    <input type="text" id="marca"
                                                        class="form-control text-uppercase" name="marca" />
                                                    <div class="invalid-feedback">El campo no debe estar vacío.</div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label"
                                                        for="presentacion">Presentaci&oacute;n</label>
                                                    <input type="text" id="presentacion"
                                                        class="form-control text-uppercase" name="presentacion" />
                                                    <div class="invalid-feedback">El campo no debe estar vacío.</div>
                                                </div>

                                                <div class="col-12 mb-3">
                                                    <label class="form-label" for="operacion">Operaci&oacute;n</label>
                                                    <select name="operacion" id="operacion" class="form-control">
                                                        @foreach ($type_inafects as $type_inafect)
                                                            <option value="{{ $type_inafect->id }}">
                                                                {{ $type_inafect->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">El campo no debe estar vacío.</div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label" for="precio_compra">Precio
                                                        Compra</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">S/</span>
                                                        <input type="text" id="precio_compra" class="form-control"
                                                            name="precio_compra">
                                                        <div class="invalid-feedback">El campo no debe estar vacío.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label" for="precio_venta">Precio Venta</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">S/</span>
                                                        <input type="text" id="precio_venta" class="form-control"
                                                            name="precio_venta">
                                                        <div class="invalid-feedback">El campo no debe estar vacío.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label" for="precio_venta_por_mayor">Precio Venta al por Mayor</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">S/</span>
                                                        <input type="text" id="precio_venta_por_mayor" class="form-control"
                                                            name="precio_venta_por_mayor">
                                                        <div class="invalid-feedback">El campo no debe estar vacío.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="form-label" for="precio_venta_distribuidor">Precio Venta Distribuidor</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon11">S/</span>
                                                        <input type="text" id="precio_venta_distribuidor" class="form-control"
                                                            name="precio_venta_distribuidor">
                                                        <div class="invalid-feedback">El campo no debe estar vacío.
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="form-label" for="fecha_vencimiento">Fecha de
                                                        vencimiento</label>
                                                    <input type="date" class="form-control" id="fecha_vencimiento"
                                                        name="fecha_vencimiento">
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <small class="fw-medium d-block"><label class="form-label">Tipo Producto</label></small>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="opcion" id="producto" value="1" checked>
                                                        <label class="form-check-label" for="producto">Producto</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="opcion" id="servicio" value="2">
                                                        <label class="form-check-label" for="servicio">Servicio</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="warehouses" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12"><span class="text-warning text-2">* Para
                                                        agregar más Almacenes
                                                        haga click </span> <a href="{{ route('admin.warehouses') }}"
                                                        class="text-info font-bold"> Aquí
                                                    </a>
                                                    <div class="table-responsive-sm">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Almacén</th>
                                                                    <th class="text-center" style="width: 160px;">
                                                                        Stock</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="tbody-warehouses">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save-product">
                            <span class="text-save-product">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving-product"
                                role="status" aria-hidden="true"></span>
                            <span class="text-saving-product d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
