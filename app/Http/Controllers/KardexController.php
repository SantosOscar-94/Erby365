<?php

namespace App\Http\Controllers;

use App\Exports\DownloadProduct;
use App\Exports\KardexExport;
use App\Models\Business;
use App\Models\Kardex;
use App\Models\Product;
use App\Models\TypeDocument;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KardexController extends Controller
{
    public function index() {
        $data["users"]        = User::all();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        $data["warehouses"]      = Warehouse::all();

        return view('admin.reports.kardex.home', $data);
    }

    public function filter(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $fecha_inicial      = $request->input('fecha_inicial');
        $fecha_final        = $request->input('fecha_final');
        $user               = $request->input('user');
        $warehouse          = $request->input('warehouse');
        $document           = $request->input('document');

        $role = User::with('roles')->where('id', auth()->user()->id)->first()->roles[0]->name;
        //$role = $user->roles->first();

        //dd($role);

        if ($role != "SUPERADMIN" && $role != "ADMIN") {
            $kardex = Kardex::where("userId", auth()->user()->id)->whereBetween('created_at', [$fecha_inicial, $fecha_final])->orderBy('id', 'desc')->get();

            echo json_encode([
                "status"            => true,
                "kardex"            => $kardex,
                'quantity'          => count($kardex)
            ]);
            die();
        }

        // $kardex = Kardex::whereBetween('created_at', [$fecha_inicial, $fecha_final])->orderBy('id', 'desc')->get();

        $kardex = DB::table('kardex')
        ->leftJoin('warehouses' , 'kardex.warehouseid', '=', 'warehouses.id')
        ->rightJoin('type_documents'    , 'kardex.documentTypeId', '=', 'type_documents.id')
        ->leftJoin('users' , 'kardex.userId', '=', 'users.id')
        ->select('kardex.*', 'warehouses.descripcion as nombre_tienda', 'type_documents.descripcion as tipo_documento', 'users.nombres as vendedor')
        ->whereBetween('kardex.created_at', [$fecha_inicial, $fecha_final])
        ->orderBy('kardex.id', 'desc')
        ->get();

        if (!empty($user)) {
            $kardex = $kardex->where('userId', $user);
        }

        if (!empty($warehouse)) {
            $kardex = $kardex->where('warehouseId', $warehouse);
        }

        if (!empty($document)) {
            $kardex = $kardex->where('documentTypeId', $document);
        }

        echo json_encode([
            "status"            => true,
            "kardex"            => $kardex,
            'quantity'          => count($kardex)
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
}
