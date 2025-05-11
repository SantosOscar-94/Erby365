<div class="modal fade" id="modalAddWarehouse" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddWarehouseTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="descripcion" class="form-label">Descripci&oacute;n</label>
                        <input type="text" id="descripcion" class="form-control text-uppercase" name="descripcion">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="direccion" class="form-label">Direcion</label>
                        <input type="text" id="direccion" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control text-uppercase" name="telefono">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="establecimiento" class="form-label">Establecimiento</label>
                        <select name="establecimiento" id="establecimiento" class="form-control">
                            <option value="1">Oficina Principal</option>
                        </select>
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

<div class="modal fade" id="modalEditWarehouse" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditWarehouseTitle">Actualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="descripcion" class="form-label">Descripci&oacute;n</label>
                        <input type="hidden" name="id">
                        <input type="text" id="descripcion" class="form-control text-uppercase" name="descripcion">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>
                     
                    <div class="col-12 mb-3">
                        <label for="direccion" class="form-label">Direcion</label>
                        <input type="hidden" name="id">
                        <input type="text" id="direccion" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="hidden" name="id">
                        <input type="text" id="telefono" class="form-control text-uppercase" name="telefono">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="establecimiento" class="form-label">Establecimiento</label>
                        <select name="establecimiento" id="establecimiento" class="form-control">
                            <option value="1">Oficina Principal</option>
                        </select>
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