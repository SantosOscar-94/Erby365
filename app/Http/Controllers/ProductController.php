<?php

namespace App\Http\Controllers;

use App\Exports\DownloadProduct;
use App\Imports\ProductImport;
use App\Models\Business;
use App\Models\IgvTypeAffection;
use App\Models\Kardex;
use App\Models\Product;
use App\Models\StockProduct;
use App\Models\Unit;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $data["units"]             = Unit::where('estado', 1)->get();
        $data['type_inafects']     = IgvTypeAffection::where('estado', 1)->get();
        $data["warehouses"]        = Warehouse::get();
        return view('admin.products.list', $data);
    }

    public function get()
    {
        $products     = DB::select("CALL get_list_products_data()");
        return Datatables()
            ->of($products)
            ->addColumn('enable_tag', function ($products) {
                if ($products->enable) {
                    return '<span class="badge bg-success text-white">Habilitado</span>';
                } else {
                    return '<span class="badge bg-danger text-white">Inhabilitado</span>';
                }
            })
            ->addColumn('acciones', function ($products) {
                $id     = $products->id;
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item btn-view" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye mr-50 menu-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            <span>  Ver detalle</span>
                                        </a>
                                        <a class="dropdown-item btn-duplicate" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512"><path d="M288 448L64 448l0-224 64 0 0-64-64 0c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l224 0c35.3 0 64-28.7 64-64l0-64-64 0 0 64zm-64-96l224 0c35.3 0 64-28.7 64-64l0-224c0-35.3-28.7-64-64-64L224 0c-35.3 0-64 28.7-64 64l0 224c0 35.3 28.7 64 64 64z"/></svg>
                                            <span>  Duplicar</span>
                                        </a>
                                        <a class="dropdown-item btn-detail" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-enable" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg width="14" height="14"" viewBox="0 0 640 512"><path d="M256 48c0-26.5 21.5-48 48-48L592 0c26.5 0 48 21.5 48 48l0 416c0 26.5-21.5 48-48 48l-210.7 0c1.8-5 2.7-10.4 2.7-16l0-242.7c18.6-6.6 32-24.4 32-45.3l0-32c0-26.5-21.5-48-48-48l-112 0 0-80zM571.3 347.3c6.2-6.2 6.2-16.4 0-22.6l-64-64c-6.2-6.2-16.4-6.2-22.6 0l-64 64c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L480 310.6 480 432c0 8.8 7.2 16 16 16s16-7.2 16-16l0-121.4 36.7 36.7c6.2 6.2 16.4 6.2 22.6 0zM0 176c0-8.8 7.2-16 16-16l352 0c8.8 0 16 7.2 16 16l0 32c0 8.8-7.2 16-16 16L16 224c-8.8 0-16-7.2-16-16l0-32zm352 80l0 224c0 17.7-14.3 32-32 32L64 512c-17.7 0-32-14.3-32-32l0-224 320 0zM144 320c-8.8 0-16 7.2-16 16s7.2 16 16 16l96 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-96 0z"/></svg>
                                            <span>  Habilitar/Deshabilitar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['acciones', 'enable_tag'])
            ->make(true);
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

        $codigo_interno             = trim($request->input('codigo_interno'));
        $codigo_interno             = empty($codigo_interno) ? NULL : $codigo_interno;
        $codigo_barras              = trim($request->input('codigo_barras'));
        $idunidad                   = $request->input('idunidad');
        $descripcion                = trim($request->input('descripcion'));
        $marca                      = trim($request->input('marca'));
        $presentacion               = trim($request->input('presentacion'));
        $operacion                  = $request->input('operacion'); // Si es 1 va IGV
        $impuesto                   = 0;
        $precio_compra              = $request->input('precio_compra');
        $precio_venta               = $request->input('precio_venta');
        $precio_venta_por_mayor     = $request->input('precio_venta_por_mayor');
        $precio_venta_distribuidor  = $request->input('precio_venta_distribuidor');
        $check_stock                = $request->input('check_stock');
        $fecha_vencimiento          = $request->input('fecha_vencimiento');
        $buscar_codigo              = Product::where('codigo_interno', $codigo_interno)->where('codigo_interno', '!=', NULL)->first();
        $igv                        = NULL;
        $idstores                   = ($request->input('id') != 0) ? $request->input('id') : $request->input('idalmacen');
        $cantidad                   = $request->input('cantidad');
        $opcion                     = $request->input('opcion');

        if (!empty($buscar_codigo)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Código interno existente',
                'type'      => 'warning'
            ]);
            return;
        }
        /**
         * 1 => Gravada
         * 10 => Exonerada
         * 30 => Inafecta
         */
        if ($operacion == '1')
            $igv            = 18;
        else
            $igv            = 0;

        Product::create([
            'codigo_sunat'              => '00000000',
            'codigo_interno'            => $codigo_interno,
            'codigo_barras'             => $codigo_barras,
            'descripcion'               => mb_strtoupper($descripcion),
            'marca'                     => mb_strtoupper($marca),
            'presentacion'              => mb_strtoupper($presentacion),
            'idunidad'                  => $idunidad,
            'idcodigo_igv'              => $operacion,
            'igv'                       => $igv,
            'precio_compra'             => $precio_compra,
            'precio_venta'              => $precio_venta,
            'precio_venta_por_mayor'    => $precio_venta_por_mayor,
            'precio_venta_distribuidor' => $precio_venta_distribuidor,
            'impuesto'                  => ($operacion == '1') ? 1 : 0,
            'fecha_vencimiento'         => $fecha_vencimiento,
            'opcion'                    => $opcion
        ]);
        $idproducto             = Product::latest('id')->first()['id'];

        //dd($idstores);
        foreach ($idstores as $i => $item) {

            if ($cantidad[$i] == '0') {
                continue;
            }

            StockProduct::create([
                'idproducto'    => $idproducto,
                'idalmacen'     => $item,
                'cantidad'      => $cantidad[$i],
                //'ingreso'      => $cantidad[$i]
            ]);

            Kardex::create([
                'documentTypeId'    => 26,
                'userId'            => auth()->user()->id,
                'warehouseId'       => $item,
                'document'          => 00000, //actualizar a código del sunat
                'product'           => mb_strtoupper($descripcion),
                'cant1'             => $cantidad[$i],
                'price1'            => $precio_compra,
                'total1'            => ($precio_compra * $cantidad[$i]),
                'cant3'             => $cantidad[$i],
                'price3'            => $precio_compra,
                'total3'            => ($precio_compra * $cantidad[$i]),
                'tipo'              => 'Entrada'
            ]);
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro agregado correctamente',
            'type'      => 'success'
        ]);
    }

    public function detail(Request $request)
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

        $id                 = $request->input('id');
        $product            = Product::where('id', $id)->first();
        $type_inafectos     = IgvTypeAffection::where('estado', 1)->get();
        $unidades           = Unit::where('estado', 1)->orderBy('id', 'DESC')->get();
        $warehouses         = [];
        $detail_stocks      = StockProduct::where('idproducto', $id)->get();
        foreach ($detail_stocks as $stock) {
            $warehouses[]   = Warehouse::where('id', $stock["idalmacen"])->first();
        }

        echo json_encode([
            'status'            => true,
            'product'           => $product,
            'type_inafectos'    => $type_inafectos,
            'unidades'          => $unidades,
            'warehouses'        => $warehouses,
            'detail_stocks'     => $detail_stocks
        ]);
    }

    public function store(Request $request)
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

        $id                 = $request->input('id');
        $codigo_interno     = $request->input('codigo_interno');
        $codigo_barras      = $request->input('codigo_barras');
        $idunidad           = $request->input('idunidad');
        $descripcion        = trim($request->input('descripcion'));
        $marca              = trim($request->input('marca'));
        $presentacion       = trim($request->input('presentacion'));
        $operacion          = $request->input('operacion'); // Si es 1 va IGV
        $impuesto           = 0;
        $precio_compra      = $request->input('precio_compra');
        $precio_venta       = $request->input('precio_venta');
        $precio_venta_por_mayor = $request->input('precio_venta_por_mayor');
        $precio_venta_distribuidor = $request->input('precio_venta_distribuidor');
        $fecha_vencimiento  = $request->input('fecha_vencimiento');
        $igv                = null;
        $idproducto         = $request->input('idproducto');
        $cantidad           = $request->input('cantidad');
        $almacenes          = $request->input('idalmacen');
        $opcion             = $request->input('opcion');

        if ($operacion == '1')
            $igv            = 18;
        else
            $igv            = 0;

        Product::where('id', $id)->update([
            'codigo_interno'    => $codigo_interno,
            'codigo_barras'     => $codigo_barras,
            'descripcion'       => mb_strtoupper($descripcion),
            'marca'             => mb_strtoupper($marca),
            'presentacion'      => mb_strtoupper($presentacion),
            'idunidad'          => $idunidad,
            'idcodigo_igv'      => $operacion,
            'igv'               => $igv,
            'precio_compra'     => $precio_compra,
            'precio_venta'      => $precio_venta,
            'precio_venta_por_mayor'      => $precio_venta_por_mayor,
            'precio_venta_distribuidor'      => $precio_venta_distribuidor,
            'impuesto'          => ($operacion == '1') ? 1 : 0,
            'fecha_vencimiento' => $fecha_vencimiento,
            'opcion'            => $opcion
        ]);

        // Update stock
        foreach ($almacenes as $i => $item) {
            $stock = StockProduct::where('idproducto', $id)->where('idalmacen', $item)->first();
            StockProduct::where('idproducto', $id)->where('idalmacen', $item)->update([
                'idproducto'    => $id,
                'idalmacen'     => $item,
                'cantidad'      => $cantidad[$i]
            ]);

            Kardex::create([
                'documentTypeId'    => 26,
                'userId'            => auth()->user()->id,
                'warehouseId'       => $item,
                'document'          => 00000, //actualizar a código del sunat
                'product'           => mb_strtoupper($descripcion),
                'cant1'             => $cantidad[$i],
                'price1'            => $precio_compra,
                'total1'            => ($precio_compra * $cantidad[$i]),
                'cant3'             => $cantidad[$i],
                'price3'            => $precio_compra,
                'total3'            => ($precio_compra * $cantidad[$i]),
                'tipo'              => 'Entrada'
            ]);
        }

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro actualizado correctamente',
            'type'      => 'success'
        ]);
    }

    public function delete(Request $request)
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

        $id            = $request->input('id');
        $product = Product::find($id);
        $stock = StockProduct::where('idproducto', $id)->get();
        foreach ($stock as $key => $value) {
            Kardex::create([
                'documentTypeId'    => 27,
                'userId'            => auth()->user()->id,
                'warehouseId'       => $value->idalmacen,
                'document'          => 00000, //actualizar a código del sunat
                'product'           => mb_strtoupper($product->descripcion),
                'cant2'             => $value->cantidad * (-1),
                'price2'            => 0,
                'total2'            => 0,
                'cant3'             => 0,
                'price3'            => 0,
                'total3'            => 0,
                'tipo'              => 'Salida'
            ]);
        }

        $product->delete();
        StockProduct::where('idproducto', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'title'     => '¡Bien!',
            'type'      => 'success'
        ]);
    }

    public function upload(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $excel      = $request->file('excel');
        if (empty($excel)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un documento',
                'type'      => 'warning'
            ]);
            return;
        }

        $extension          = $excel->extension();
        if ($extension != 'xlsx') {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione un documento válido',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        try {
            Excel::import(new ProductImport, $excel);
            echo json_encode([
                'status'    => true,
                'msg'       => 'Importación de datos exitosa',
                'type'      => 'success'
            ]);
        } catch (Exception $e) {

            echo json_encode([
                'status'    => false,
                'msg'       => 'Se encontraron observaciones en el documento',
                'type'      => 'warning'
            ]);
        }
    }

    public function download(Request $request, $ids = null)
    {

        $business           = Business::where('id', 1)->first();
        $nombre_documento   = 'Lista de productos ' . $business->razon_social;
        $productos  = Product::select(
            'products.*',
            'units.descripcion as unidad',
            'units.codigo as codigo_unidad',
            'igv_type_affections.descripcion as tipo_afecto'
        )
            ->join('units', 'products.idunidad', '=', 'units.id')
            ->join('igv_type_affections', 'products.idcodigo_igv', 'igv_type_affections.id')
            ->get();

        $selectedWarehouse = explode(',', $ids);
        $warehouses = [];

        if (in_array('0', $selectedWarehouse)) {
            $warehouses         = Warehouse::get();
        } else {
            $warehouses         = Warehouse::whereIn('id', $selectedWarehouse)->get();
        }

        $data['filas'] = [];
        foreach ($productos as $index => $producto) {
            foreach ($warehouses as $store) {
                $stock =  StockProduct::select('cantidad')->where('idproducto', $producto["id"])->where('idalmacen', $store["id"])->first();

                if ($stock === null) {
                    continue;
                } else {
                    $data['filas'][] = array_merge(
                            $producto->toArray(),
                            [
                                'tienda' => $store->descripcion,
                                'stock' => $stock->cantidad,
                            ]
                        );
                }
            }
        }

        return Excel::download(new DownloadProduct($data), $nombre_documento . '.xlsx');
    }

    public function view_stocks(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                         = (int) $request->input('id');
        $product                    = Product::where('id', $id)->first();
        $data["codigo"]             = (empty($product->codigo_interno)) ? '-' : $product->codigo_interno;
        $data["descripcion"]        = $product->descripcion;
        $data["marca"]              = (empty($product->marca)) ? '-' : $product->marca;
        $data["presentacion"]       = (empty($product->presentacion)) ? '-' : $product->presentacion;
        $data["precio_compra"]      = 'S/' . $product->precio_compra;
        $data["precio_venta"]       = 'S/' . $product->precio_venta;
        $data["precio_venta_por_mayor"]       = 'S/' . $product->precio_venta_por_mayor;
        $data["precio_venta_distribuidor"]       = 'S/' . $product->precio_venta_distribuidor;
        $data["fecha_vencimiento"]  = (empty($product->fecha_vencimiento)) ? '-' : $product->fecha_vencimiento;
        $data["stocks"]             = StockProduct::where('idproducto', $id)->get();
        $data["warehouses"]         = [];
        $detail_stocks              = StockProduct::where('idproducto', $id)->get();
        $data["total_stock"]        = StockProduct::where('idproducto', $id)->sum('cantidad');
        foreach ($detail_stocks as $stock) {
            $data["warehouses"][]   = Warehouse::where('id', $stock["idalmacen"])->first();
        }

        echo json_encode([
            'status'    => true,
            'data'      => $data
        ]);
    }

    public function enableProduct(Request $request)
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

        $id = $request->input('id');
        $product = Product::find($id);

        if ($product->enable) {
            $product->update(['enable' => 0]);

            echo json_encode([
                'status'    => true,
                'msg'       => 'Producto inhabilitado',
                'title'     => 'Realizado',
                'type'      => 'success'
            ]);
        } else {
            $product->update(['enable' => 1]);

            echo json_encode([
                'status'    => true,
                'msg'       => 'Producto habilitado',
                'title'     => 'Realizado',
                'type'      => 'success'
            ]);
        }
    }
}
