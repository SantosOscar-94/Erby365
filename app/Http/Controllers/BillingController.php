<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\Currency;
use App\Models\DetailBilling;
use App\Models\DetailPayment;
use App\Models\IdentityDocumentType;
use App\Models\PayMode;
use App\Models\Serie;
use App\Models\TypeDocument;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;

class BillingController extends Controller
{
    public function index()
    {
        return view('admin.billings.list');
    }

    public function get()
    {
        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $billings    = DB::select("CALL get_list_billings()");
        }else{
            $billings    = Billing::select("billings.*", "clients.dni_ruc as dni_ruc", "clients.nombres as  nombre_cliente", "clients.telefono as telefono_cliente")
                        ->join('clients', 'billings.idcliente', 'clients.id')
                        ->where([['billings.idtipo_comprobante', '!=', 6], ['billings.idusuario', auth()->user()->id]])
                        ->orderBy('id', 'DESC');
        }

        //dd($billings);

        return Datatables()
            ->of($billings)
            ->addColumn('cliente', function ($billings) {
                $cliente  = $billings->nombre_cliente;
                return $cliente;
            })
            ->addColumn('documento', function ($billings) {
                $documento  = $billings->serie . '-' . $billings->correlativo;
                return $documento;
            })
            ->addColumn('fecha_de_emision', function ($billings) {
                $fecha_emision = date('d-m-Y', strtotime($billings->fecha_emision));
                return $fecha_emision;
            })
            ->addColumn('xml', function ($billings) {
                $cdr            = $billings->cdr;
                $type_document  = TypeDocument::where('id', $billings->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $url_api        = $business->url_api;
                $ruc            = $business->ruc;
                $name_file      = $ruc . '-' . $type_document . '-' . $billings->serie . '-' . $billings->correlativo . '.xml';
                $btn            = '';
                switch ($cdr) {
                    case '0':
                        $btn = '';
                        break;

                    case '1':
                        $btn = '<a target="_blank" href="' . $url_api . 'Xml/xml-firmados/' . $name_file . '" class="text-center text-primary"><i class="far fa-file-code"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('cdr', function ($billings) {
                $cdr            = $billings->cdr;
                $type_document  = TypeDocument::where('id', $billings->idtipo_comprobante)->first()->codigo;
                $business       = Business::where('id', 1)->first();
                $ruc            = $business->ruc;
                $url_api        = $business->url_api;
                $btn            = '';
                $name_file      = 'R-' . $ruc . '-' . $type_document . '-' . $billings->serie . '-' . $billings->correlativo . '.xml';
                switch ($cdr) {
                    case '0':
                        $btn .= '';
                        break;

                    case '1':
                        $btn .= '<a target="_blank" href="' . $url_api . 'Cdr/' . $name_file . '" class="text-center text-primary"><i class="fas fa-file-alt"></i></a>';
                        break;
                }
                return $btn;
            })
            ->addColumn('estado_sunat', function ($billings) {
                $cdr    = $billings->cdr;
                $btn    = '';
                switch ($cdr) {
                    case '0':
                        $btn .= '<span class="badge bg-warning text-white">Sin enviar</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Aceptado</span>';
                        break;

                    case '2':
                        $btn .= '<span class="badge bg-danger text-white">Rechazado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('estado_cpe', function ($billings) {
                $estado_cpe    = $billings->estado_cpe;
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
            ->addColumn('wap', function ($billings) {
                $wap = '<a class="btn-open-whatsapp" data-id="' . $billings->id . '" data-telefono="' . $billings->telefono_cliente . '" href="javascript:void(0);">
                            <i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem;"></i>
                        </a>';
                return $wap;
            })
            ->addColumn('acciones', function ($billings) {
                $id     = $billings->id;
                $btn    = '<div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn-send-sunat" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                <span> Enviar a SUNAT</span>
                                            </a>
                                            <a class="dropdown-item btn-print" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                                <span> Imprimir Ticket</span>
                                            </a>
                                            <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                <span>Descargar PDF A4</span>
                                            </a>
                                            <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="'. route("admin.create_nc", $id) .'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-slash"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                                <span>Nota de Crédito</span>
                                            </a>
                                            <a class="dropdown-item btn-open-whatsapp" data-id="' . $id . '" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp" style="margin-right: 0.5rem;"></i>
                                            <span> Enviar Documento</span>
                                            </a>
                                        </div>
                                    </div>';
                return $btn;
            })
            ->rawColumns(['fecha_de_emision', 'xml', 'cdr', 'documento', 'cliente', 'estado_sunat', 'estado_cpe', 'acciones', 'wap'])
            ->make(true);
    }

    public function print(Request $request)
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
        $customPaper                = array(0, 0, 630.00, 210.00);
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $ruc                        = $data['business']->ruc;
        $factura                    = Billing::where('id', $id)->first();
        $codigo_comprobante         = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $data["name"]               = $ruc . '-' . $codigo_comprobante . '-' . $factura->serie . '-' . $factura->correlativo;

        if (!file_exists(public_path('files/billings/ticket/' . $data["name"] . '.pdf'))) {
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
            return $pdf->save(public_path('files/billings/ticket/' . $data["name"] . '.pdf'));
        }

        echo json_encode([
            'status'    => true,
            'pdf'       => $data["name"] . '.pdf'
        ]);
    }

    public function test()
    {
        $pdf    = PDF::loadView('admin.billings.test_a4')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function download($id)
    {
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $data['factura']            = Billing::where('id', $id)->first();
        $data['tipo_comprobante']   = TypeDocument::where('id', $data["factura"]->idtipo_comprobante)->first();
        $data["type_document"]      = TypeDocument::where('id', $data["factura"]["idtipo_comprobante"])->first();
        $data["client"]             = Client::where('id', $data["factura"]["idcliente"])->first();
        $data['cliente']            = Client::where('id', $data["factura"]->idcliente)->first();
        $data["name"]               = mb_strtoupper($data["client"]->dni_ruc . '-' . $data["factura"]["serie"]) . '-' . $data["factura"]["correlativo"];
        $data['tipo_documento']     = IdentityDocumentType::where('id', $data['cliente']->iddoc)->first();
        $data['moneda']             = Currency::where('id', $data["factura"]->idmoneda)->first();
        $data['modo_pago']          = PayMode::where('id', $data["factura"]->modo_pago)->first();
        $data['detalle']            = DetailBilling::select(
            'detail_billings.*',
            'products.descripcion as producto',
            'products.codigo_interno as codigo_interno',
            'units.codigo as unidad'
        )
            ->join('products', 'detail_billings.idproducto', '=', 'products.id')
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->where('idfacturacion', $data["factura"]->id)
            ->get();

        $formatter                  = new NumeroALetras();
        $data['numero_letras']      = $formatter->toWords($data["factura"]->total, 2);
        $data['tipo_comprobante']   = TypeDocument::where('id', $data["factura"]->idtipo_comprobante)->first();
        $data['vendedor']           = mb_strtoupper(User::where('id', $data['factura']->idusuario)->first()->user);

        $pdf    = PDF::loadView('admin.billings.a4', $data)->setPaper('A4', 'portrait');
        return $pdf->download($data["name"] . '.pdf');
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
        $id                 = $request->input('id');
        $factura            = DB::select("CALL get_bf(?)", [$id])[0];
        $detalle            = DB::select("CALL get_detail_bf(?)", [$id]);
        if ($factura->cdr == 1) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El comprobante ya fue enviado',
                'type'      => 'warning'
            ]);
            return;
        }

        $codigo_comprobante             = TypeDocument::where('id', $factura->idtipo_comprobante)->first()->codigo;
        $id_doc_cliente                 = Client::where('id', $factura->idcliente)->first()->iddoc;
        $tipo_documento_cliente         = IdentityDocumentType::where('id', $id_doc_cliente)->first()->codigo;
        $dni_ruc                        = Client::where('id', $factura->idcliente)->first()->dni_ruc;
        $nombre_cliente                 = Client::where('id', $factura->idcliente)->first()->nombres;
        $direccion_cliente              = Client::where('id', $factura->idcliente)->first()->direccion;
        $ubigeo_cliente                 = Client::where('id', $factura->idcliente)->first()->ubigeo;
        $email_cliente                  = Client::where('id', $factura->idcliente)->first()->email;
        $detalle_ubigeo                 = $this->get_ubigeo($ubigeo_cliente);
        $tipo_moneda                    = Currency::where('id', $factura->idmoneda)->first()->codigo;
        $idserie                        = Serie::where('idtipo_documento', $factura->idtipo_comprobante)->first()->id;
        $empresa                        = Business::where('id', 1)->first();

        $data                           = [];
        $data['tipOperacion']           = "0101";
        $data['fecEmision']             = $factura->fecha_emision;
        $data['fecVencimiento']         = $factura->fecha_vencimiento;
        $data['tipComp']                = $codigo_comprobante;
        $data['serieComp']              = $factura->serie;
        $data['numeroComp']             = $factura->correlativo;
        $data['tipDocUsuario']          = $tipo_documento_cliente;
        $data['codCliente']             = strval($factura->idcliente);
        $data['numDocUsuario']          = $dni_ruc;
        $data['rznSocialUsuario']       = $nombre_cliente;
        $data['codPaisCliente']         = "PE";
        $data['codLocalEmisor']         = "";
        $data['desDireccionCliente']    = $direccion_cliente;
        $data['deptCliente']            = $detalle_ubigeo['departamento'];
        $data['provCliente']            = $detalle_ubigeo['provincia'];
        $data['distCliente']            = $detalle_ubigeo['distrito'];
        $data['urbCliente']             = "";
        $data['codUbigeoCliente']       = $ubigeo_cliente;
        $data['tipMoneda']              = $tipo_moneda;
        $data['tipCambio']              = "0.00";
        $data['Gravada']                = $factura->gravada;
        $data['Exonerada']              = $factura->exonerada;
        $data['Inafecta']               = $factura->inafecta;
        $data['Gratuita']               = "0.00";
        $data['Anticipo']               = "0.00";
        $data['DsctoGlobal']            = "0.00";
        $data['otrosCargos']            = "0.00";
        $data['mtoIgv']                 = strval(round($factura->igv, 2));
        $data['mtoTotal']               = $factura->total;
        $data['servidorSunat']          = $empresa->servidor_sunat;
        $data['envioSunat']             = true;
        $data['UBL']                    = "2.1";
        $data['idserie']                = strval($idserie);
        $data['numdias']                = "0";
        $data['Cat10']                  = "00"; // Nota de débito
        $data['Cat09']                  = "00"; // Nota de crédito
        $data['docRef']                 = "";
        $data['CodDir']                 = "";
        $data['tipPago']                = "01";
        $data['accion']                 = false;
        $data['obs']                    = $factura->observaciones;
        $data['saltosLinea']            = "1";
        $data['impresion']              = "A4";
        $data['rucEmp']                 = $empresa->ruc;
        $data['dirEmp']                 = "";
        $data['emailCli']               = $email_cliente;
        $igv                            = 0;
        $precio__unitario               = 0;

        foreach ($detalle as $i => $product) {
            if ($product->idcodigo_igv == 1) // Aplica igv
            {
                $igv                    = number_format((((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18) * (int) $product->cantidad), 2, ".", "");
                $precio__unitario   = number_format(($product->precio_unitario - ((float) $product->precio_unitario - (float) $product->precio_unitario / 1.18)), 5, ".", "");
            } else {
                $igv                = 0;
                $precio__unitario   = number_format($product->precio_unitario, 5, ".", "");
            }

            $data['items'][] =
                [
                    'CodItem'               => strval($product->id),
                    'codUnidadMedida'       => $product->unidad,
                    'ctdUnidadItem'         => strval(ceil($product->cantidad)),
                    'codProducto'           => strval($product->id),
                    'desItem'               => $product->producto,
                    'mtoValorUnitario'      => strval(number_format(($precio__unitario * $product->cantidad), 2, ".", "")),
                    'mtoDsctoItem'          => "0.00",
                    'mtoIgvItem'            => strval($igv),
                    'tipAfeIGV'             => $product->codigo_igv,
                    'tipPrecio'             => "01",
                    'mtoPrecioVentaItem'    => strval(number_format((($precio__unitario * $product->cantidad)) + $igv, 2, ".", "")),
                    'mtoValorVentaItem'     => strval(number_format((($precio__unitario * $product->cantidad)), 2, ".", "")),
                    'porcentajeIgv'         => strval(ceil($product->igv)),
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
        Billing::where('id', $idfactura)->update([
            'cdr'           => 1,
            'estado_cpe'    => 1
        ]);
        echo json_encode(['status'  => true]);
    }

    public function userBills(Request $request) {
        $identificacion = $request->input('identificacion');
        $fechaDesde = $request->input('fechaDesde');
        $fechaHasta = $request->input('fechaHasta');
        $tipoComprobante = $request->input('tipoComprobante');
        $product = $request->input('product');
        $html_bills = '';

        if ($product != 0) {
            $bills = Billing::select('billings.fecha_emision as fecha_emision', 'clients.nombres as cliente', 'clients.id as id_cliente', 'clients.dni_ruc as dniCliente', 'products.descripcion as producto', 'products.id as producto_id','detail_billings.cantidad as cantidad', 'detail_billings.precio_unitario as precio_unitario', 'detail_billings.precio_total as total')
                    ->join('detail_billings', 'billings.id', 'detail_billings.idfacturacion')
                    ->join('products', 'detail_billings.idproducto', 'products.id')
                    ->join('clients', 'billings.idcliente', 'clients.id')
                    ->where([['clients.dni_ruc', '=', $identificacion], ["products.id", "=", $product]])
                    //->whereBetween('billings.fecha_emision', [$fechaDesde, $fechaHasta])
                    ->orderBy('billings.id', 'DESC')
                    ->get();
        }else{
            $bills = Billing::select('billings.fecha_emision as fecha_emision', 'clients.nombres as cliente', 'clients.id as id_cliente', 'clients.dni_ruc as dniCliente', 'products.descripcion as producto', 'products.id as producto_id','detail_billings.cantidad as cantidad', 'detail_billings.precio_unitario as precio_unitario', 'detail_billings.precio_total as total')
                    ->join('detail_billings', 'billings.id', 'detail_billings.idfacturacion')
                    ->join('products', 'detail_billings.idproducto', 'products.id')
                    ->join('clients', 'billings.idcliente', 'clients.id')
                    ->where('clients.dni_ruc', '=', $identificacion)
                    //->whereBetween('billings.fecha_emision', [$fechaDesde, $fechaHasta])
                    ->orderBy('billings.id', 'DESC')
                    ->get();
        }



        //return json_encode($bills);

        if (!empty($bills)) {
            foreach ($bills as $bill) {

                $html_bills .= '<tr>
                                    <td>'.$bill->fecha_emision.'</td>
                                    <td>'.$bill->cliente.'</td>
                                    <td>'.$bill->producto.'</td>
                                    <td>'.$bill->cantidad.'</td>
                                    <td>'.$bill->precio_unitario.'</td>
                                    <td>'.$bill->total.'</td>
                                    <td><button type="button" class="btn btn-primary" onclick="addProduct('.$bill->producto_id.', '.$bill->precio_unitario.', \''.$bill->cliente.'\', '.$bill->id_cliente.', '.$bill->dniCliente.')"  data-bs-dismiss="modal">Aplicar precio</button></td>
                                </tr>';
            }
        }

        echo json_encode([
            'bills'         => $bills,
            'html_bills'    =>  $html_bills
        ]);
    }
}
