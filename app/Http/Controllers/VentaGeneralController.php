<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Cuentas;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\DetailQuote;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\ListadoDetra;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Serie;
use App\Models\StockProduct;
use App\Models\SunatDetraccion;
use App\Models\TypeDocument;
use App\Models\Unit;
use App\Models\User;
use App\Models\VentaGeneral;
use App\Models\DetailVentaGeneral;
use App\Models\VentaContado;
use App\Models\VentaCreditoCuota;
use App\Models\Detraccion;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VentaGeneralController extends Controller
{
    public function index()
    {
        return view('admin.credits.list');
    }

    public function get()
    {
        $ventas_generales     = VentaGeneral::select(
            'ventas_generales.*',
            'clients.dni_ruc as dni_ruc',
            'clients.nombres as cliente',
            'type_documents.descripcion as tipo_documento',
            'currencies.codigo as moneda'
        )
            ->join('clients', 'ventas_generales.idcliente', '=', 'clients.id')
            ->join('type_documents', 'ventas_generales.idtipo_comprobante', 'type_documents.id')
            ->join('currencies', 'ventas_generales.idmoneda', '=', 'currencies.id')
            ->where('idtipo_comprobante', '!=', 6)
            ->orderBy('id', 'DESC')
            ->get();

        return Datatables()
            ->of($ventas_generales)
            ->addColumn('cliente', function ($ventas_generales) {
                $cliente  = $ventas_generales->cliente;
                return $cliente;
            })
            ->addColumn('documento', function ($ventas_generales) {
                $documento  = $ventas_generales->serie . '-' . $ventas_generales->correlativo;
                return $documento;
            })
            ->addColumn('fecha_de_emision', function ($ventas_generales) {
                $fecha_emision = date('d-m-Y', strtotime($ventas_generales->fecha_emision));
                return $fecha_emision;
            })->addColumn('fecha_de_vencimiento', function ($ventas_generales) {
                $fecha_emision = date('d-m-Y', strtotime($ventas_generales->fecha_emision));
                return $fecha_emision;
            })
            ->addColumn('xml', function ($ventas_generales) {
                $cdr            = $ventas_generales->cdr;
                $type_document  = TypeDocument::where('id', $ventas_generales->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $url_api        = $business->url_api;
                $ruc            = $business->ruc;
                $name_file      = $ruc . '-' . $type_document . '-' . $ventas_generales->serie . '-' . $ventas_generales->correlativo . '.xml';
                $btn            = '';
                switch ($cdr) {
                    case '0':
                        $btn = '<span class="badge bg-secondary text-white">Sin registro</span>';
                        break;

                    case '1':
                        $btn = '<a target="_blank" href="' . $url_api . 'Xml/xml-firmados/' . $name_file . '" class="text-center text-primary"><i class="far fa-file-code"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('cdr', function ($ventas_generales) {
                $cdr            = $ventas_generales->cdr;
                $type_document  = TypeDocument::where('id', $ventas_generales->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $ruc            = $business->ruc;
                $url_api        = $business->url_api;
                $btn            = '';
                $name_file      = 'R-' . $ruc . '-' . $type_document . '-' . $ventas_generales->serie . '-' . $ventas_generales->correlativo . '.xml';
                switch ($cdr) {
                    case '0':
                        $btn .= '<span class="badge bg-secondary text-white">Sin registro</span>';
                        break;

                    case '1':
                        $btn .= '<a target="_blank" href="' . $url_api . 'Cdr/' . $name_file . '" class="text-center text-primary"><i class="fas fa-file-alt"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('estado_cpe', function ($ventas_generales) {
                $estado_cpe    = $ventas_generales->estado;
                $btn           = '';
                switch ($estado_cpe) {
                    case '0':
                        $btn .= '<span class="badge text-white" style="background-color: rgb(108, 117, 125);">Registrado</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Pagado</span>';
                        break;

                    case '2':
                        $btn .= '<span class="badge bg-danger text-white">Anulado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('acciones', function ($ventas_generales) {
                $id     = $ventas_generales->id;
                $idtipo_comprobante = $ventas_generales->idtipo_comprobante;
                $btn    = '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                            <div class="dropdown-menu">
                                        <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download mr-50 menu-icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <span>Descargar PDF A4</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-idtipo_comprobante="' . $idtipo_comprobante . '" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file mr-50 menu-icon"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                            <span>Cuenta Detracción</span>
                                        </a>
                                        <a class="dropdown-item btn-open-whatsapp" data-id="' . $id . '" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem;"></i>
                                            <span> Enviar Documento</span>
                                        </a>
                        </div>
                    </div>';
                return $btn;
            })
            ->rawColumns(['proveedor', 'documento', 'fecha_de_emision', 'estado_compra','xml', 'cdr', 'acciones', 'estado_cpe'])
            ->make(true);
    }

    public function create()
    {
        $data['clients']            = Client::where('iddoc', 4)->get();
        $data['type_documents_p']   = TypeDocument::where('estado', 1)->limit(2)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['modo_pagos']         = PayMode::get();
        $data['products']           = Product::get();
        $data["warehouses"]         = Warehouse::get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
//        $user                       = Auth::user();
//        $data['series']             = Serie::where('idcaja', $user->idcaja)->get();

        return view('admin.credits.create', $data);
    }

    // public function edit($id)
    // {
    //     $data["type_documents_p"]   = TypeDocument::where('estado', 1)->limit(2)->get();
    //     $data["quote"]              = Quote::where('id', $id)->first();
    //     $data["client"]             = Client::where('id', $data["quote"]->idcliente)->first();
    //     $data["clients"]            = Client::where('iddoc', $data["client"]->iddoc)->get();
    //     $data["type_documents"]     = IdentityDocumentType::where('estado', 1)->get();
    //     $data['products']           = Product::get();
    //     $data["modo_pagos"]         = PayMode::get();
    //     $data["warehouses"]         = Warehouse::get();
    //     $data["units"]              = Unit::where('estado', 1)->get();
    //     $data["type_inafects"]      = IgvTypeAffection::where('estado', 1)->get();
    //     $data["detalle"]            = DetailQuote::select(
    //         'detail_quotes.*',
    //         'products.descripcion as producto',
    //         'products.codigo_interno as codigo_interno',
    //         'units.codigo as unidad',
    //         'products.idcodigo_igv as idcodigo_igv',
    //         'igv_type_affections.codigo as codigo_igv',
    //         'products.igv as igv',
    //         'products.opcion as opcion',
    //         'products.impuesto as impuesto'
    //     )
    //         ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
    //         ->join('units', 'products.idunidad', '=', 'units.id')
    //         ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
    //         ->where('detail_quotes.idcotizacion', $id)
    //         ->get();
    //     return view('admin.quotes.edit', $data);
    // }

    public function get_product_update(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
        $idalmacen          = (int) $request->input('idalmacen');
        $stock              = StockProduct::where('idproducto', $id)->where('idalmacen', $idalmacen)->first();
        if ((int) $stock->cantidad <= 0) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'type'      => 'warning'
            ]);
            return;
        }
        $producto        = Product::select(
            'products.id',
            'products.precio_venta',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad',
            'products.idcodigo_igv as idcodigo_igv',
            'igv_type_affections.codigo as codigo_igv',
            'products.igv as igv',
            'products.opcion as opcion',
            'products.impuesto as impuesto',
            'stock_products.idalmacen as idalmacen'
        )
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->join('stock_products', 'products.id', 'stock_products.idproducto')
            ->join('warehouses', 'stock_products.idalmacen', 'warehouses.id')
            ->join('users', 'warehouses.id', 'users.idalmacen')
            ->where('products.id', $id)
            ->where('warehouses.id', $idalmacen)
            ->first();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success',
            'producto'  => $producto,
            'cantidad'  => $cantidad
        ]);
    }

    public function store_product_update(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = (int) $request->input('id');
        $idalmacen          = (int) $request->input('idalmacen');
        $producto        = Product::select(
            'products.id',
            'products.precio_venta',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad',
            'products.idcodigo_igv as idcodigo_igv',
            'igv_type_affections.codigo as codigo_igv',
            'products.igv as igv',
            'products.opcion as opcion',
            'products.impuesto as impuesto',
            'stock_products.idalmacen as idalmacen'
        )
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->join('stock_products', 'products.id', 'stock_products.idproducto')
            ->join('warehouses', 'stock_products.idalmacen', 'warehouses.id')
            ->join('users', 'warehouses.id', 'users.idalmacen')
            ->where('products.id', $id)
            ->where('warehouses.id', $idalmacen)
            ->first();

        $cantidad           = (int) $request->input('cantidad');
        $precio             = number_format($request->input('precio'), 2, ".", "");
        echo json_encode([
            'status'    => true,
            'cantidad'  => $cantidad,
            'id'        => $id,
            'precio'    => $precio,
            'producto'  => $producto,
            'msg'       => 'Actualizado correctamente',
            'type'      => 'success'
        ]);
    }

    public function gen_update(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idquote                = (int) $request->input('idquote');
        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $idcliente              = $request->input('idcliente');
        $tipo_cambio            = $request->input('tipo_cambio');
        $modo_pago              = $request->input('modo_pago');
        $observaciones          = trim($request->input('observaciones'));
        $products               = json_decode($request->post('productos'));
        $totales                = json_decode($request->post('totales'));
        if (empty($products)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        $registros                  = DetailQuote::where('idcotizacion', $idquote)->get();
        $existingIdentifiers        = $registros->pluck('idproducto')->toArray();
        $existingIdalmacenes        = $registros->pluck('idalmacen')->toArray();
        $array_ids                  = [];
        $array_precio               = [];
        $array_cantidad             = [];
        foreach ($products as $producto) {
            $array_ids[]            = $producto->idproducto;
            $array_precio[]         = $producto->precio;
            $array_cantidad[]       = $producto->cantidad;
            $array_idalmacen[]      = $producto->idalmacen;
        }

        foreach ($existingIdentifiers as $i => $id_db) {
            if (in_array($id_db, $array_ids)) {
                foreach ($products as $producto) {
                    DetailQuote::updateOrCreate([
                        'idcotizacion'      => $idquote,
                        'idproducto'        => $producto->idproducto,
                        'idalmacen'         => $producto->idalmacen
                    ], [
                        'idcotizacion'      => $idquote,
                        'idproducto'        => $producto->idproducto,
                        'cantidad'          => $producto->cantidad,
                        'precio_unitario'   => $producto->precio,
                        'precio_total'      => ($producto->precio * $producto->cantidad),
                        'idalmacen'         => $producto->idalmacen
                    ]);
                }
            } else {
                DetailQuote::where([
                    'idcotizacion'      => $idquote,
                    'idproducto'        => $id_db,
                    'idalmacen'         => $existingIdalmacenes[$i]
                ])->delete();
            }
        }

        Quote::where('id', $idquote)->update([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $idcliente,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $modo_pago,
            'exonerada'             => $totales->exonerada,
            'inafecta'              => $totales->inafecta,
            'gravada'               => $totales->gravada,

            'anticipo'              => "0.00",
            'igv'                   => $totales->igv,
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $totales->total,
            'observaciones'         => mb_strtoupper($observaciones),
            'estado'                => 1,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => Auth::user()['idcaja'],
        ]);

        $id                         = $idquote;
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select(
            'detail_quotes.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_quotes.idcotizacion', $id)
            ->get();

        $this->gen_pdf($data, $data["name_quote"]);
        echo json_encode([
            'status'                => true,
            'pdf'                   => $data["name_quote"] . '.pdf',
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idcotizacion'          => $idquote
        ]);
    }

    public function get_serie(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtipo_documento = $request->input('idtipo_documento');
        if (empty($idtipo_documento)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un tipo de comprobante',
                'type'      => 'warning'
            ]);
            return;
        }

        $type_document                      = TypeDocument::where('id', $idtipo_documento)->first();
        $serie                              = Serie::where('idtipo_documento', $type_document->id)->first();
        if ($type_document->id != 1)
            $clients                        = Client::where('iddoc', 1)->orWhere('iddoc', 2)->orderBy('id', 'DESC')->get();
        else
            $clients                        = Client::where('iddoc', 4)->orderBy('id', 'DESC')->get();

        echo json_encode(['status'  => true, 'serie'  => $serie, 'clients' => $clients]);
    }

    public function get_clients(Request $request)
    {
        $clientes                       = Client::orderBy('id', 'DESC')->get();
        return $clientes;
    }

    public function load_cart(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $cart           = $this->create_cart();
        $html_cart      = '';
        $html_totales   = '';
        $contador       = 0;

        if (!empty($cart['products'])) {
            foreach ($cart['products'] as $i => $product) {
                $contador   = $contador + 1;
                $html_cart .= '<tr>
                                <td class="text-center">' . $contador . '</td>
                                <td>' . $product["descripcion"] . '</td>
                                <td class="text-center">' . $product["unidad"] . '</td>
                                <td class="text-right">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text btn-down" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-minus me-sm-1"></i></span>
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '">
                                        <span class="input-group-text btn-up" style="cursor: pointer;" data-id="' . $product["id"] . '" data-cantidad="' . $product["cantidad"] . '" data-precio="' . $product["precio_venta"] . '"><i class="ti ti-plus me-sm-1"></i></span>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_venta"], 2, ".", "") . '" data-cantidad="' . $product["cantidad"] . '" data-id="' . $product["id"] . '" name="precio"></td>
                                <td class="text-center">' . number_format(($product["precio_venta"] * $product["cantidad"]), 2, ".", "") . '</td>
                                <td class="text-center"><span data-id="' . $product["id"] . '" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                            </tr>';
            }
        }

        $html_totales   .= '<div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">OP. Gravadas:</span>
                                    <span class="fw-medium">S/' . number_format(($cart['exonerada'] + $cart['gravada'] + $cart['inafecta']), 2, ".", "") . '</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">IGV:</span>
                                    <span class="fw-medium">S/' . number_format($cart['igv'], 2, ".", "") . '</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <span class="w-px-100">
                                        <input type="checkbox" id="otros-cargos"> Otros cargos:
                                    </span>
                                    <span class="fw-medium d-flex align-items-center input-container" style="width: 30%;">
                                        $/<input type="number" class="form-control priceInput ms-1" step="1" min="0" value="0.00" style="display: none">
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">Condición de pago: </span>
                                    <span class="fw-medium">
                                        <select class="form-control" name="condicion_pago" id="condicion-pago">
                                            <option value="1">Contado</option>
                                            <option value="2">Crédito</option>
                                            <option value="3">Crédito con cuotas</option>
                                        </select>
                                    </span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="w-px-100">Total:</span>
                                    <span class="fw-medium">S/' . number_format($cart['total'], 2, ".", "") . '</span>
                            </div>';

        if (!empty($request->input('detraccion'))){
            $detraccion = $cart['total'] * (ListadoDetra::find($request->input('detraccion'))->porcentaje /100 ?? 0) ;
        }else{
            $detraccion = 0;
        }

        echo json_encode([
            'status'        => true,
            'cart_products' => $cart,
            'html_cart'     => $html_cart,
            'html_totales'  => $html_totales,
            'detraccion'    => number_format($detraccion, 2, ".", ""),
            'total_card'    => number_format($cart['total'], 2, ".", "")
        ]);
    }

    public function add_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");
        $idalmacen      = (int) $request->input('idalmacen');

        if (!$this->add_product_cart($id, $cantidad, $precio, $opcion, $idalmacen)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success'
        ]);
    }

    public function delete_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        if (!$this->delete_product_cart($id, $opcion)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se pudo eliminar el producto',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success'
        ]);
    }

    public function store_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        $cantidad       = (int) $request->input('cantidad');
        $precio         = number_format($request->input('precio'), 2, ".", "");
        if (!$this->update_quantity($id, $cantidad, $precio, $opcion)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Actualizado correctamente',
            'type'      => 'success'
        ]);
    }

    public function save(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $idcliente              = $request->input('dni_ruc');
        $tipo_cambio            = $request->input('tipo_cambio');
        $cart                   = $this->create_cart();
        //$serie                  = $request->input('serie');
        $condicion_pago         = $request->input('condicion_pago');
        $tipo_operacion         = $request->input('tipo_operacion');
        $ultimo_correlativo     = NULL;

        //dd($request->input('servicio_detraccion') );

        if ($tipo_operacion == 4 && $cart["total"] < 700){
            echo json_encode([
                'status'    => false,
                'msg'       => 'El valor mínimo para detracciones es de 700 soles',
                'type'      => 'warning'
            ]);
            return;
        }

        if (empty($idcliente)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe seleccionar el cliente',
                'type'      => 'warning'
            ]);
            return;
        }

        if (empty($cart['products'])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        if (VentaGeneral::count() == 0){
            $ultima_serie                       = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
            $correlativo                        = str_pad(1, 8, '0', STR_PAD_LEFT);
            $serie                              = $ultima_serie->serie;
        }else{
            $ultima_serie                       = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
            $ultimo_correlativo                 = (int) $ultima_serie->correlativo + 1;
            $serie                              = $ultima_serie->serie;
            $correlativo                        = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        }

        $business                   = Business::where('id', 1)->first();
        $type_document              = TypeDocument::where('id', $idtipo_comprobante)->first();
        $client                     = Client::where('id', $idcliente)->first();
        $identity_document          = IdentityDocumentType::where('id', $client->iddoc)->first();
        $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($cart["igv"], 2, ".", "") . ' | ' . number_format($cart["total"], 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
        $name_qr                    = $serie . '-' . $correlativo;

        // Gen Qr
        QrCode::format('png')
            ->size(140)
            ->generate($qr, 'files/ventas-generales/qr/' . $name_qr . '.png');

        VentaGeneral::create([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'correlativo'           => $correlativo,
            'serie'                 => $serie,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'tipo_operacion'        => $tipo_operacion,
            'idcliente'             => $idcliente,
            'idmoneda'              => 1,
            'tipo_cambio'                  => $tipo_cambio,
            'exonerada'             => $cart["exonerada"],
            'inafecta'              => $cart["inafecta"],
            'gravada'               => $cart["gravada"],
            'anticipo'              => "0.00",
            'igv'                   => $cart["igv"],
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $cart["total"],
            'cdr'                   => 0,
            'estado'                => 1,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => Auth::user()['idcaja'],
            'condicion_pago'        => $condicion_pago,
        ]);

        $idventa                    = VentaGeneral::latest('id')->first()['id'];

        foreach ($cart["products"] as $product) {
            DetailVentaGeneral::create([
                'idventa_general'      => $idventa,
                'idproducto'        => $product['id'],
                'cantidad'          => $product['cantidad'],
                'precio_unitario'   => $product['precio_venta'],
                'precio_total'      => ($product['precio_venta'] * $product['cantidad']),
                'idalmacen'         => $product['idalmacen']
            ]);
        }

        if($tipo_operacion == '4'){

            $path = null;
            if ($request->hasFile('imagen_constancia')) {
                $image = $request->file('imagen_constancia');
                $imageName = $idventa . '.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('detracciones'), $imageName);
            }

            Detraccion::create([
                'idventa_general'           => $idventa,
                'idproducto'                => $cart["products"][0]['id'],
                'num_detracciones'          => $request->input('num_detracciones')??0,
                'num_constancia_pago'       => $request->input('num_constancia_pago'),
                'iddetraccion'              => $request->input('servicio_detraccion')??0,
                'metodo_pago'               => $request->input('metodo_pago'),
                'monto_detraccion'          => $request->input('monto_detraccion')??0,
                'path_imagen_constancia'    => $path??0
            ]);

        }

        switch ($condicion_pago) {
            case '1':
                VentaContado::create([
                    'idventa_general' => $idventa,
                    'metodo_pago'       => $request->input('metodo_pago'),
                    'destino'           => $request->input('destino'),
                    'glosa'             => $request->input('glosa'),
                    'monto'             => $request->input('monto')
                ]);
                break;
            case '3':
                VentaCreditoCuota::create([
                    'idventa_general'      => $idventa,
                    'numero_cuotas'          => $request->input('numero_cuotas'),
                    'valor_cuotas'           => $request->input('valor_cuotas'),
                    'valor_cuota_inicial'    => $request->input('valor_cuota_inicial'),
                ]);
        }

        $ultima_serie                       = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo                 = (int) $ultima_serie->correlativo + 1;
        $nuevo_correlativo                  = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo
        ]);

        $this->destroy_cart();

        $data["venta_general"]               = VentaGeneral::where('id', $idventa)->first();
        $data["client"]                      = Client::where('id', $data["venta_general"]["idcliente"])->first();
        $name                                = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["venta_general"]["serie"]) . '-' . $data["venta_general"]["correlativo"];
        $empresa = Business::where('id', 1)->first();

        $this->gen_ticket_b($idventa, $name, $name_qr);

        echo json_encode([
            'status'                => true,
            'pdf'                   => $name . '.pdf',
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idventa'               => $idventa,
            'empresa'               => $empresa
        ]);
    }

    public function print_venta_general(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }


        $id                         = $request->input('id');
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select(
            'detail_quotes.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_quotes.idcotizacion', $id)
            ->get();

        $this->gen_pdf($data, $data["name_quote"]);
        echo json_encode([
            'status'    => true,
            'pdf'       => $data["name_quote"] . '.pdf'
        ]);
    }

    public function download($id)
    {
        $data['cuentas']            = Cuentas::latest()->first();
        $data["quote"]              = Quote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["quote"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name_quote"]         = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["quote"]["serie"]) . '-' . $data["quote"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["quote"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["quote"]->total, 2);
        $data["detail"]             = DetailQuote::select(
            'detail_quotes.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_quotes.idcotizacion', $id)
            ->get();

        $pdf    = PDF::loadView('admin.quotes.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download($data["name_quote"] . '.pdf');
    }

    public function gen_voucher(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                     = $request->input('id');
        $quote                  = Quote::where('id', $id)->first();
        $detalle                = DetailQuote::select(
            'detail_quotes.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad',
            'products.idcodigo_igv as idcodigo_igv',
            'products.igv as igv',
            'products.opcion as opcion'
        )
            ->join('products', 'detail_quotes.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->where('detail_quotes.idcotizacion', $id)
            ->get();

        $client                 = Client::where('id', $quote->idcliente)->first();
        $idtipo_comprobante     = $request->input('idtipo_comprobante');
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $id_arching             = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];

        // Save
        $business                   = Business::where('id', 1)->first();
        $type_document              = TypeDocument::where('id', $idtipo_comprobante)->first();
        $client                     = Client::where('id', $client->id)->first();
        $identity_document          = IdentityDocumentType::where('id', $client->iddoc)->first();

        $ultima_serie               = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo         = (int) $ultima_serie->correlativo;
        $serie                      = $ultima_serie->serie;
        $correlativo                = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        $empresa                     = Business::where('id', 1)->first();

        $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($quote->igv, 2, ".", "") . ' | ' . number_format($quote->total, 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
        $name_qr                    = $serie . '-' . $correlativo;

        // Gen Qr
        QrCode::format('png')
            ->size(140)
            ->generate($qr, 'files/ventas-generales/qr/' . $name_qr . '.png');

        Billing::create([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'serie'                 => $serie,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $client->id,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $quote->modo_pago,
            'exonerada'             => $quote->exonerada,
            'observaciones'         => $quote->observaciones,
            'inafecta'              => $quote->inafecta,
            'gravada'               => $quote->gravada,
            'anticipo'              => "0.00",
            'igv'                   => $quote->igv,
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $quote->total,
            'cdr'                   => 0,
            'anulado'               => 0,
            'id_tipo_nota_credito'  => null,
            'estado_cpe'            => 0,
            'errores'               => null,
            'nticket'               => null,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => $id_arching,
            'vuelto'                => "0.00",
            'qr'                    => $name_qr . '.png'
        ]);
        $idfactura                  = Billing::latest('id')->first()['id'];
        DetailPayment::create([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idfactura'             => $idfactura,
            'idpago'                => $quote->modo_pago,
            'monto'                 => $quote->total,
            'idcaja'                => $id_arching
        ]);

        foreach ($detalle as $product) {
            DetailBilling::create([
                'idfacturacion'         => $idfactura,
                'idproducto'            => $product['idproducto'],
                'cantidad'              => $product['cantidad'],
                'descuento'             => 0.0000000000,
                'igv'                   => $product["igv"],
                'id_afectacion_igv'     => $product['idcodigo_igv'],
                'precio_unitario'       => $product['precio_unitario'],
                'precio_total'          => ($product['precio_unitario'] * $product['cantidad'])
            ]);

            if ($product["opcion"] == 1) {
                StockProduct::where('idproducto', $product["id"])
                    ->where('idalmacen', $product["idalmacen"])
                    ->update([
                        'cantidad'  => $product["stock"] - $product["cantidad"]
                    ]);
            }
        }

        $factura                = Billing::where('id', $idfactura)->first();
        $ruc                    = Business::where('id', 1)->first()->ruc;
        $code_sale              = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $name_sale              = $ruc . '-' . $code_sale . '-' . $factura->serie . '-' . $factura->correlativo;
        $id_sale                = $idfactura;
        $this->gen_ticket_b($idfactura, $name_sale);

        $ultima_serie_sale      = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo_sale = (int) $ultima_serie_sale->correlativo + 1;
        $nuevo_correlativo_sale = str_pad($ultimo_correlativo_sale, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo_sale
        ]);

        echo json_encode([
            'status'        => true,
            'id'            => $id_sale,
            'pdf'           => $name_sale . '.pdf',
            'type_document' => $idtipo_comprobante,
        ]);
    }

    public function gen_pdf($data, $name)
    {
        $data['cuentas']            = Cuentas::latest()->first();
        $pdf    = PDF::loadView('admin.credits.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->save(public_path('files/credits/' . $name . '.pdf'));
    }

    public function test()
    {
        $pdf    = PDF::loadView('admin.quotes.test')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function create_cart()
    {
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            $quote =
                [
                    'quote' =>
                    [
                        'products'     => [],
                        'igv'          => 0,
                        'exonerada'    => 0,
                        'gravada'      => 0,
                        'inafecta'     => 0,
                        'subtotal'     => 0,
                        'total'        => 0
                    ]
                ];

            session($quote);
            return session()->get('quote');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('quote')['products'] as $index => $product) {
            if ($product['impuesto'] == 1) {
                $igv        +=  number_format((((float) $product['precio_venta'] - (float) $product['precio_venta'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $igv        = $this->redondeado($igv);
            }

            if ($product["codigo_igv"] == "10") {
                $gravada    += number_format((((float) $product['precio_venta'] / 1.18) * (int) $product['cantidad']), 2, ".", "");
                $gravada     = $this->redondeado($gravada);
            }

            if ($product["codigo_igv"] == "20") {
                $exonerada   += number_format(((float) $product['precio_venta'] * (int) $product['cantidad']), 2, ".", "");
                $exonerada   = $this->redondeado($exonerada);
            }

            if ($product["codigo_igv"] == "30") {
                $inafecta    += number_format(((float) $product['precio_venta'] * (int) $product['cantidad']), 2, ".", "");
                $inafecta     = str_replace(',', '', $inafecta);
                $inafecta     = $this->redondeado($inafecta);
            }

            $subtotal      = $exonerada + $gravada + $inafecta;
            session()->put('quote.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;
        $quote =
            [
                'quote' =>
                [
                    'products'     => session('quote')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($quote);
        return session()->get('quote');
    }

    public function add_product_cart($id, $cantidad, $precio, $opcion, $idalmacen)
    {
        $product        = Product::select(
            'products.*',
            'units.codigo as unidad',
            'igv_type_affections.descripcion as tipo_afecto',
            'igv_type_affections.codigo as codigo_igv',
            'stock_products.cantidad as stock',
            'stock_products.idalmacen as idalmacen'
        )
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->join('stock_products', 'products.id', 'stock_products.idproducto')
            ->join('warehouses', 'stock_products.idalmacen', 'warehouses.id')
            ->join('users', 'warehouses.id', 'users.idalmacen')
            ->where('products.id', $id)
            ->where('warehouses.id', $idalmacen)
            ->first();

        if (!$product)
            return false;

        if ($opcion == 1) {
            if ($product->stock < $cantidad) {
                return false;
            } elseif ($product->stock == 0) {
                return false;
            }
        }

        $new_product    =
            [
                'id'                => $product->id,
                'codigo_sunat'      => $product->codigo_sunat,
                'descripcion'       => $product->descripcion,
                'idunidad'          => $product->idunidad,
                'unidad'            => $product->unidad,
                'idcodigo_igv'      => $product->idcodigo_igv,
                'codigo_igv'        => $product->codigo_igv,
                'igv'               => $product->igv,
                'precio_compra'     => $product->precio_compra,
                'precio_venta'      => $precio,
                'impuesto'          => $product->impuesto,
                'stock'             => ($opcion == 1) ? $product->stock : null,
                'opcion'            => $opcion,
                'cantidad'          => $cantidad,
                'idalmacen'         => ($opcion == 1) ? $idalmacen : null,
            ];

        if (empty(session()->get('quote')['products'])) {
            session()->push('quote.products', $new_product);
            return true;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if ($opcion == 1) {
                    if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                        return false;
                    }
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('quote.products.' . $index, $product);
                return true;
            }
        }

        session()->push('quote.products', $new_product);

        return true;
    }

    public function delete_product_cart($id, $opcion)
    {
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            return false;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                session()->forget('quote.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad, $precio, $opcion)
    {
        if (empty(session()->get('quote')['products'])) {
            return false;
        }

        foreach (session()->get('quote')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if ($product["stock"] != NULL) {
                    if ($product["stock"] < $cantidad) {
                        return false;
                    } elseif ($product["stock"] == 0) {
                        return false;
                    }
                }
                $product['cantidad']           =  $cantidad;
                $product['precio_venta']       =  $precio;
                session()->put('quote.products.' . $index, $product);
                return true;
            }
        }
    }

    public function get_price_product(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        $id         = $request->input('id');
        $product    = Product::where('id', $id)->first();
        echo json_encode([
            'status'    => true,
            'product'   => $product
        ]);
    }

    public function destroy_cart()
    {
        if (!session()->get('quote') || empty(session()->get('quote')['products'])) {
            return false;
        }

        session()->forget('quote');
        return true;
    }

    public function get_products_update()
    {
        $products           = Product::orderBy('id', 'DESC')->get();
        return $products;
    }

    public function gen_ticket_b($id, $name, $nameqr)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = VentaGeneral::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        $data['factura']            = VentaGeneral::where('id', $id)->first();
        $data['cliente']            = Client::where('id', $factura->idcliente)->first();
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
        $data['detalle']            = DetailVentaGeneral::select(
            'detail_ventas_generales.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno'
        )
            ->join('products', 'detail_ventas_generales.idproducto', '=', 'products.id')
            ->where('idventa_general', $factura->id)
            ->get();

        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($factura->total, 2);

        $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
        $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
        $data['payment_modes']      = DetailPayment::select('detail_payments.*', 'pay_modes.descripcion as modo_pago')
            ->join('pay_modes', 'detail_payments.idpago', 'pay_modes.id')
            ->where('idfactura', $factura->id)
            ->where('idtipo_comprobante', $factura->idtipo_comprobante)
            ->get();
        $data['count_payment']      = count($data['payment_modes']);
        $data['nameqr']             = $nameqr;
        $pdf                        = PDF::loadView('admin.credits.ticket_b', $data)->setPaper($customPaper, 'landscape');
        return $pdf->save(public_path('files/ventas-generales/ticket/' . $name . '.pdf'));
    }

    public function send(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                         = $request->input('id');
        $venta_general              = VentaGeneral::where('id', $id)->first();
        $detalle                    = DetailVentaGeneral::select(
            'detail_ventas_generales.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_ventas_generales.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('detail_ventas_generales.idventa_general', $id)
            ->get();

        if ($venta_general->cdr == 1) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El comprobante ya fue enviado',
                'type'      => 'warning'
            ]);
            return;
        }

        $codigo_comprobante         = TypeDocument::where('id', $venta_general->idtipo_comprobante)->first()->codigo;
        $id_doc_cliente             = Client::where('id', $venta_general->idcliente)->first()->iddoc;
        $tipo_documento_cliente     = IdentityDocumentType::where('id', $id_doc_cliente)->first()->codigo;
        $dni_ruc                     = Client::where('id', $venta_general->idcliente)->first()->dni_ruc;
        $nombre_cliente             = Client::where('id', $venta_general->idcliente)->first()->nombres;
        $direccion_cliente          = Client::where('id', $venta_general->idcliente)->first()->direccion;
        $ubigeo_cliente             = Client::where('id', $venta_general->idcliente)->first()->ubigeo;
        $email_cliente              = Client::where('id', $venta_general->idcliente)->first()->email;
        $detalle_ubigeo             = $this->get_ubigeo($ubigeo_cliente);
        $tipo_moneda                 = Currency::where('id', $venta_general->idmoneda)->first()->codigo;
        $idserie                     = Serie::where('idtipo_documento', $venta_general->idtipo_comprobante)->first()->id;
        $empresa                     = Business::where('id', 1)->first();
        $detraccion                 = Detraccion::where('idventa_general', $id)->first();

        $data                         = [];
        if(!empty($detraccion)){
            $codigo_comprobante         = 36;
            $data['PorcentajeDetraccion']= ListadoDetra::find($detraccion->id)->porcentaje ?? 10;
        }
        $data['tipOperacion']         = "0101";
        $data['fecEmision']          = $venta_general->fecha_emision;
        $data['fecVencimiento']      = $venta_general->fecha_vencimiento;
        $data['tipComp']             = $codigo_comprobante;
        $data['serieComp']           = $venta_general->serie;
        $data['numeroComp']          = $venta_general->correlativo;
        $data['tipDocUsuario']       = $tipo_documento_cliente;
        $data['codCliente']          = strval($venta_general->idcliente);
        $data['numDocUsuario']       = $dni_ruc;
        $data['rznSocialUsuario']     = $nombre_cliente;
        $data['codPaisCliente']      = "PE";
        $data['codLocalEmisor']      = "";
        $data['desDireccionCliente'] = $direccion_cliente;
        $data['deptCliente']         = $detalle_ubigeo['departamento'];
        $data['provCliente']         = $detalle_ubigeo['provincia'];
        $data['distCliente']         = $detalle_ubigeo['distrito'];
        $data['urbCliente']          = "";
        $data['codUbigeoCliente']     = $ubigeo_cliente;
        $data['tipMoneda']           = $tipo_moneda;
        $data['tipCambio']           = "0.00";
        $data['Gravada']             = $venta_general->gravada;
        $data['Exonerada']           = $venta_general->exonerada;
        $data['Inafecta']            = $venta_general->inafecta;
        $data['Gratuita']            = "0.00";
        $data['Anticipo']            = "0.00";
        $data['DsctoGlobal']         = "0.00";
        $data['otrosCargos']         = "0.00";
        $data['mtoIgv']              = strval(round($venta_general->igv, 2));
        $data['mtoTotal']            = $venta_general->total;
        $data['servidorSunat']       = $empresa->servidor_sunat;
        $data['envioSunat']          = true;
        $data['UBL']                 = "2.1";
        $data['idserie']             = strval($idserie);
        $data['numdias']             = "0";
        $data['Cat10']               = "00"; // Nota de débito
        $data['Cat09']               = "00"; // Nota de crédito
        $data['docRef']              = "";
        $data['CodDir']              = "";
        $data['tipPago']             = "01";
        $data['accion']              = false;
        $data['obs']                 = $venta_general->observaciones;
        $data['saltosLinea']         = "1";
        $data['impresion']           = "A4";
        $data['rucEmp']              = $empresa->ruc;
        $data['dirEmp']              = "";
        $data['emailCli']            = $email_cliente;
        $igv                         = 0;
        $precio__unitario            = 0;
        $data['CuentaDetraccion']    = $empresa->cuenta_detraciones;
        $data['mtoDetraccion']       = $detraccion->valor_detraccion;

        foreach ($detalle as $i => $product) {
            if ($product->idcodigo_igv == 1) // Aplica igv
            {
                $igv                          = number_format((((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18) * (int) $product->cantidad), 2, ".", "");
                $precio__unitario             = number_format(($product->precio_unitario - ((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18)), 5, ".", "");
            } else {
                $igv                          = 0;
                $precio__unitario             = number_format($product->precio_unitario, 5, ".", "");
            }

            $data['items'][] = [
                'CodItem'             => strval($product->id),
                'codUnidadMedida'     => $product->unidad,
                'ctdUnidadItem'       => strval(ceil($product->cantidad)),
                'codProducto'         => strval($product->id),
                'desItem'             => $product->producto,
                'mtoValorUnitario'    => strval(number_format(($precio__unitario * $product->cantidad), 2, ".", "")),
                'mtoDsctoItem'        => "0.00",
                'mtoIgvItem'          => strval($igv),
                'tipAfeIGV'           => $product->codigo_igv,
                'tipPrecio'           => "01",
                'mtoPrecioVentaItem'  => strval(number_format((($precio__unitario * $product->cantidad)) + $igv, 2, ".", "")),
                'mtoValorVentaItem'   => strval(number_format((($precio__unitario * $product->cantidad)), 2, ".", "")),
                'porcentajeIgv'       => strval(ceil($product->igv)),
            ];
        }

        echo json_encode([
            'status'    => true,
            'data'      => $data,
            'idfactura' => $id,
            'empresa'   => $empresa
        ]);
    }

    public function update_cdr_bf(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idfactura          = $request->input('idfactura');
        VentaGeneral::where('id', $idfactura)->update([
            'cdr'           => 1,
            'estado'    => 1
        ]);
        echo json_encode(['status'  => true]);
    }
}
