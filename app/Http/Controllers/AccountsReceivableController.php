<?php

namespace App\Http\Controllers;

use App\Exports\DownloadProduct;
use App\Exports\KardexExport;
use App\Models\Business;
use App\Models\CreditQuote;
use App\Models\DetailVentaGeneral;
use App\Models\Kardex;
use App\Models\PayMode;
use App\Models\Product;
use App\Models\TypeDocument;
use App\Models\User;
use App\Models\VentaCreditoCuota;
use App\Models\VentaGeneral;
use App\Models\Warehouse;
use App\Models\Credit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;

class AccountsReceivableController extends Controller
{
    public function index() {
        $data["users"]        = User::all();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->get();
        $data["warehouses"]      = Warehouse::all();

//         $credits = VentaGeneral::select('ventas_generales.*', 'users.nombres as vendedor', 'clients.nombres as cliente')
//            ->with('creditQuote')
//            ->rightJoin('type_documents', 'ventas_generales.idtipo_comprobante', '=', 'type_documents.id')
//            ->leftJoin('clients' , 'ventas_generales.idcliente', '=', 'clients.id')
//            ->leftJoin('users' , 'ventas_generales.idusuario', '=', 'users.id')
//            ->where('ventas_generales.condicion_pago', 3)
//            ->orderBy('id', 'desc')
//            ->get();
//        return $credits[0]->creditQuote;

        return view('admin.reports.accounts_receivable.home', $data);
    }

