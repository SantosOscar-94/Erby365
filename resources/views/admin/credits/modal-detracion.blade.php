<div class="modal fade" id="modalDetraccion" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save_detraccion" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetraccionTitle">Registrar datos de detracción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Bienes y servicios sujetos a detracciones<span class="text-danger">
                                *</span></label>
                        <select class="form-control" name="servicio_detraccion" id="servicio_detraccion">
                            <option value="0">[SELECCIONE]</option>
                            @foreach (\App\Models\ListadoDetra::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->descripcion }} - {{ $item->porcentaje }}%</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Método pago - Detracción<span class="text-danger"> *</span></label>
                        <select class="form-control" name="metodo_pago">
                            <option value="1">Depósito en cuenta</option>
                            <option value="2">Giro</option>
                            <option value="3">Transferencia de fondos</option>
                            <option value="4">Orden de pago</option>
                            <option value="5">Tarjeta de débito</option>
                            <option value="6">Tarjeta de crédito emitida en el país por una empresa del sistema
                                financiero</option>
                            <option value="7">Cheques con la cláusula de "NO NEGOCIABLE", "INTRANSFERIBLES", "NO A
                                LA ORDEN" u otra equivalente</option>
                            <option value="8">Efectivo, por operaciones en las que no existe obligación de utilizar
                                medio de pago</option>
                            <option value="9">Efectivo, en los demás casos</option>
                            <option value="10">Medios de pago usados en comercio exterior</option>
                            <option value="22">Otros medios de pago</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">N° Cta Detracciones<span class="text-danger"> *</span></label>
                        <input type="text" readonly class="form-control" name="num_detracciones" value="{{\App\Models\Business::find(1)->cuenta_detraciones ?? "No disponible"}}">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">N° Constancia de pago - detracción</label>
                        <input type="text" class="form-control" name="num_constancia_pago">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Monto de la detracción<span class="text-danger"> *</span></label>
                        <input type="text" readonly class="form-control" name="monto_detraccion" id="monto_detraccion">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Imágen constancia</label>
                        <input type="file" class="form-control" name="imagen_constancia">
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Guardar</button>
                        <!-- <button type="submit" class="btn btn-primary">Guardar</button> -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
