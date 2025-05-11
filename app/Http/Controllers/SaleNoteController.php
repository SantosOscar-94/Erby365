<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailSaleNote;
use App\Models\ArchingCash;
use App\Models\Kardex;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\DetailSaleNote;
use App\Models\IdentityDocumentType;
use App\Models\IgvTypeAffection;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\SaleNote;
use App\Models\Serie;
use App\Models\StockProduct;
use App\Models\TypeDocument;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Luecano\NumeroALetras\NumeroALetras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail; 
use Svg\Tag\Rect;

class SaleNoteController extends Controller
{
    public function index()
    {
        return view('admin.sale_notes.list');
    }

    public function get()
    {
        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $sale_notes    = DB::select("CALL get_list_sale_notes()");
        }else{
            $sale_notes    = SaleNote::select("sale_notes.*", "clients.dni_ruc as dni_ruc", "clients.nombres as  nombre_cliente")
                        ->join('clients', 'sale_notes.idcliente', 'clients.id')
                        ->where([['sale_notes.idusuario', auth()->user()->id]])
                        ->orderBy('sale_notes.id', 'DESC');
        }

        return Datatables()
                    ->of($sale_notes)
                    ->addColumn('cliente', function ($sale_notes) {
                        $cliente  = $sale_notes->nombre_cliente;
                        return $cliente;
                    })
                    ->addColumn('documento', function ($sale_notes) {
                        $documento  = $sale_notes->serie . '-' . $sale_notes->correlativo;
                        return $documento;
                    })
                    ->addColumn('fecha_de_emision', function ($sale_notes) {
                        $fecha_emision = date('d-m-Y', strtotime($sale_notes->fecha_emision));
                        return $fecha_emision;
                    })
                    ->addColumn('estado', function ($sale_notes) 
                    {
                        $estado    = $sale_notes->estado;
                        $btn    = '';
                        switch ($estado) {
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
                    ->addColumn('acciones', function($sale_notes){
                        /* <a class="dropdown-item btn-open-whatsapp" data-id="' . $id . '" href="javascript:void(0);">
                                                <i class="fa-regular fa-envelope"></i>
                                                <span> Enviar Documento</span>
                        </a> */
                        $id                 = $sale_notes->id;
                        $cliente            = Client::where('id', $sale_notes->idcliente)->first();
                        $idtipo_comprobante = (strlen($cliente->dni_ruc) == 11) ? 1 : 2;
                        $btn    = '<div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                        <div class="dropdown-menu"><a class="dropdown-item btn-print" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                <span> Imprimir Ticket</span>
                                            </a>
                                            <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                <span>Descargar PDF A4</span>
                                            </a>
                                            <a class="dropdown-item btn-gen-billing" data-idtipo_comprobante="'.$idtipo_comprobante.'" data-id="'.$id.'" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                <span>Generar Boleta/Factura</span>
                                            </a>
                                            <a class="dropdown-item btn-confirm" data-id="'.$id.'" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-slash"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                                <span>Anular</span>
                                            </a>
                                        </div>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['fecha','acciones', 'estado'])
                    ->make(true);   
    }

    public function test()
    {
        $customPaper        = array(0, 0, 630.00, 210.00);
        $pdf                = PDF::loadView('admin.billings.test_ticket')->setPaper($customPaper, 'landscape');
        return $pdf->stream();
    }

    public function create()
    {
        $data['type_documents_p']   = TypeDocument::where('id', 7)->get();
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['modo_pagos']         = PayMode::get();
        $data['clients']            = Client::get();
        $data['products']           = Product::get();
        $data["units"]              = Unit::where('estado', 1)->get();
        $data["warehouses"]         = Warehouse::get();
        $data['type_inafects']      = IgvTypeAffection::where('estado', 1)->get();
        return view('admin.sale_notes.create', $data);
    }

    public function load_serie(Request $request)
    {
        if (!$request->ajax()) 
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idcaja     = Auth::user()['idcaja'];
        $serie      = Serie::where('id', '>', 4)->where('idtipo_documento', 7)->where('idcaja', $idcaja)->first();
        echo json_encode(['status'  => true, 'serie'  => $serie]);
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

        if (!empty($cart['products'])) 
        {
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
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . number_format($product["precio_venta"], 2, ".", "") . '" data-cantidad="'.$product["cantidad"].'" data-id="' . $product["id"] . '" name="precio"></td>
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
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="w-px-100">Total:</span>
                                    <span class="fw-medium">S/' . number_format($cart['total'], 2, ".", "") . '</span>
                            </div>';

        echo json_encode([
            'status'        => true,
            'cart_products' => $cart,
            'html_cart'     => $html_cart,
            'html_totales'  => $html_totales
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
        if(!$this->add_product_cart($id, $cantidad, $precio, $opcion))
        {   
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
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id     = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        $opcion         = (int) $producto->opcion;
        if(!$this->delete_product_cart($id, $opcion))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se pudo eliminar el producto',
                'type'      => 'warning'
            ]);
            return;
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Producto eliminado correctamente',
            'type'      => 'success'
        ]);
    }

    public function store_product(Request $request)
    {
        if(!$request->ajax())
        {
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
        if(!$this->update_quantity($id , $cantidad, $precio, $opcion))
        {
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
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idtipo_comprobante     = 7;
        $serie                  = $request->input('serie');
        $correlativo            = $request->input('correlativo');
        $fecha_emision          = $request->input('fecha_emision');
        $fecha_emision          = $request->input('fecha_emision');
        $fecha_vencimiento      = $request->input('fecha_vencimiento');
        $idcliente              = $request->input('dni_ruc');
        $tipo_cambio            = $request->input('tipo_cambio');
        $modo_pago              = $request->input('modo_pago');
        $cart                   = $this->create_cart(); 
        $id_arching             = ArchingCash::where('idcaja', Auth::user()['idcaja'])->where('idusuario', Auth::user()['id'])->latest('id')->first()['id'];
        $idalmacen              = Auth::user()['idalmacen'];

        if(empty($idcliente))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe seleccionar el cliente',
                'type'      => 'warning'
            ]);
            return;
        }

        if(empty($cart['products']))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        SaleNote::create([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'serie'                 => $serie,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idcliente'             => $idcliente,
            'idmoneda'              => 1,
            'idpago'                => 1,
            'modo_pago'             => $modo_pago,
            'exonerada'             => $cart["exonerada"],
            'inafecta'              => $cart["inafecta"],
            'gravada'               => $cart["gravada"],
            'anticipo'              => "0.00",
            'igv'                   => $cart['igv'],
            'gratuita'              => "0.00",
            'otros_cargos'          => "0.00",
            'total'                 => $cart['total'],
            'estado'                => 1,
            'idusuario'             => Auth::user()['id'],
            'idcaja'                => $id_arching,
            'idalmacen'             => $idalmacen
        ]);
        
        $idnotaventa                = SaleNote::latest('id')->first()['id'];
        DetailPayment::create([
            'idtipo_comprobante'    => $idtipo_comprobante,
            'idfactura'             => $idnotaventa,
            'idpago'                => $modo_pago,
            'monto'                 => $cart['total'],
            'idcaja'                => $id_arching
        ]);
        foreach ($cart['products'] as $product) 
        {
            DetailSaleNote::create([
                'idnotaventa'           => $idnotaventa,
                'idproducto'            => $product['id'],
                'cantidad'              => $product['cantidad'],
                'descuento'             => 0.0000000000,
                'igv'                   => $product["igv"],
                'id_afectacion_igv'     => $product['idcodigo_igv'],
                'precio_unitario'       => $product['precio_venta'],
                'precio_total'          => ($product['precio_venta'] * $product['cantidad'])
            ]);

            if($product["opcion"] == 1)
            {
                StockProduct::where('idproducto', $product["id"])
                            ->where('idalmacen', $product["idalmacen"])
                            ->update([
                                 'cantidad'  => $product["stock"] - $product["cantidad"]
                            ]);
            }
            
            $stock = StockProduct::where('idproducto', $product["id"])->where('idalmacen', $idalmacen)->first();
            $oProduct = Product::findOrFail($product["id"]);
            
            Kardex::create([
                'documentTypeId'    => $idtipo_comprobante, //$product['idtipo_comprobante'],
                'userId'            => auth()->user()->id,
                'warehouseId'       => auth()->user()->idalmacen,
                'document'     => $serie.'-'.$correlativo, //$product['serie']."-".$product['correlativo'], 
                'product'      => mb_strtoupper($product['descripcion']),
                'cant2'      => $product["cantidad"],
                'price2'      => ($product['precio_venta'] * -1),
                'total2'      => ($product['precio_venta'] * $product['cantidad']),
                'cant3'      => $stock->cantidad,
                'price3'      => $oProduct->precio_venta,
                'total3'      => (($oProduct->precio_venta * $stock->cantidad) *-1)
            ]);
        }
        $ultima_serie                   = Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->first();
        $ultimo_correlativo             = (int) $ultima_serie->correlativo + 1;
        $nuevo_correlativo              = str_pad($ultimo_correlativo, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo
        ]);

        $this->destroy_cart();
        // Gen ticket data to pdf
        $factura                        = SaleNote::where('id', $idnotaventa)->first();
        $ruc                            = Business::where('id', 1)->first()->ruc;
        $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $name                           = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;
        $this->gen_ticket($idnotaventa, $name);

        echo json_encode([
            'status'    => true,
            'pdf'       => $name . '.pdf',
        ]);
    }

    public function print(Request $request)
    {
        if (!$request->ajax()) 
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                         = $request->input('id');
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = SaleNote::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        if(!file_exists(public_path('files/sale-notes/ticket/' . $data["name"] . '.pdf')))
        {
            $data['factura']            = SaleNote::where('id', $id)->first();
            $data['cliente']            = Client::where('id', $factura->idcliente)->first();
            $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
            $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
            $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
            $data['detalle']            = DetailSaleNote::select('detail_sale_notes.*', 'products.descripcion as producto', 
                                        'products.codigo_interno as codigo_interno')
                                        ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
                                        ->where('idnotaventa', $factura->id)
                                        ->get();
    
            $formatter                  = new NumeroALetras();
            $data['numero_letras']      = $formatter->toWords($factura->total, 2);
            $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
            $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
            $pdf                        = PDF::loadView('admin.sale_notes.ticket', $data)->setPaper($customPaper, 'landscape');
            $pdf->save(public_path('files/sale-notes/ticket/' . $data["name"] . '.pdf'));
        }
        
        echo json_encode([
            'status'    => true,
            'pdf'       => $data["name"] . '.pdf'
        ]);
    }

    public function download($id)
    {
        $data['factura']            = SaleNote::where('id', $id)->first();
        $data["business"]           = Business::where('id', 1)->first();
        $data["client"]             = Client::where('id', $data["factura"]["idcliente"])->first();
        $data["ubigeo"]             = $this->get_ubigeo($data["business"]->ubigeo);
        $data["name"]               = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["factura"]["serie"]) . '-' . $data["factura"]["correlativo"];
        $data["type_document"]      = TypeDocument::where('id', $data["factura"]["idtipo_comprobante"])->first();
        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["factura"]->total, 2);
        $data['detalle']            = DetailSaleNote::select('detail_sale_notes.*', 'products.descripcion as producto', 
                                        'products.codigo_interno as codigo_interno')
                                        ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
                                        ->where('idnotaventa', $data["factura"]->id)
                                        ->get();

