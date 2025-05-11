<div class="modal fade" id="modalEditWarehouse" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditWarehouseTitle">Detalles del crédito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Factura</label>
                        <input type="text" id="invoice" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Moneda</label>
                        <input type="text" id="currency" class="form-control" disabled>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Vendedor</label>
                        <input type="text" id="seller" class="form-control text-uppercase" disabled>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Cliente</label>
                        <input type="text" id="client" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Total</label>
                        <input type="text" id="total" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Valor de Cuota</label>
                        <input type="text" id="installment-value" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Saldo Pendiente</label>
                        <input type="text" id="outstanding-balance" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Días de Retraso</label>
                        <input type="text" id="delay-days" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" id="issue-date" class="form-control" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Fecha de Vencimiento</label>
                        <input type="date" id="due-date" class="form-control" disabled>
                    </div>

                    <h5>Productos</h5><hr>
                    <div id="productList" class="row"></div>

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

<div class="modal fade" id="modalQuotes" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddWarehouseTitle">Pagos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row" id="basic-table">
                        <div class="col-12">
                            <div class="card">
                                <div class="p-3">
                                    <div class="table-responsive">
                                        <table id="tableQuotes" class="table table-sm">
                                            <thead class="table-light">
                                            <tr>
                                                <th width="8%">#</th>
                                                <th>Fecha de pago</th>
                                                <th>Método de pago</th>
                                                <th>Destino</th>
                                                <th>Monto</th>
                                                <th>¿Pago recibido?</th>
                                                <th>Imprimir</th>
                                                <th width="10%">Acciones</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <div class="col-4 float-end">
                                            <table class="table float-end">
                                                <tr>
                                                    <td colspan="6" class="text-end">
                                                        TOTAL PAGADO
                                                    </td>
                                                    <td class="text-end" id="payed">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;70.8
                                                    </td>
                                                </tr> <!---->
                                                <tr>
                                                    <td colspan="6" class="text-end">
                                                        TOTAL A PAGAR
                                                    </td>
                                                    <td class="text-end" id="total2">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;212.4
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end">
                                                        PENDIENTE DE PAGO
                                                    </td>
                                                    <td class="text-end" id="pending">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;141.6
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-add-quote">
                            Agregar Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddQuote" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Agregar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="account" class="form-label">Destino</label>
                        <select name="account" id="account" class="form-control">
                            @foreach(\App\Models\Cuentas::all() as $account)
                                <option value="{{ $account->id }}">{{ $account->nombre_ban }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="pay_method" class="form-label">Método de pago</label>
                        <select name="pay_method" id="pay_method" class="form-control">
                            <option value="1">Efectivo</option>
                            <option value="2">Tarjeta de crédito</option>
                            <option value="3">Tarjeta de débito</option>
                            <option value="4">Transferencia bancaria</option>
                            <option value="5">factura a 30 días</option>
                            <option value="6">Tarjeta de crédito visa</option>
                            <option value="7">Contado contraentrega</option>
                            <option value="8">Factura a 30 días</option>
                            <option value="9">Contado</option>
                            <option value="10">VENDEMAS</option>
                            <option value="11">YAPE</option>
                            <option value="12">PLIN</option>
                            <option value="13">Transferencia BCP SOLES</option>
                        </select>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="amount" class="form-label">Monto a pagar</label>
                        <input type="text" id="amount" class="form-control text-uppercase" name="amount">
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
