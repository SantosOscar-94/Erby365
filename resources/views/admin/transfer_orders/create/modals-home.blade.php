<div class="modal fade" id="modalAddToProduct" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="form_add_to_product" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddToProductTitle">Agregar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-xs-6 col-sm-12 col-md-4 col-lg-12 col-xl-12">
                        <label class="form-label">Producto:</label>
                        <div class="input-group">
                            <input type="text" class="form-control input__search"
                                placeholder="Buscar por nombre o código"
                                name="input__search" autocomplete="off">
                            <span class="input-group-text text-danger btn-clear-input" id="basic-addon11" style="cursor: pointer;"
                                data-bs-toggle="tooltip" data-bs-original-title="Limpiar descripción">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-x align-middle mr-25">
                                <line x1="18" y1="6" x2="6"
                                    y2="18"></line>
                                <line x1="6" y1="6" x2="18"
                                    y2="18"></line>
                            </svg>
                            </span>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-12 col-md-7 mt-2">
                                <label class="form-label">Resultado:</label>
                                <div id="wrapper__table__detail" class="table-responsive-sm" style="width: 100%; height: 350px;">
                                    <table class="table">
                                        <thead class="has-gutter">
                                            <tr class="">
                                                <input type="hidden" name="idproducto">
                                                <th colspan="1" rowspan="1">
                                                    <div class="cell">C&Oacute;DIGO</div>
                                                </th>
                                                <th colspan="1" rowspan="1">
                                                    <div class="cell">DESCRIPCIÓN</div>
                                                </th>
                                                <th colspan="1" rowspan="1">
                                                    <div class="cell">PRECIO</div>
                                                </th>
                                                <th style="width: 0px; display: none;"></th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <table cellspacing="0" cellpadding="0" border="0" class="table table-hover">
                                        <tbody id="wrapper__search"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 mt-2">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group"><label class="form-label"> Cantidad: </label>
                                            <div class="border-left rounded-left border-info">
                                                <div class="input-group">
                                                    <span class="input-group-text bootstrap-touchspin-down-product" style="cursor: pointer;"><i
                                                            class="ti ti-minus me-sm-1"></i></span>
                                                    <input type="text" class="quantity-counter text-center form-control" value="1"
                                                        name="input-cantidad">
                                                    <span class="input-group-text bootstrap-touchspin-up-product" style="cursor: pointer;"><i
                                                            class="ti ti-plus me-sm-1"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group">
                                            <label class="form-label font-weight-bold">Precio Unitario </label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">S/</span>
                                                <input type="text" class="form-control" name="input-price">
                                                <div class="invalid-feedback">El campo no debe estar vacío.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 mt-4">
                                        <h6 class="my-2 border-bottom">INFORMACIÓN</h6>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                            <label id="label__stock" class="form-label font-bold"> STOCK: </label>
                                            <div class="table-responsive-sm">
                                                <table class="table mb-0">
                                                    <tbody id="wrapper__warehouses"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="border-bottom"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save-to-product">
                            <span class="text-save-to-product">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving-to-product" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving-to-product d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>