    public function filter(Request $request)
    {

        parse_str($request->filters, $filter);
        //dd($filter);
        $fecha_inicial      = $filter['fecha_inicial'];
        $fecha_final        = $filter['fecha_final'];
        $user               = $filter['user'] ?? null;
        $warehouse          = $filter['warehouse'] ?? null;
        $document           = $filter['document'] ?? null;

        $role = User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name;
        //$role = $user->roles->first();

        //dd($fecha_inicial);

        if ($role != "SUPERADMIN" && $role != "ADMIN") {
            $credits = VentaGeneral::where("userId", auth()->user()->id)->whereBetween('created_at', [$fecha_inicial, $fecha_final])->orderBy('id', 'desc')->get();
        }else{

            $credits = VentaGeneral::select('ventas_generales.*', 'users.nombres as vendedor', 'clients.nombres as cliente')
            ->with('creditQuote')
            //->leftJoin('warehouses' , 'credits.warehouseid', '=', 'warehouses.id')
            ->rightJoin('type_documents', 'ventas_generales.idtipo_comprobante', '=', 'type_documents.id')
            ->leftJoin('clients' , 'ventas_generales.idcliente', '=', 'clients.id')
            ->leftJoin('users' , 'ventas_generales.idusuario', '=', 'users.id')
            ->where('ventas_generales.condicion_pago', 3)
            ->whereBetween('ventas_generales.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('id', 'desc')
            ->get();
            //dd($credits);

            if (!empty($user)) {
                $credits = $credits->where('idusuario', $user);
            }

            // if (!empty($warehouse)) {
            //     $credits = $credits->where('warehouseId', $warehouse);
            // }

            if (!empty($document)) {
                $credits = $credits->where('documentTypeId', $document);
            }
        }

        //dd($credits[0]->credit_quote);

        return Datatables()
                ->of($credits)
                ->addColumn('comprobante', function($credits){
                    return $credits->serie.'-'.$credits->correlativo;
                })
                ->addColumn('deuda', function($credits){
                    $deuda = $credits->total;

                    if (!empty($credits->creditQuote)) {
                        foreach ($credits->creditQuote as $quote) {
                            $deuda -= $quote->total;
                        }
                    }

                    return number_format($deuda, 2);
                })
                ->addColumn('retraso', function($credits){
                    $fecha1 = new DateTime(); // Fecha actual
                    $fecha2 = new DateTime();

                    if (!empty($credits->credit_quote)) {
                        foreach ($credits->credit_quote as $quote) {
                            $fecha2 = new DateTime($quote->created_at); // Convertir la fecha de la cotización
                        }
                    }else {
                        $fecha2 = new DateTime($credits->created_at);
                    }

                    $diferencia = abs($fecha1->getTimestamp() - $fecha2->getTimestamp()); // Diferencia en segundos

                    // Calcular el retraso en días
                    $retraso = ceil($diferencia / (60 * 60 * 24)); // Convertir a días
                    return $retraso;
                })
                ->addColumn('acciones', function($credits){
                    $id     = $credits->id;
                    $deuda = $credits->total;

                    if (!empty($credits->creditQuote)) {
                        foreach ($credits->creditQuote as $quote) {
                            $deuda -= (double)$quote->total;
                        }
                    }

                    $btn    = '<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail" data-id="'.$id.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                        <span> Visualizar</span>
                                    </a>
                                    <a class="dropdown-item btn-quotes" data-id="'.$id.'" data-deuda="'.number_format($deuda, 2).'" data-total="'.$credits->total.'" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M535 41c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l64 64c4.5 4.5 7 10.6 7 17s-2.5 12.5-7 17l-64 64c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l23-23L384 112c-13.3 0-24-10.7-24-24s10.7-24 24-24l174.1 0L535 41zM105 377l-23 23L256 400c13.3 0 24 10.7 24 24s-10.7 24-24 24L81.9 448l23 23c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L7 441c-4.5-4.5-7-10.6-7-17s2.5-12.5 7-17l64-64c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9zM96 64l241.9 0c-3.7 7.2-5.9 15.3-5.9 24c0 28.7 23.3 52 52 52l117.4 0c-4 17 .6 35.5 13.8 48.8c20.3 20.3 53.2 20.3 73.5 0L608 169.5 608 384c0 35.3-28.7 64-64 64l-241.9 0c3.7-7.2 5.9-15.3 5.9-24c0-28.7-23.3-52-52-52l-117.4 0c4-17-.6-35.5-13.8-48.8c-20.3-20.3-53.2-20.3-73.5 0L32 342.5 32 128c0-35.3 28.7-64 64-64zm64 64l-64 0 0 64c35.3 0 64-28.7 64-64zM544 320c-35.3 0-64 28.7-64 64l64 0 0-64zM320 352a96 96 0 1 0 0-192 96 96 0 1 0 0 192z"/></svg>
                                        <span> Cuotas</span>
                                    </a>
                                </div>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['comprobante', 'deuda', 'retraso', 'acciones'])
                ->make(true);
    }

    public function filterQuotes(Request $request)
    {
        $quotes = CreditQuote::where("creditId", $request->id)->get();

        return Datatables()
            ->of($quotes)
            ->addColumn('pay_method', function($quotes){
                return PayMode::find($quotes->payId)->descripcion;
            })
            ->addColumn('date', function($quotes){
                return $quotes->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('bank_account', function($quotes){
                return "Banco Test (cambiar)";
            })
            ->addColumn('received_payment', function($quotes){
                return "Si";
            })
            ->addColumn('print', function($quotes){
                return '<a href="javascript:void(0);" class="btn btn-primary btn-sm btn-print" data-id="'.$quotes->id.'" title="Imprimir">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 384 512"><path d="M320 464c8.8 0 16-7.2 16-16l0-288-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16l256 0zM0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 448c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64z"/></svg>
                </a>';
            })
            ->addColumn('acciones', function($quotes){
                return '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-delete" data-id="'.$quotes->id.'" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512"><path d="M170.5 51.6L151.5 80l145 0-19-28.4c-1.5-2.2-4-3.6-6.7-3.6l-93.7 0c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80 368 80l48 0 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-8 0 0 304c0 44.2-35.8 80-80 80l-224 0c-44.2 0-80-35.8-80-80l0-304-8 0c-13.3 0-24-10.7-24-24S10.7 80 24 80l8 0 48 0 13.8 0 36.7-55.1C140.9 9.4 158.4 0 177.1 0l93.7 0c18.7 0 36.2 9.4 46.6 24.9zM80 128l0 304c0 17.7 14.3 32 32 32l224 0c17.7 0 32-14.3 32-32l0-304L80 128zm80 64l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0l0 208c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-208c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>
                </a>';
            })
            ->rawColumns(['pay_method', 'bank_account', 'received_payment', 'print', 'acciones'])
            ->make(true);
    }

    public function getDetails(Request $request){
        $id = $request->input('id');

        $details = VentaGeneral::select('ventas_generales.*', 'clients.nombres as cliente', 'users.nombres as vendedor',
            'venta_creditos_cuotas.valor_cuotas')
            ->withSum('creditQuote as payed', 'total')
            ->with('detailVentasGenerales')
            //->join('detail_ventas_generales','ventas_generales.id', '=','detail_ventas_generales.idventa_general')
            //->join('warehouses', 'ventas_generales.idalmacen', '=', 'warehouses.id')
            ->join('clients', 'ventas_generales.idcliente', '=', 'clients.id')
            ->join('users', 'ventas_generales.idusuario', '=', 'users.id')
            ->join('venta_creditos_cuotas', 'ventas_generales.id', '=', 'venta_creditos_cuotas.idventa_general')
            ->where('ventas_generales.id', $id)
            ->get();

        $products = '';

        foreach (DetailVentaGeneral::with('product', 'warehouse')->where('idventa_general', $id)->get() as $item){
            $products .= '<div class="col-12 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="'.$item['product']->descripcion.'" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Precio Unitario</label>
                        <input type="text" class="form-control" value="'.$item->precio_unitario.'" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Cantidad</label>
                        <input type="text" class="form-control" value="'.(int)$item->cantidad.'" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Precio Total</label>
                        <input type="text" class="form-control" value="'.$item->precio_total.'" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Almacén de Despacho</label>
                        <input type="text" class="form-control" value="'.$item['warehouse']->descripcion.'" disabled>
                    </div><br><br>';
        }

        echo json_encode([
            'status'    => true,
            'details'   => $details,
            'products'  => $products,
        ]);
    }

    public function getAmount(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
            return;
        }
        $amount = VentaCreditoCuota::where('idventa_general', $request->input('id'))->first()->valor_cuotas;

        echo json_encode([
            'status'    => true,
            'amount'   => $amount,
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

        $idCredit       = $request->input('id');
        $pay_method     = $request->input('pay_method');
        $account        = $request->input('account');
        $amount         = $request->input('amount');

        CreditQuote::create([
            'creditId'      => $idCredit,
            'cuentaId'      => $account,
            'payId'         => $pay_method,
            'total'         => $amount
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro insertado correctamente',
            'type'      => 'success'
        ]);
    }

    public function download(Request $request)
    {
        $fecha_inicial      = $request->input('fecha_inicial');
        $fecha_final        = $request->input('fecha_final');
        $user               = $request->input('user');
        $warehouse          = $request->input('warehouse');
        $document           = $request->input('document');

        $data['kardex'] = Kardex::whereBetween('created_at', [$fecha_inicial, $fecha_final])->orderBy('id', 'desc')->get();

        if (!empty($user)) {
            $data['kardex'] = $data['kardex']->where('userId', $user);
        }

        if (!empty($warehouse)) {
            $data['kardex'] = $data['kardex']->where('warehouseId', $warehouse);
        }

        if (!empty($document)) {
            $data['kardex'] = $data['kardex']->where('documentTypeId', $document);
        }

        $export_pdf                 = $request->input('export_pdf');
        $nombre_excel               = 'kardex ' . date('d-m-Y H-i-s') . '.xlsx';

        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.kardex.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('kardex ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new KardexExport($data), $nombre_excel);
        }
    }

    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id            = $request->input('id');
        $cuota = CreditQuote::find($id)->total;
        CreditQuote::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado con éxito',
            'type'      => 'success',
            'cuota'      => $cuota,
        ]);
    }
}
