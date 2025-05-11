<div class="modal fade" id="modalConfirmTransfer" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmTransferTitle">Traslado de Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="invoice">
                        <div class="col-12 mb-3">
                            <div class="el-descriptions">
                                <div class="el-descriptions__header">
                                    <input type="hidden" name="idtransfer">
                                    <div class="el-descriptions__title"> NUMERO DE ORDEN: <span class="nro__orden"></span> </div>
                                    <div class="el-descriptions__extra"></div>
                                </div>
                                <div class="table-responsive-sm">
                                    <table class="table table-hover table-sm">
                                        <tbody>
                                            <tr class="el-descriptions-row">
                                                <th colspan="1" class="thead-transfer"> 
                                                    Fecha Emisi&oacute;n</th>
                                                <td colspan="3" class="td__emision"></td>
                                            </tr>
                                            <tr class="el-descriptions-row">
                                                <th colspan="1" class="thead-transfer"> 
                                                    Fecha Vencimiento</th>
                                                <td colspan="3" class="td__expiration"></td>
                                            </tr>
                                            <tr class="el-descriptions-row">
                                                <th colspan="1" class="thead-transfer"> 
                                                    Almac&eacute;n Despacho</th>
                                                <td colspan="1" class="td__dispatch"></td>
                                                <th colspan="1" class="thead-transfer"> 
                                                    Almac&eacute;n Receptor</th>
                                                <td colspan="1" class="td__receiver"></td>
                                            </tr>
                                            <tr class="el-descriptions-row">
                                                <th colspan="1" class="thead-transfer"> 
                                                    Informaci&oacute;n Adicional</th>
                                                <td colspan="3" class="td__optional"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3 mb-3">
                            <div class="table-responsive-sm">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-left" style="width: 100px;">Codigo</th>
                                            <th class="text-left">Producto</th>
                                            <th class="text-center" style="width: 100px;">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_detail_transfer"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Trasladar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status" aria-hidden="true"></span>
                            <span class="text-saving d-none">Trasladando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>