<div class="modal fade" id="modalAddAjustes" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save1" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddAjustesTitle">Registrar Vehiculos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="num_placa" class="form-label">Nro. de Placa </label>
                        <input type="text" id="num_placa" class="form-control text-uppercase" name="num_placa">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="tuc" class="form-label">T.U.C</label>
                        <input type="text" id="tuc" class="form-control text-uppercase" name="tuc">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="autori_placa_principal" class="form-label">Autorización de Placa principal</label>
                        <input type="text" id="autori_placa_principal" class="form-control text-uppercase" name="autori_placa_principal">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="placa_secundario " class="form-label">Nro. de Placa secundaria</label>
                        <input type="text" id="placa_secundario" class="form-control text-uppercase" name="placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="tuc_placa_secundario" class="form-label">T.U.C (placa secundaria)</label>
                        <input type="text" id="tuc_placa_secundario" class="form-control text-uppercase" name="tuc_placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="autori_placa_secundario" class="form-label">Autorización de Placa secundaria</label>
                        <input type="text" id="autori_placa_secundario" class="form-control text-uppercase" name="autori_placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" id="modelo" class="form-control text-uppercase" name="modelo">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" id="marca" class="form-control text-uppercase" name="marca">
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

<div class="modal fade" id="modalEditAjustes" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditAjustesTitle">Actualizar Vehiculos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="num_placa" class="form-label">Nro. de Placa</label>
                        <input type="hidden" name="id">
                        <input type="text" id="num_placa" class="form-control text-uppercase" name="num_placa">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="tuc" class="form-label">T.U.C</label>
                        <input type="hidden" name="id">
                        <input type="text" id="tuc" class="form-control text-uppercase" name="tuc">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="autori_placa_principal" class="form-label">Autorización de Placa principal</label>
                        <input type="hidden" name="id">
                        <input type="text" id="autori_placa_principal" class="form-control text-uppercase" name="autori_placa_principal">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="placa_secundario" class="form-label">Nro. de Placa secundaria</label>
                        <input type="hidden" name="id">
                        <input type="text" id="placa_secundario" class="form-control text-uppercase" name="placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="tuc_placa_secundario" class="form-label">T.U.C (placa secundaria) </label>
                        <input type="hidden" name="id">
                        <input type="text" id="tuc_placa_secundario" class="form-control text-uppercase" name="tuc_placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="autori_placa_secundario" class="form-label">Autorización de Placa secundaria</label>
                        <input type="hidden" name="id">
                        <input type="text" id="autori_placa_secundario" class="form-control text-uppercase" name="autori_placa_secundario">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="hidden" name="id">
                        <input type="text" id="modelo" class="form-control text-uppercase" name="modelo">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="hidden" name="id">
                        <input type="text" id="marca" class="form-control text-uppercase" name="marca">
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