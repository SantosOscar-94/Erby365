<div class="modal fade" id="modalAddCuentas" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddCuentasTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nombre_ban" class="form-label">Nombre del Banco</label>
                        <input type="text" id="nombre_ban" class="form-control text-uppercase" name="nombre_ban">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="moneda" class="form-label">Moneda</label>
                        <input type="text" id="moneda" class="form-control text-uppercase" name="moneda">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="num_cuenta" class="form-label">Numero De Cuenta</label>
                        <input type="text" id="num_cuenta" class="form-control text-uppercase" name="num_cuenta">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="cci" class="form-label">CCI</label>
                        <input type="text" id="cci" class="form-control text-uppercase" name="cci">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status" aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditCuentas" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCuentasTitle">Actualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nombre_ban" class="form-label">Nombre del Banco</label>
                        <input type="hidden" name="id">
                        <input type="text" id="nombre_ban" class="form-control text-uppercase" name="nombre_ban">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="moneda" class="form-label">Moneda</label>
                        <input type="hidden" name="id">
                        <input type="text" id="moneda" class="form-control text-uppercase" name="moneda">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="num_cuenta" class="form-label">Numero De Cuenta</label>
                        <input type="hidden" name="id">
                        <input type="text" id="num_cuenta" class="form-control text-uppercase" name="num_cuenta">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="cci" class="form-label">CCI</label>
                        <input type="hidden" name="id">
                        <input type="text" id="cci" class="form-control text-uppercase" name="cci">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store">
                            <span class="text-store">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing" role="status" aria-hidden="true"></span>
                            <span class="text-storing d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
