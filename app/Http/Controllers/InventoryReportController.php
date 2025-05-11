<?php

namespace App\Http\Controllers;

use App\Exports\InventoryReportProduct;
use App\Models\Business;
use App\Models\StockProduct;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class InventoryReportController extends Controller
{
    public function products()
    {
        $data["warehouses"]        = Warehouse::get();
        return view('admin.reports.inventories.products.home', $data);
    }

    public function search_products(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $warehouse             = $request->input('warehouse');
        if ($warehouse == '0') {
            $stock_products = StockProduct::get();
            $products       = DB::select("CALL get_list_products_data()");
        } else {
            $stock_products = StockProduct::where('idalmacen', $warehouse)->get();
            $products       = DB::table('products')
                                ->join('stock_products', 'stock_products.idproducto', '=', 'products.id')
                                ->where('stock_products.idalmacen', '=', $warehouse)
                                ->select('products.*', 'stock_products.cantidad')
                                ->get();
        }

        $warehouses     = Warehouse::get();
        $quantity       = count($products);

        echo json_encode([
            "status"            => true,
            "products"          => $products,
            "quantity"          => $quantity,
            'warehouses'        => $warehouses,
            'stock_products'    => $stock_products,
        ]);
    }

    public function export_products(Request $request)
    {
        $data["productos"]              = DB::select("CALL get_list_products_data()");
        $data["quantity"]               = count($data["productos"]);
        $data["business"]               = Business::where('id', 1)->first();
        $data['ruc']                    = $data["business"]->ruc;
        $data['nombre_comercial']       = $data["business"]->nombre_comercial;
        $data['user_name']              = Auth::user()->nombres;

        $warehouse                      = $request->input('warehouse');
        $data["warehouse_selected"]     = null;
        if ($warehouse) {
            $data["warehouse"]          = Warehouse::where('id', $warehouse)->first();
            $data["stock_products"]    = StockProduct::where('idalmacen', $warehouse)->get();
            $data["warehouse_selected"] = true;
        } else {
            $data["warehouses"]         = Warehouse::get();
            $data["stock_products"]     = StockProduct::get();
        }

        $export_pdf                     = $request->input('export_pdf');

        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.inventories.products.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Productos ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            $nombre_excel  = 'Reporte de Productos ' . date('d-m-Y H-i-s') . '.xlsx';
            return Excel::download(new InventoryReportProduct($data), $nombre_excel);
        }
    }
}