        $pdf    = PDF::loadView('admin.sale_notes.a4', $data)->setPaper('A4', 'portrait');
        return $pdf->download($data["name"] . '.pdf');
    }

    public function anulled(Request $request)
    {
        if (!$request->ajax()) 
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = $request->input('id');
        $sale_note      = SaleNote::where('id', $id)->first();
        $detail_sale    = DetailSaleNote::where('idnotaventa', $id)->get();

        if($sale_note->estado == '2')
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'La venta se encuentra anulada',
                'type'      => 'warning'
            ]);
            return;
        }

        foreach($detail_sale as $product)
        {
            $id_product     = (int) $product["idproducto"];
            $cantidad       = intval($product["cantidad"]);
            $product        = Product::where('id', $id_product)->first();

            if($product->opcion == 1) {
                $stock__actual  = StockProduct::where('idproducto', $id_product)->where('idalmacen', $sale_note->idalmacen)->first();
                StockProduct::where('idproducto', $product["id"])->where('idalmacen', $sale_note->idalmacen)->update([
                    'cantidad'  => $stock__actual->cantidad + $cantidad
                ]);
            }
        }

        DetailPayment::where('idtipo_comprobante', 7)->where('idfactura', $id)->update([
            'estado'    => 2
        ]);

        SaleNote::where('id', $id)->update([
            'estado' => 2
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Venta anulada correctamente',
            'type'      => 'success'
        ]);
    }

    public function gen_ticket($id, $name)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = SaleNote::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        $data['factura']            = SaleNote::where('id', $id)->first();
        $data['cliente']            = Client::where('id', $factura->idcliente)->first();
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
        $data['detalle']            = DetailSaleNote::select('detail_sale_notes.*', 'products.descripcion as producto', 
                                    'products.codigo_interno as codigo_interno')
                                    ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
                                    ->where('idnotaventa', $factura->id)
                                    ->get();

        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($factura->total, 2);
        $data['tipo_comprobante']   = TypeDocument::where('id', $factura->idtipo_comprobante)->first();
        $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);
        $pdf                        = PDF::loadView('admin.sale_notes.ticket', $data)->setPaper($customPaper, 'landscape');
        return $pdf->save(public_path('files/sale-notes/ticket/' . $name . '.pdf'));
    }

    public function gen_voucher(Request $request)
    {
        if(!$request->ajax()){
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                     = $request->input('id');
        $quote                  = SaleNote::where('id', $id)->first();
        $detalle                = DetailSaleNote::select('detail_sale_notes.*', 'products.descripcion as producto',
                                'products.codigo_interno as codigo_interno','units.codigo as unidad', 'products.idcodigo_igv as idcodigo_igv',
                                'products.igv as igv', 'products.opcion as opcion')
                                ->join('products', 'detail_sale_notes.idproducto', '=', 'products.id')
                                ->join('units', 'products.idunidad', '=', 'units.id')
                                ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
                                ->where('detail_sale_notes.idnotaventa', $id)
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
        
        $qr                         = $business->ruc . ' | ' . $type_document->codigo . ' | ' . $serie . ' | ' . $correlativo . ' | ' . number_format($quote->igv, 2, ".", "") . ' | ' . number_format($quote->total, 2, ".", "") . ' | ' . $fecha_emision . ' | ' . $identity_document->codigo . ' | ' . $client->dni_ruc;
        $name_qr                    = $serie . '-' . $correlativo;

        // Gen Qr
        QrCode::format('png')
        ->size(140)
        ->generate($qr, 'files/billings/qr/' . $name_qr . '.png');

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

            if($product["opcion"] == 1)
            {
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
        $ultimo_correlativo_sale= (int) $ultima_serie_sale->correlativo + 1;
        $nuevo_correlativo_sale = str_pad($ultimo_correlativo_sale, 8, '0', STR_PAD_LEFT);
        Serie::where('idtipo_documento', $idtipo_comprobante)->where('idcaja', Auth::user()['idcaja'])->update([
            'correlativo'   => $nuevo_correlativo_sale
        ]);

        echo json_encode([
            'status'        => true,
            'id'            => $id_sale,
            'pdf'           => $name_sale . '.pdf',
            'type_document' => $idtipo_comprobante
        ]);
    }

    ## Functions to cart
    public function create_cart()
    {
        if (!session()->get('sale_note') || empty(session()->get('sale_note')['products'])) {
            $sale_note =
                [
                    'sale_note' =>
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

            session($sale_note);
            return session()->get('sale_note');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('sale_note')['products'] as $index => $product) {
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
            session()->put('sale_note.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;

        $sale_note =
            [
                'sale_note' =>
                [
                    'products'     => session('sale_note')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($sale_note);
        return session()->get('sale_note');
    }

    public function add_product_cart($id, $cantidad, $precio, $opcion)
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
        ->where('warehouses.id', Auth::user()['idalmacen'])
        ->first();

        if(!$product)
            return false;

        if ($opcion == 1)
        {
            if ($product->stock < $cantidad) 
            {
                return false;
            } elseif($product->stock == 0)
            {
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
            'idalmacen'         => ($opcion == 1) ? $product->idalmacen : null,
        ];

        if (empty(session()->get('sale_note')['products'])) {
            session()->push('sale_note.products', $new_product);
            return true;
        }

        foreach (session()->get('sale_note')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if ($opcion == 1) {
                    if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                        return false;
                    }
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('sale_note.products.' . $index, $product);
                return true;
            }
        }

        session()->push('sale_note.products', $new_product);
        return true;
    }

    public function delete_product_cart($id, $opcion)
    {
        if (!session()->get('sale_note') || empty(session()->get('sale_note')['products'])) {
            return false;
        }

        foreach (session()->get('sale_note')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                session()->forget('sale_note.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad, $precio, $opcion)
    {
        if (empty(session()->get('sale_note')['products'])) {
            return false;
        }

        foreach (session()->get('sale_note')['products'] as $index => $product) {
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
                session()->put('sale_note.products.' . $index, $product);
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
        if (!session()->get('sale_note') || empty(session()->get('sale_note')['products'])) {
            return false;
        }

        session()->forget('sale_note');
        return true;
    }

    public function gen_ticket_b($id, $name)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = Billing::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        $data['factura']            = Billing::where('id', $id)->first();
        $data['cliente']            = Client::where('id', $factura->idcliente)->first();
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $factura->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $factura->modo_pago)->first();
        $data['detalle']            = DetailBilling::select(
            'detail_billings.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno'
        )
            ->join('products', 'detail_billings.idproducto', '=', 'products.id')
            ->where('idfacturacion', $factura->id)
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
        $pdf                        = PDF::loadView('admin.billings.ticket_b', $data)->setPaper($customPaper, 'landscape');
        return $pdf->save(public_path('files/billings/ticket/' . $name . '.pdf'));
    }
}
