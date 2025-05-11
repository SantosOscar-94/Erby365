<?php

namespace App\Http\Controllers;

use App\Models\ArchingCash;
use App\Models\DetailPayment;
use App\Models\Warehouse;
use App\Models\Bill;
use App\Models\Billing;
use App\Models\SaleNote;
use App\Models\Business;
use App\Models\DetailBilling;
use App\Models\PurchaseDescription;
use App\Models\User;
use App\Models\Currency;
use App\Models\TypeDocument;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArchingCashController extends Controller
{
    public function index()
    {
        $data["users"]          = User::all();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        $data["warehouses"]     = Warehouse::all();
        
        return view('admin.arching_cashes.list', $data);
    }

    public function get(Request $request)
    {
        parse_str($request->filters, $filter);
        //dd($filter);
        $fecha_inicial      = $filter['fecha_inicial'];
        $fecha_final        = $filter['fecha_final'];
        $vendedor           = $filter['user'] ?? null;
        $warehouse          = $filter['warehouse'] ?? null;

        $user = User::with('roles')->where('id', Auth::user()['id'])->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $arching_cashes     = ArchingCash::select('arching_cashes.*', 'users.user as usuario', 'users.id as usuario_id')
            ->join('users', 'arching_cashes.idusuario', '=' ,'users.id')
            ->whereBetween('arching_cashes.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('id', 'DESC')
            ->get();
           
        }else{
            $arching_cashes     = ArchingCash::select('arching_cashes.*', 'users.user as usuario', 'users.id as usuario_id')
            ->join('users', 'arching_cashes.idusuario', '=' ,'users.id')
            ->where("idusuario", "=", Auth::user()->id)
            ->whereBetween('arching_cashes.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('id', 'DESC')
            ->get(); 
        }
        
        

        if (!empty($vendedor)) {
            $arching_cashes = $arching_cashes->where('usuario_id', $vendedor);
        }

        if (!empty($warehouse)) {
            $arching_cashes = $arching_cashes->filter(function ($item) use($warehouse) {
                if(Warehouse::find(User::find($item->usuario_id)->idalmacen)->id == $warehouse){
                    return $item;
                }
            });
        }
        
        //dd(Warehouse::find(User::find(2)->idalmacen)->id);

        return Datatables()
                    ->of($arching_cashes)
                    ->addColumn('fecha1', function($arching_cashes){
                        $fecha = date('d-m-Y H:i:s', strtotime($arching_cashes->created_at));
                        return $fecha;
                    })
                    ->addColumn('fecha2', function($arching_cashes){
                        $fecha = $arching_cashes->estado == 1 ? "No disponible" : date('d-m-Y H:i:s', strtotime($arching_cashes->updated_at));
                        return $fecha;
                    })
                    ->addColumn('cajero', function($arching_cashes){
                        $cajero = mb_strtoupper($arching_cashes->usuario);
                        return $cajero;
                    })
                    ->addColumn('tienda', function($arching_cashes){
                        return Warehouse::find(User::find($arching_cashes->usuario_id)->idalmacen)->descripcion;
                    })
                    ->addColumn('monto_cierre', function($arching_cashes){
                        $cajero = $arching_cashes->estado == 1 ? "No disponible" : $arching_cashes->monto_final;
                        return $cajero;
                    })
                    ->addColumn('products', function($arching_cashes){
                        $cant1 = Billing::join('detail_billings', 'billings.id', 'detail_billings.idfacturacion')
                        ->where([['billings.idcaja', $arching_cashes->id], ['billings.estado_cpe', 1], ['billings.idtipo_comprobante', '!=', 6]])
                        ->sum('detail_billings.cantidad');
                        
                        $cant2 = SaleNote::join('detail_sale_notes', 'sale_notes.id', 'detail_sale_notes.idnotaventa')
                        ->where([['sale_notes.idcaja', $arching_cashes->id], ['sale_notes.idtipo_comprobante', '!=', 6]])
                        ->sum('detail_sale_notes.cantidad');
                        
                        return (int) $cant1 + $cant2;
                    })    
                    ->addColumn('billings', function($arching_cashes){
                        return (int)Billing::where([['idcaja', $arching_cashes->id], ['billings.estado_cpe', 1], ['billings.idtipo_comprobante', '!=', 6]])->count()
                        + (int)SaleNote::where([['idcaja', $arching_cashes->id], ['sale_notes.idtipo_comprobante', '!=', 6]])->count();
                    })
                    ->addColumn('estado', function($arching_cashes){
                        $estado    = $arching_cashes->estado;
                        $btn        = '';
                        switch($estado)
                        {
                            case '1':
                                $btn .= '<span class="badge bg-success text-white">ABIERTO</span>';
                                break;

                            case '2':
                                $btn .= '<span class="badge bg-danger text-white">CERRADO</span>';
                                break;
                        }
                        return $btn;
                    })
                    ->addColumn('acciones', function($arching_cashes){
                        $id     = $arching_cashes->id;
                        $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail-cash" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        <span> Ver Detalle</span>
                                    </a>
                                    <a class="dropdown-item btn-summary" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                                        <span> Ver Resumen</span>
                                    </a>
                                    <a class="dropdown-item btn-download" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        <span>Descargar Reporte</span>
                                    </a>';
                        if($arching_cashes->estado == 1){
                            $btn    .= '<a class="dropdown-item btn-confirm" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        <span>Cerrar Caja</span>
                                    </a>';
                        }else if(User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name == "SUPERADMIN" || 
                        User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name == "ADMIN"){
                            $btn    .= '<a class="dropdown-item btn-confirm2" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                        <span>Reabrir Caja</span>
                                    </a>';
                        }
                                    
                                    
                                    
                        $btn    .= '</div>
                                </div>';
                        return $btn;
                    })
                    ->rawColumns(['fecha1', 'fecha2', 'cajero', 'estado', 'acciones'])
                    ->make(true);   
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

        $idcaja         = $request->input('idcaja');
        $idusuario      = $request->input('idusuario');
        $fecha_inicio   = date('Y-m-d');
        $fecha_fin      = date('Y-m-d');
        $monto_inicial  = $request->input('monto_inicial');
        $monto_final    = $request->input('monto_inicial');
        $total_ventas   = 0;
        $estado         = 1;

        $buscar_caja    = count(ArchingCash::where('idcaja', $idcaja)->where('idusuario', $idusuario)->where('estado', 1)->get());
        if($buscar_caja >= 1)
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe cerrar caja actual',
                'type'      => 'warning'
            ]);
            return;
        }

        ArchingCash::create([
            'idcaja'        => $idcaja,
            'idusuario'     => $idusuario,
            'fecha_inicio'  => $fecha_inicio,
            'fecha_fin'     => $fecha_fin,
            'monto_inicial' => $monto_inicial,
            'monto_final'   => $monto_final,
            'total_ventas'  => $total_ventas,
            'estado'        => $estado
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Caja aperturada correctamente',
            'type'      => 'success'
        ]);
    }

    public function close(Request $request)
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

        $id                 = trim($request->input('id'));
        $cash               = ArchingCash::where('id', $id)->first();

        if($cash->idusuario != Auth::user()['id'] && (User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name != "SUPERADMIN" && 
            User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name != "ADMIN"))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Esta apertura de caja no le pertenece',
                'type'      => 'warning'
            ]);
            return;
        }

        if($cash->estado == 2)
        {
            ArchingCash::where('id', $id)->update([
                'estado'        => 1,
            ]);
        
            echo json_encode([
                'status'    => true,
                'msg'       => 'La caja está abierta nuevamente',
                'type'      => 'success'
            ]);
            return;
        }

        $monto_final        = Billing::where('idcaja', $id)->where('estado_cpe', 1)->where('idtipo_comprobante', '!=', 6)->where('idusuario', $cash->idusuario)->sum('total');
         
        ArchingCash::where('id', $id)->update([
            'estado'        => 2,
            'fecha_fin'     => date('Y-m-d'),
            'monto_final'   => ($monto_final + $cash->monto_inicial)
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Caja cerrada correctamente',
            'type'      => 'success'
        ]);
    }

    public function get_detail_cash(Request $request)
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

        $id         = (int) $request->input('id');
        echo json_encode([
            'status' => true,
            'id'     => $id
        ]);
    }

    public function get_detail_cashes(Request $request)
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
        $id             = $request->input('id');
        $b_f            = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v            = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f            = json_encode($b_f);
        $n_v            = json_encode($n_v);
        $billings       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        
        //dd($billings);
        return Datatables()
            ->of($billings)
            ->addColumn('cliente', function($billings){
                $cliente  = $billings["nombre_cliente"];
                return $cliente;
            })
            ->addColumn('tipo_documento', function($billings){
                return TypeDocument::find($billings["idtipo_comprobante"])->descripcion;;
            })
            ->addColumn('documento', function($billings){
                $documento  = $billings["serie"] . '-' . $billings["correlativo"];
                return $documento;
            })
            ->addColumn('fecha', function($billings){
                $fecha_emision = date('d-m-Y', strtotime($billings["fecha_emision"]));
                return $fecha_emision;
            })
            ->rawColumns(['fecha', 'documento', 'tipo_documento', 'cliente'])
            ->make(true);
    }

    public function get_summary(Request $request)
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
        $id                 = $request->input('id');
        $cash               = ArchingCash::where('id', $id)->first();
        
        $monto_inicial      = $cash->monto_inicial;
        $b_f                = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v                = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f                = json_encode($b_f);
        $n_v                = json_encode($n_v);
        $billings           = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $cantidad_ventas    = 0; //count($billings);
        $monto_ventas       = 0;
        $monto_ventas2      = 0;
        $idusuario          = $cash->idusuario;
        $idcaja             = $cash->idcaja;
        $idarqueocaja       = $cash->id;
        $billing_filter     = $billings;

        // foreach($billings as $billing)
        // {
        //     $monto_ventas += $billing["total"];
        // }

        // Bills
        $bills              = DB::select("SELECT sum(monto) as monto, idpurchase_description, 
                            purchase_descriptions.descripcion as gasto
                            FROM bills
                            INNER JOIN purchase_descriptions ON bills.idpurchase_description = purchase_descriptions.id
                            WHERE idcaja = $idcaja AND idusuario = $idusuario AND idarqueocaja = $idarqueocaja
                            GROUP BY idpurchase_description, purchase_descriptions.descripcion");

        $sum_bills          = Bill::where('idcaja', $idcaja)->where('idusuario', $idusuario)->where('idarqueocaja', $idarqueocaja)->sum('monto');
        $html_bills         = '';
        $bills_empty        = null;
        if(count($bills) == 0)
        {
            $html_bills     .= 'S/' . number_format($sum_bills, 2, ".", "");
            $bills_empty    = true;
        }
        else
        {
            foreach($bills as $bill)
            {
                $html_bills .= '<div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"></p>
                                <h6 class="mb-0"><span style="font-size: 13px;">'. $bill->gasto .': </span><span>S/'. number_format($bill->monto, 2, ".", ",") .'</span></h6>
                                </div>';
            }
            $bills_empty    = false;
        }
        // Sales
        $sales              = DB::select("SELECT SUM(billings.total) as monto, detail_payments.idpago, pay_modes.descripcion as tipo_pago, COUNT(billings.id) as cantidad
                            FROM detail_payments
                            INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                            INNER JOIN billings ON detail_payments.idfactura = billings.id
                            WHERE billings.idcaja = $idarqueocaja AND (detail_payments.idtipo_comprobante = 1 OR detail_payments.idtipo_comprobante = 2) AND billings.estado_cpe = 1
                            GROUP BY detail_payments.idpago, pay_modes.descripcion");
                            
        $sales2             = DB::select("SELECT SUM(sale_notes.total) as monto, detail_payments.idpago, pay_modes.descripcion as tipo_pago, COUNT(sale_notes.id) as cantidad
                            FROM detail_payments
                            INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                            INNER JOIN sale_notes ON detail_payments.idfactura = sale_notes.id
                            WHERE sale_notes.idcaja = $idarqueocaja AND detail_payments.idtipo_comprobante = 7
                            GROUP BY detail_payments.idpago, pay_modes.descripcion");
        //dd($sales, $sales2, $idarqueocaja);
        //$sales = [];
        /*for ($i=0; $i < count($sales1); $i++) {
            $sales[$i] = new \stdClass();
            foreach ($sales1[$i] as $key => $value) {
                for ($j=0; $j < count($sales2); $j++) {
                    foreach ($sales2[$i] as $key2 => $value2) {
                        if ($key == $key2) {
                            if($key == "monto" || $key == "cantidad"){
                                $sales[$i]->$key = (int) $value + $value2;
                            }else{
                                $sales[$i]->$key =  $value;
                            }
                        }
                    }
                }
            }
        }*/
        
        foreach ($sales2 as $elemento2) {
            $encontrado = false;
            foreach ($sales as &$elemento1) {
                if ($elemento1->tipo_pago === $elemento2->tipo_pago) {
                    $elemento1->monto = (double)$elemento1->monto + (double)$elemento2->monto;
                    $elemento1->cantidad = (double)$elemento1->cantidad + (double)$elemento2->cantidad;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $sales[] = $elemento2;
            }
        }
        //return $array1;
        
        //dd($sales);               
        
        $html_sales         = '';
        $html_quantity      ='';
        $sales_empty        = null;
        $html_download      = '<a class="btn btn-primary download_ticket" data-id="'.$id.'" href="javascript:void(0);">
                                    <span>Descargar Resumen ticket</span>
                                </a>
                                <a class="btn btn-primary download" data-id="'.$id.'" href="javascript:void(0);">
                                    <span>Descargar Resumen</span>
                                </a>
                                <button type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">Cerrar
                                </button>
                                ';

        if(count($sales) == 0)
        {
            $html_sales     .= 'S/0.00';
            $sales_empty    = true;
        }
        else
        {
            //dd(Billing::first());
            foreach($sales as $sale)
            {
                $cantidadDolar = Billing::select('billings.total')
                ->join('detail_payments', function ($join) use ($sale) {
                    $join->on('billings.id', '=', 'detail_payments.idfactura')
                         ->where('detail_payments.idpago', '=', $sale->idpago);
                })
                ->where('billings.idmoneda', '=', 2)
                ->sum('detail_payments.monto');
                
                $cantidadSoles = Billing::select('billings.total')
                ->join('detail_payments', function ($join) use ($sale) {
                    $join->on('billings.id', '=', 'detail_payments.idfactura')
                        ->where('detail_payments.idpago', '=', $sale->idpago);
                })
                ->where('billings.idmoneda', '=', 1)
                ->sum('detail_payments.monto');
                
                $html_sales .= '<div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0"></p>
                                    <h6 class="mb-0"><span style="font-size: 13px;">'. $sale->tipo_pago .': </span><span>S/'. number_format($cantidadSoles, 2, ".", ",") .'</span> - <span>$'. number_format($cantidadDolar, 2, ".", ",") .'</span></h6>
                                </div>';

                $html_quantity .= '<div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"></p>
                                <h6 class="mb-0"><span style="font-size: 13px;">'. $sale->tipo_pago .': </span><span>'. $sale->cantidad .'</span></h6>
                            </div>';
                //$monto_ventas += number_format($sale->monto, 2, ".", ",");
                $monto_ventas += $cantidadSoles;
                $monto_ventas2 += $cantidadDolar;
                $cantidad_ventas += $sale->cantidad;
            }

            $html_quantity .= '<div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"></p>
                                <h6 class="mb-0"><span style="font-size: 13px;">total: </span><span>'. $cantidad_ventas .'</span></h6>
                            </div>';

            $sales_empty    = false;
        }
        $subTotal           = $monto_ventas + $monto_inicial - $sum_bills;
        $total              = number_format($subTotal, 2, ".", ",");
        $monto_ventas2      = number_format($monto_ventas2, 2, ".", ",");

        echo json_encode([
            'status'            => true,
            'monto_inicial'     => $monto_inicial,
            'cantidad_ventas'   => $cantidad_ventas,
            'suma_gastos'       => $sum_bills,
            'html_bills'        => $html_bills,
            'bills_empty'       => $bills_empty,
            'html_sales'        => $html_sales,
            'sales_empty'       => $sales_empty,
            'monto_ventas'      => number_format($monto_ventas, 2, ".", ","),
            'monto_ventas2'     => $monto_ventas2,
            'total'             => $total,
            'html_quantity'     => $html_quantity,
            'download_button'   => $html_download
        ]);
    }

    public function download($id)
    {
        $b_f                    = DB::select("CALL get_list_billings_cash(?)", [$id]);
        $n_v                    = DB::select("CALL get_list_sale_notes_cash(?)", [$id]);
        $b_f                    = json_encode($b_f);
        $n_v                    = json_encode($n_v);
        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
        $data["monto_ventas"]   = 0;
        $data["pagos"]          = [];
        foreach($data["billings"] as $billing)
        {
            $data["monto_ventas"]  += $billing["total"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante, 
                                    pay_modes.descripcion as tipo_pago, referencia, cuenta
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $id AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, idfactura, idtipo_comprobante, referencia, cuenta, pay_modes.descripcion");
        }

        $data["cash"]           = ArchingCash::where('id', $id)->first();
        $data["sum_bills"]      = Bill::where('idcaja', $data["cash"]->idcaja)->where('idusuario', $data["cash"]->idusuario)->where('idarqueocaja', $id)->sum('monto');
        $data["total"]          = number_format(($data["monto_ventas"]) + ($data["cash"]->monto_inicial - $data["sum_bills"]), 2, ".", "");
        $data["business"]       = Business::where('id', 1)->first();
        $data["name"]           = $data["business"]->ruc . '-' . $data["cash"]->fecha_inicio;
        $pdf                    = PDF::loadView('admin.arching_cashes.report', $data)->setPaper('A4', 'landscape');

        //return $data;
        return $pdf->download($data["name"] . '.pdf');
    }

    public function download_resumen($id)
    {
        $sales  = DB::select("SELECT SUM(billings.total) as monto, pay_modes.descripcion as tipo_pago, COUNT(billings.id) as cant, billings.idmoneda as moneda
                FROM detail_payments
                INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                INNER JOIN billings ON detail_payments.idfactura = billings.id
                WHERE billings.idcaja = $id AND (detail_payments.idtipo_comprobante = 1 OR detail_payments.idtipo_comprobante = 2) AND billings.estado_cpe = 1
                GROUP BY detail_payments.idpago, pay_modes.descripcion, billings.idmoneda");
                            
        $sales2 = DB::select("SELECT SUM(sale_notes.total) as monto, pay_modes.descripcion as tipo_pago, COUNT(sale_notes.id) as cant, sale_notes.idmoneda as moneda
                FROM detail_payments
                INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                INNER JOIN sale_notes ON detail_payments.idfactura = sale_notes.id
                WHERE sale_notes.idcaja = $id AND detail_payments.idtipo_comprobante = 7
                GROUP BY detail_payments.idpago, pay_modes.descripcion, sale_notes.idmoneda");
                
        foreach ($sales2 as $elemento2) {
            $encontrado = false;
            foreach ($sales as &$elemento1) {
                if ($elemento1->tipo_pago === $elemento2->tipo_pago && $elemento1->moneda === $elemento2->moneda) {
                    $elemento1->monto = (double)$elemento1->monto + (double)$elemento2->monto;
                    $elemento1->cant = (double)$elemento1->cant + (double)$elemento2->cant;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $sales[] = $elemento2;
            }
        }
        
        foreach($sales as $sale){
            $sale->moneda = Currency::find($sale->moneda)->codigo;
        }
                            
        $data["pagos"]          = $sales;
        $data["business"]       = Business::where('id', 1)->first();
        $data["cash"]           = ArchingCash::where('id', $id)->first();
        $data["name"]           = $data["business"]->ruc . '-' . $data["cash"]->fecha_inicio;
        
        $pdf = PDF::loadView('admin.arching_cashes.resumen', $data)->setPaper('A4', 'landscape');

        //return $data["pagos"];
        return $pdf->download($data["name"] . '.pdf');
    }
    
    public function download_resumen_ticket($id)
    {
        $customPaper                = array(0, 0, 630.00, 210.00);
        $sales  = DB::select("SELECT SUM(billings.total) as monto, pay_modes.descripcion as tipo_pago, COUNT(billings.id) as cant, billings.idmoneda as moneda
                FROM detail_payments
                INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                INNER JOIN billings ON detail_payments.idfactura = billings.id
                WHERE billings.idcaja = $id AND (detail_payments.idtipo_comprobante = 1 OR detail_payments.idtipo_comprobante = 2) AND billings.estado_cpe = 1
                GROUP BY detail_payments.idpago, pay_modes.descripcion, billings.idmoneda");
                            
        $sales2 = DB::select("SELECT SUM(sale_notes.total) as monto, pay_modes.descripcion as tipo_pago, COUNT(sale_notes.id) as cant, sale_notes.idmoneda as moneda
                FROM detail_payments
                INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                INNER JOIN sale_notes ON detail_payments.idfactura = sale_notes.id
                WHERE sale_notes.idcaja = $id AND detail_payments.idtipo_comprobante = 7
                GROUP BY detail_payments.idpago, pay_modes.descripcion, sale_notes.idmoneda");
                
        foreach ($sales2 as $elemento2) {
            $encontrado = false;
            foreach ($sales as &$elemento1) {
                if ($elemento1->tipo_pago === $elemento2->tipo_pago && $elemento1->moneda === $elemento2->moneda) {
                    $elemento1->monto = (double)$elemento1->monto + (double)$elemento2->monto;
                    $elemento1->cant = (double)$elemento1->cant + (double)$elemento2->cant;
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $sales[] = $elemento2;
            }
        }
        
        foreach($sales as $sale){
            $sale->moneda = Currency::find($sale->moneda)->codigo;
        }
                            
        $data["pagos"]          = $sales;
        $data["business"]       = Business::where('id', 1)->first();
        $data["cash"]           = ArchingCash::where('id', $id)->first();
        $data["ingresos"]       = DetailPayment::where('idcaja', $id)->sum('monto');
        $data["egresos"]        = Bill::where('idcaja', $id)->sum('monto');
        $data["name"]           = $data["business"]->ruc . '-' . $data["cash"]->fecha_inicio;
        $data["factura"]        = Billing::where('idtipo_comprobante', 1)->get();
        $data["boleta"]         = Billing::where('idtipo_comprobante', 2)->get();
        $data["nv"]             = SaleNote::where('idtipo_comprobante', 7)->get();
        $data["nc"]             = Billing::where('idtipo_comprobante', 6)->get();
        
        $pdf = PDF::loadView('admin.arching_cashes.resumen-ticket', $data)->setPaper($customPaper, 'landscape');
        $pdf->save(public_path('files/summary/' . $data["name"] . '.pdf'));
        
        //return $data["name"].'pdf';
        echo json_encode([
            'status'        => true,
            'pdf'            => $data["name"].'.pdf',
        ]);
    }
}
