<div class="modal fade" id="modalArchingCash" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalArchingCashTitle">Registrar Reclamo / Queja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="cajero" class="form-label">Responsablesss</label>
                        <input type="hidden" name="idusuario" value="{{ Auth::user()['id'] }}" />
                        <input type="hidden" name="idcaja" value="{{ Auth::user()['idcaja'] }}" />
                        <input class="form-control text-uppercase" id="cajero" type="text" name="cajero"
                            value="{{ mb_strtoupper(Auth::user()['user']) }}" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="monto_inicial">Monto Inicial</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="monto_inicial" class="form-control" name="monto_inicial">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha">Fecha de Emisi&oacute;n</label>
                        <input class="form-control" id="fecha" type="date"name="fecha"
                            value="{{ date('Y-m-d') }}" />
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modalDetailReclamosQuejas" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        @include('admin.reclamos_quejas.template.partial-form')
                    </div>
                </div>

                <div class="row">
                    <form id="form-respuesta-reclamo" onsubmit="event.preventDefault()">
                        @csrf
                        <input type="hidden" name="reclamo_id" id="reclamo-id" value="">

                        <div>
                            <label for="exampleTextarea" class="form-label mt-4">Dale una respuesta al cliente</label>
                            <textarea class="form-control" name="respuesta" id="exampleTextarea" rows="3" spellcheck="false"></textarea>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-label-primary">Enviar respuesta</button>
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                @include('admin.reclamos_quejas.template.partial-form-footer')
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalArchingCash" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalArchingCashTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="cajero" class="form-label">Responsable</label>
                        <input type="hidden" name="idusuario" value="{{ Auth::user()['id'] }}" />
                        <input type="hidden" name="idcaja" value="{{ Auth::user()['idcaja'] }}" />
                        <input class="form-control text-uppercase" id="cajero" type="text" name="cajero"
                            value="{{ mb_strtoupper(Auth::user()['user']) }}" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="monto_inicial">Monto Inicial</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="monto_inicial" class="form-control" name="monto_inicial">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha">Fecha de Emisi&oacute;n</label>
                        <input class="form-control" id="fecha" type="date"name="fecha"
                            value="{{ date('Y-m-d') }}" />
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modalSummary" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="modalSummaryTitle">Resumen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 card-body">
                        <div class="bg-lighter p-4 rounded">
                            <p class="mb-1">Total de Ventas soles</p>
                            <div class="d-flex align-items-center">
                                <h1 class="text-heading display-6 mb-1 sales_amount"></h1>
                            </div>
                        </div>

                        <div class="bg-lighter p-4 rounded">
                            <p class="mb-1">Total de Ventas dolares</p>
                            <div class="d-flex align-items-center">
                                <h1 class="text-heading display-6 mb-1 sales_amount2"></h1>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Monto inicial</p>
                                <h6 class="mb-0 starting_amount"></h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Gastos</p>
                                <h6 class="mb-0 bill_empty"></h6>
                            </div>
                            <div id="wrapper_bills"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Monto de ventas</p>
                                <h6 class="mb-0 sale_empty"></h6>
                            </div>
                            <div id="wrapper_sales"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Cantidad de pagos</p>
                                <h6 class="mb-0 quantity"></h6>
                            </div>
                            <div id="wrapper_quantity"></div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mt-3 pb-1">
                                <p class="mb-0">Total</p>
                                <h6 class="mb-0 total"></h6>
                            </div>

                            <p class="mt-2 pt-2 text-justify">El <b>total</b> refiere al monto de ventas m&aacute;s el
                                monto inicial menos los gastos.</p>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <div class="download-resumen"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
