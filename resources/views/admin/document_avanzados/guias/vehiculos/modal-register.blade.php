<div class="modal fade" id="modalAddVehicle" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save_vehicle" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddClientTitle">Registrar Vehiculo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">


                    <div class="col-6 mb-3">
                        <label class="form-label" for="razon_social">Nro. de Placa</label>
                        <input type="text" class="form-control text-uppercase" name="razon_social">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">TUC<small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="telefono">Autorización de Placa principal<small class="text-danger" style="font-size: 12px;"></small></label>
                        <input type="text" class="form-control" name="telefono">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">Nro. de Placa secundaria <small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">T.U.C (placa secundaria)<small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">Autorización de Placa secundaria<small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">Modelo<small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="direccion">Marca<small class="text-danger d-none" id="opcional_direccion" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control text-uppercase" name="direccion">
                        <div class="invalid-feedback">El campo no debe estar vacío</div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save-client">
                            <span class="text-save-client">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving-client" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving-client d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
