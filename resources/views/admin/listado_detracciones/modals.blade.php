<div class="modal fade" id="modalAddCuentas" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddCuentasTitle">Registrar Tipo Detracción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">


                    <div class="form-group col-6 mb-3">
                        <label for="tipo_detra">Tipo operación </label>
                        <select class="form-control select2" id="tipo_detra" name="tipo_detra" required>
                            <option value="">Selecciona una serie</option>
                            <option value="1001">Operacion Sujeta a Detraccion</option>
                            <option value="1004">Operacion Sujeta Detraccion- Servicios de Transporte Carga</option>
                        </select>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="codigo_detra" class="form-label">Código</label>
                        <input type="text" id="codigo_detra" class="form-control text-uppercase" name="codigo_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="descripcion_detra" class="form-label">Descripción</label>
                        <input type="text" id="descripcion_detra" class="form-control text-uppercase"
                            name="descripcion_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="porcentaje_detra" class="form-label">Porcentaje</label>
                        <input type="text" id="porcentaje_detra" class="form-control text-uppercase"
                            name="porcentaje_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="estado_detra" class="form-label">Estado</label>
                        <div class="form-check form-switch">
                            <!-- Input oculto para enviar el valor "off" cuando el switch no esté marcado -->
                            <input type="hidden" name="estado_detra" value="off">
                            <!-- Switch visible -->
                            <input class="form-check-input" type="checkbox" name="estado_detra" id="toggleSwitch"
                                value="on">
                            <label class="form-check-label" for="toggleSwitch">Activar / Desactivar</label>
                        </div>
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

                    <div class="form-group col-6 mb-3">
                        <label for="tipo_detra">Tipo operación </label>
                        <select class="form-control select2" id="tipo_detra" name="tipo_detra" required>
                            <option value="">Selecciona una serie</option>
                            <option value="1001">Operacion Sujeta a Detraccion</option>
                            <option value="1004">Operacion Sujeta Detraccion- Servicios de Transporte Carga</option>
                            <input type="hidden" name="id">
                        </select>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="codigo_detra" class="form-label">Código</label>
                        <input type="text" id="codigo_detra" class="form-control text-uppercase" name="codigo_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="descripcion_detra" class="form-label">Descripción</label>
                        <input type="text" id="descripcion_detra" class="form-control text-uppercase"
                               name="descripcion_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="porcentaje_detra" class="form-label">Porcentaje</label>
                        <input type="text" id="porcentaje_detra" class="form-control text-uppercase"
                               name="porcentaje_detra">
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store">
                            <span class="text-store">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing" role="status"
                                aria-hidden="true"></span>
                            <span class="text-storing d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
