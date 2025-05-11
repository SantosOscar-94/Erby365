<div class="modal fade" id="modalConfirmSale" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="modalConfirmSaleTitle">Procesar Pago</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-save-sale" method="POST">

                  @csrf
                  <div class="row">
                    <div class="col-xl-8 col-md-12 mb-3 mb-xl-0">
                        <!-- Choose Delivery -->
                        <p>Documento</p>
                        <div class="row mt-2">
                        <input type="hidden" name="iddocumento_tipo" value="2">
                          <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-icon position-relative checked">
                              <label class="form-check-label custom-option-content btn-type-document" for="boleta">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">BOLETA</span>
                                </span>
                                <input id="boleta" class="form-check-input" type="radio" value="2" name="type_document" checked="">
                              </label>
                            </div>
                          </div>
                          <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-icon position-relative">
                              <label class="form-check-label custom-option-content btn-type-document" for="factura">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">FACTURA</span>
                                </span>
                                <input id="factura"  class="form-check-input" type="radio" value="1" name="type_document">
                              </label>
                            </div>
                          </div>
                          <div class="col-md">
                            <div class="form-check custom-option custom-option-icon position-relative">
                              <label class="form-check-label custom-option-content btn-type-document" for="nota_venta">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">NOTA</span>
                                </span>
                                <input id="nota_venta" class="form-check-input" type="radio" value="7" name="type_document">
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-xl-4 col-md-12 mb-3 mb-xl-0">
                        <p>Serie</p>
                        <div class="bg-lighter rounded p-4 text-center">
                            <input type="hidden"name="serie_sale">
                            <h4 id="serie-sale" class="fw-medium mb-2"></h4>
                        </div>
                      </div>

                    <div class="col-12 mt-4 mb-4">
                        <div class="form-group">
                            <label class="form-label" for="dni_ruc">Cliente</label>
                            <small class="text-primary fw-bold btn-create-client" style="cursor: pointer">[+
                                Nuevo]</small>
                            <select class="select2-size-sm form-control" id="dni_ruc" name="dni_ruc">
                                <option id="user" selected></option>
                                <option></option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->dni_ruc . ' - ' . $client->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-sm-7 col-md-12 col-lg-7">
                      <div class="form-group"><label class="form-label">Forma de Pago</label>
                          <div class="row mb-3 shadow-payment">
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago" name="modo_pago">
                                                  <!-- @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach -->
                                                  <option value="1">Efectivo</option>
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row mb-3 shadow-payment">
                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago_2" name="modo_pago_2">
                                                   @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach
{{--                                                  <option value="2" selected>Yape</option>--}}
{{--                                                  <option value="3" selected>Plin</option>--}}
{{--                                                  <option value="5" selected>Tarjeta crédito</option>--}}
{{--                                                  <option value="7">Transferencia</option>--}}
{{--                                                  <option value="8">Deposito</option>--}}
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="referencia2" placeholder="# Referencia">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying_2" value="0">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="d-flex flex-row mb-3 shadow-payment" id="cuentasDiv2">
                              @foreach($cuentas as $cuenta)
                                  <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuenta->moneda}}">
                                    <input class="form-check-input" type="radio" name="cuentas2" id="cuenta{{$cuenta->id}}.2" value="{{$cuenta->nombre_ban}}">
                                    <label class="form-check-label" for="cuenta{{$cuenta->id}}">
                                        {{$cuenta->nombre_ban}}
                                    </label>
                                </div>
                              @endforeach
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas2" id="cuenta1.2" value="{{$cuentas->nombre_ban}}">--}}
{{--                                <label class="form-check-label" for="cuenta1.2">--}}
{{--                                    {{$cuentas->nombre_ban}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda1}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas2" id="cuenta2.2" value="{{$cuentas->nombre_ban1}}">--}}
{{--                                <label class="form-check-label" for="cuenta2.2">--}}
{{--                                    {{$cuentas->nombre_ban1}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda2}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas2" id="cuenta3.2" value="{{$cuentas->nombre_ban2}}">--}}
{{--                                <label class="form-check-label" for="cuenta3.2">--}}
{{--                                    {{$cuentas->nombre_ban2}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda3}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas2" id="cuenta4.2" value="{{$cuentas->nombre_ban3}}">--}}
{{--                                <label class="form-check-label" for="cuenta4.2">--}}
{{--                                    {{$cuentas->nombre_ban3}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
                          </div>

                          <div class="row mb-3 shadow-payment">
                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago_3" name="modo_pago_3">
                                                  @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach
{{--                                                  <option value="7">Transferencia</option>--}}
{{--                                                  <option value="8">Deposito</option>--}}
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="referencia" placeholder="# Referencia">
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-4">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying_3" value="0">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="d-flex flex-row mb-3 shadow-payment" id="cuentasDiv">
                              @foreach($cuentas as $cuenta)
                                  <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuenta->moneda}}">
                                      <input class="form-check-input" type="radio" name="cuentas" id="cuenta{{$cuenta->id}}" value="{{$cuenta->nombre_ban}}">
                                      <label class="form-check-label" for="cuenta{{$cuenta->id}}">
                                          {{$cuenta->nombre_ban}}
                                      </label>
                                  </div>
                              @endforeach
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas" id="cuenta1" value="{{$cuentas->nombre_ban}}">--}}
{{--                                <label class="form-check-label" for="cuenta1">--}}
{{--                                    {{$cuentas->nombre_ban}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda1}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas" id="cuenta2" value="{{$cuentas->nombre_ban1}}">--}}
{{--                                <label class="form-check-label" for="cuenta2">--}}
{{--                                    {{$cuentas->nombre_ban1}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda2}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas" id="cuenta3" value="{{$cuentas->nombre_ban2}}">--}}
{{--                                <label class="form-check-label" for="cuenta3">--}}
{{--                                    {{$cuentas->nombre_ban2}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline cuenta-item" data-moneda="{{$cuentas->moneda3}}">--}}
{{--                                <input class="form-check-input" type="radio" name="cuentas" id="cuenta4" value="{{$cuentas->nombre_ban3}}">--}}
{{--                                <label class="form-check-label" for="cuenta4">--}}
{{--                                    {{$cuentas->nombre_ban3}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
                          </div>
                      </div>
                  </div>

                  <div class="col-12 col-sm-5 col-md-12 col-lg-5">
                      <div class="form-group text-end h-100"><label
                              class="control-label fw-bold">&nbsp;</label>
                          <div class="card shadow-none mb-0" style="height: calc(100% - 35px);">
                              <div id="card-body-totals" class="card-body d-flex align-items-end">
                                  <div class="w-100" style="margin-bottom: 10px">
                                      <div class="row">
                                          <div class="col-6 pr-sm-0"><span
                                                  class="text-primary fw-bold">Total:</span></div>
                                          <div class="col-6 pl-sm-0"><span class="fw-bold"><span name="moneda">S/ </span><span id="total_pay"></span></span>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-6 pr-sm-0"><span
                                                  class="text-info fw-bold">Pagando:</span></div>
                                          <div class="col-6 pl-sm-0"><span class="fw-bold"><span name="moneda">S/ </span><span id="total_paying"></span></span>
                                          </div>
                                      </div>
                                      <hr class="my-1 line-total">
                                      <div class="row">
                                          <div class="col-6 pr-sm-0">
                                              <span class="fw-bold text-success wrapper_difference">Diferencia:</span>
                                          </div>
                                          <div class="col-6 pl-sm-0">
                                              <span class="fw-bold text-success wrapper_difference"><span name="moneda">S/ </span><span id="difference"></span></span>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-6 pr-sm-0">
                                              <span class="fw-bold text-success wrapper_vuelto">Vuelto:</span>
                                          </div>
                                          <div class="col-6 pl-sm-0">
                                              <span class="fw-bold text-success wrapper_vuelto"><span name="moneda">S/ </span><span id="vuelto"></span></span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                    <div class="col-12 mt-3 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary btn-confirm-pay">
                            <span class="text-confirm-pay">Generar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-confirm-payment" role="status"
                                aria-hidden="true"></span>
                            <span class="text-confirm-payment d-none">Generando...</span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCaja" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-xl modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Arqueo de Cajas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form-cashes" method="POST" class="form form-vertical"
                    action="#" target="_blank">
                        <div class="row">
                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_inicial">Fecha Inicial</label>
                                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial"
                                    value="{{ date('Y-m-01') }}">
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_final">Fecha Final</label>
                                <input type="date" class="form-control" id="fecha_final" name="fecha_final"
                                    value="{{ date('Y-m-t') }}">
                            </div>

                            @php
                                $user = App\Models\User::with('roles')->where('id', auth()->user()['id'])->first();
                                $role = $user->roles->first();
                            @endphp

                            @if ($role->name == "SUPERADMIN" || $role->name == "ADMIN")
                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label" for="user">Vendedor</label>
                                    <select class="select2-size-sm form-control" id="user" name="user">
                                        <option value="0">TODOS</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-5 mb-3">
                                    <label class="form-label" for="warehouse">Tienda</label>
                                    <select class="form-control" id="warehouse" name="warehouse">
                                        <option value="0">TODOS</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">
                                                {{ $warehouse->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="mt-3">
                                <div class="col-12 d-flex justify-content-between">
                                    <button id="wrapper-input-reniec" class="btn btn-info btn-search" type="button"
                                        id="button-addon2" onclick="load_datatable()">
                                        <span class="text-search">
                                            <i class="ti ti-search" style="font-size: 15px; margin-bottom: 2px;"></i>
                                            <span class="input-text-reniec"> Buscar</span>
                                        </span>

                                        <span class="spinner-border spinner-border-sm text-searching d-none"
                                            role="status" aria-hidden="true"></span>
                                        <span class="ml-25 align-middle text-searching d-none" style="font-size: 14px;">
                                            <span style="margin-left: 3px;">Buscando...</span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" id="basic-table">
                    <div class="col-12">
                        <div class="card">
                            <div class="p-3">
                                <div class="table-responsive">
                                    <table id="table" class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                            <th width="8%">#</th>
                                            <th>Fecha de apertura</th>
                                            <th>Fecha de cierre</th>
                                            <th>Vendedor</th>
                                            <th>Tienda</th>
                                            <th>Monto apertura</th>
                                            <th>Monto cierre</th>
                                            <th>Productos vendidos</th>
                                            <th>Documentos emitidos</th>
                                            <th>Estado</th>
                                            <th width="10%">Acciones</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.arching_cashes.modals')
