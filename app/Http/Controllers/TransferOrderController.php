<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\DetailTransferOrder;
use App\Models\Kardex;
use App\Models\Product;
use App\Models\StockProduct;
use App\Models\TransferOrder;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferOrderController extends Controller
{
    public function index()
    {
        return view('admin.transfer_orders.list');
    }

    public function get()
    {
        $transfer_orders    = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                            'receptor.descripcion as almacen_receptor')
                            ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                            ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                            ->orderBy('id', 'DESC')
                            ->get();
        return Datatables()
            ->of($transfer_orders)
            ->addColumn('documento', function ($transfer_orders) {
                $documento  = $transfer_orders->serie . '-' . $transfer_orders->correlativo;
                return $documento;
            })
            ->addColumn('fecha_de_emision', function ($transfer_orders) {
                $fecha_emision = date('d-m-Y', strtotime($transfer_orders->fecha_emision));
                return $fecha_emision;
            })
            ->addColumn('estado_compra', function ($transfer_orders) {
                $estado    = $transfer_orders->estado;
                $btn    = '';
                switch ($estado) {
                    case '0':
                        $btn .= '<span class="badge text-white" style="background-color: rgb(108, 117, 125);">Registrado</span>';
                        break;

                    case '1':
                        $btn .= '<span class="badge bg-success text-white">Aceptado</span>';
                        break;

                    case '2':
                        $btn .= '<span class="badge bg-danger text-white">Anulado</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('acciones', function ($transfer_orders) {
                /* <a class="dropdown-item btn-open-whatsapp" data-id="' . $id . '" href="javascript:void(0);">
                                            <i class="fa-brands fa-whatsapp"></i>
                                            <span> Enviar Documento</span>
                                        </a> */
                $id         = $transfer_orders->id;
                $disabled   = ($transfer_orders->estado == 2) ? 'disabled' : '';
                $status     = ($transfer_orders->estado == 1) ? 'd-none' : '';
                $status_a   = ($transfer_orders->estado == 1) ? '' : 'd-none';
                $btn    = '<div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow ' . $disabled . '" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                <div class="dropdown-menu">
                                        <a class="dropdown-item btn-confirm-transfer '.$status.'" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-repeat"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                                            <span>Confirmar Traslado</span>
                                            </a>
                                        <a class="dropdown-item btn-print '.$status_a.'" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                            <span> Imprimir</span>
                                        </a>
                                        <a class="dropdown-item btn-download '.$status_a.'" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <span>Descargar PDF A4</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm '.$status.'" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Anular</span>
                                        </a>
                </div>
            </div>';
                return $btn;
            })
            ->rawColumns(['proveedor', 'documento', 'fecha_de_emision', 'estado_compra', 'acciones'])
            ->make(true);
    }

    public function download($id)
    {
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $data['transfer']           = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                                    'receptor.descripcion as almacen_receptor', 'users.nombres as usuario_solicita')
                                    ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                                    ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                                    ->join('users', 'transfer_orders.idusuario', 'users.id')
                                    ->where('transfer_orders.id', $id)
                                    ->first();

        $data['detalle']            = DetailTransferOrder::select('detail_transfer_orders.*', 'products.codigo_interno as codigo_interno',
                                    'products.descripcion as producto', 'units.codigo as unidad')
                                    ->join('products', 'detail_transfer_orders.idproducto', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_transfer_orders.idorden_traslado', $id)
                                    ->get();
        $name                       = $data["business"]->ruc . '-' . $data["transfer"]->serie . '-' . $data["transfer"]->correlativo . '.pdf';
        $pdf    = PDF::loadView('admin.transfer_orders.pdf', $data)->setPaper('A4', 'portrait');
        return $pdf->download($name);
    }

    public function print(Request $request)
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


        $id                         = $request->input('id');
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $data['transfer']           = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                                    'receptor.descripcion as almacen_receptor', 'users.nombres as usuario_solicita')
                                    ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                                    ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                                    ->join('users', 'transfer_orders.idusuario', 'users.id')
                                    ->where('transfer_orders.id', $id)
                                    ->first();

        $data['detalle']            = DetailTransferOrder::select('detail_transfer_orders.*', 'products.codigo_interno as codigo_interno',
                                    'products.descripcion as producto', 'units.codigo as unidad')
                                    ->join('products', 'detail_transfer_orders.idproducto', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_transfer_orders.idorden_traslado', $id)
                                    ->get();
        $name                       = $data["business"]->ruc . '-' . $data["transfer"]->serie . '-' . $data["transfer"]->correlativo . '.pdf';
        $pdf    = PDF::loadView('admin.transfer_orders.pdf', $data)->setPaper('A4', 'portrait');
        $pdf->save(public_path('files/transfer-orders/' . $name));

        echo json_encode([
            'status'    => true,
            'pdf'       => $name
        ]);
    }

    public function create()
    {
        $data["warehouses"]     = Warehouse::get();
        return view('admin.transfer_orders.create.home', $data);
    }

    public function load_serie(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $valid_transfer         = TransferOrder::count();
        $serie                  = 'OT01';
        if($valid_transfer == 0) {
            $correlativo        = str_pad(1, 8, '0', STR_PAD_LEFT);
        }
        else {
            $last_transfer      = TransferOrder::latest('id')->first();
            $last_correlative   = (int) $last_transfer->correlativo + 1;
            $correlativo        = str_pad($last_correlative, 8, '0', STR_PAD_LEFT);
        }

        echo json_encode([
            'status'        => true,
            'serie'         => $serie,
            'correlativo'   => $correlativo
        ]);
    }

    public function load_warehouse_office(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $idwarehouse_office     = $request->input('idwarehouse_office');
        $receivings_office      = Warehouse::where('id', '!=', $idwarehouse_office)->get();
        echo json_encode([
            'status'            => true,
            'receivings_office' => $receivings_office
        ]);
    }

    public function load_warehouse_dispatch(Request $request)
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
        $receivings_office  = Warehouse::where('id', '!=', $id)->get();
        echo json_encode([
            'status'            => true,
            'receivings_office' => $receivings_office
        ]);
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

    public function search_product(Request $request)
    {
        if(!$request->ajax())
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'title'     => 'Espere',
                'type'      => 'warning'
            ]);
            return;
        }

        $value              = trim($request->input('value'));
        $products           = Product::where('descripcion', 'like', "%$value%")
                            ->orWhere('codigo_interno', 'like', "%$value%")
                            ->get();

        $html_products      = '';
        $scroll_y           = count($products) >= 8 ? 'el-table--scrollable-y  el-table--enable-row-transition' : '';
        if(count($products) >= 1)
        {
            foreach($products as $product)
            {
                $codigo_interno = empty($product["codigo_interno"]) ? '-' : $product["codigo_interno"];
                $html_products .= '<tr class="el-table__row btn__select__product" data-id="'. $product["id"] .'">
                                        <td rowspan="1" colspan="1" class="el-table__cell text-center">
                                            <div class="cell">'. $codigo_interno .'</div>
                                        </td>
                                        <td rowspan="1" colspan="1" class="el-table__cell">
                                            <div class="cell">'. $product["descripcion"] .'</div>
                                        </td>
                                        <td rowspan="1" colspan="1"
                                            class="el-table__cell text-center">
                                            <div class="cell">'. number_format($product["precio_venta"], 2) .'</div>
                                        </td>
                                    </tr>';
            }
        }

        echo json_encode([
            'status'        => true,
            'products'      => $products,
            'html_products' => $html_products,
            'scroll_y'      => $scroll_y,
            'quantity'      => count($products)
        ]);
    }

    public function detail_product(Request $request)
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

        $id                 = (int) $request->input('id');
        $product            = Product::where('id', $id)->first();
        $warehouses         = [];
        $detail_stocks      = StockProduct::where('idproducto', $id)->get();

        foreach($detail_stocks as $stock)
        {
            $warehouses[]   = Warehouse::where('id', $stock["idalmacen"])->first();
        }

        echo json_encode([
            'status'        => true,
            'product'       => $product,
            'warehouses'    => $warehouses,
            'detail_stocks' => $detail_stocks
        ]);
    }

    public function add_product(Request $request)
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

        $id                 = (int) $request->input('idproducto');
        $cantidad           = (int) $request->input('cantidad');
        $idalmacen_despacho = (int) $request->input('idalmacen_despacho');

        if (!$this->add_product_cart($id, $cantidad, $idalmacen_despacho))
        {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Stock insuficiente',
                'title'     => 'Espere',
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
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $producto       = Product::where('id', $id)->first();
        if(!$this->delete_product_cart($id))
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
            'msg'       => 'Producto agregado correctamente',
            'type'      => 'success'
        ]);
    }

    public function store_product(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id             = (int) $request->input('id');
        $cantidad       = (int) $request->input('cantidad');
        if(!$this->update_quantity($id , $cantidad))
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
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $serie                  = $request->input('serie');
        $correlativo            = trim($request->input('correlativo'));
        $fecha_emision          = date('Y-m-d');
        $fecha_vencimiento      = date('Y-m-d');
        $almacen_despacho       = $request->input('almacen_despacho');
        $almacen_receptor       = $request->input('almacen_receptor');
        $observaciones          = trim($request->input('observaciones'));
        $cart                   = $this->create_cart();
        if(empty($almacen_receptor)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Seleccione el almacén de destino',
                'type'      => 'warning'
            ]);
            return;
        }

        if(empty($cart["products"])) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Debe ingresar al menos 1 producto',
                'type'      => 'warning'
            ]);
            return;
        }

        TransferOrder::create([
            'serie'                 => $serie,
            'correlativo'           => $correlativo,
            'fecha_emision'         => $fecha_emision,
            'fecha_vencimiento'     => $fecha_vencimiento,
            'hora'                  => date('H:i:s'),
            'idalmacen_despacho'    => $almacen_despacho,
            'idalmacen_receptor'    => $almacen_receptor,
            'observaciones'         => mb_strtoupper($observaciones),
            'idusuario'             => Auth::user()['id'],
            'estado'                => 0
        ]);
        $idtransfer                  = TransferOrder::latest('id')->first()['id'];



        foreach($cart["products"] as $product)
        {
            DetailTransferOrder::create([
                'idorden_traslado'  => $idtransfer,
                'idproducto'        => $product["id"],
                'cantidad'          => $product["cantidad"]
            ]);

            $stock = StockProduct::where('idproducto', $product["id"])->where('idalmacen', $almacen_despacho)->first();

            Kardex::create([
                'documentTypeId'    => '29',
                'userId'            => auth()->user()->id,
                'warehouseId'       => $almacen_despacho,
                'document'          => $serie."-".$correlativo, //actualizar a código del sunat
                'product'           => mb_strtoupper($product['descripcion']),
                'cant2'             => $product["cantidad"] * (-1),
                'price2'            => $product["precio_compra"],
                'total2'            => ($product['precio_compra'] * $product['cantidad'] * (-1)),
                'cant3'             => ($stock->cantidad - $product["cantidad"]),
                'price3'            => $product["precio_compra"],
                'total3'            => ($product['precio_compra'] * $stock->cantidad * (-1)),
                'tipo'              => 'Salida'
            ]);

            $stock = StockProduct::where('idproducto', $product["id"])->where('idalmacen', $almacen_receptor)->first();

            Kardex::create([
                'documentTypeId'    => '28',
                'userId'            => auth()->user()->id,
                'warehouseId'       => $almacen_receptor,
                'document'          => $serie."-".$correlativo, //actualizar a código del sunat
                'product'           => mb_strtoupper($product['descripcion']),
                'cant1'             => $product["cantidad"],
                'price1'            => $product["precio_compra"],
                'total1'            => ($product['precio_compra'] * $product['cantidad']),
                'cant3'             => $stock->cantidad + $product["cantidad"],
                'price3'            => $product["precio_compra"],
                'total3'            => ($product['precio_compra'] * $stock->cantidad),
                'tipo'              => 'Entrada'
            ]);
        }
        $this->destroy_cart();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Orden de Traslado registrado correctamente'
        ]);
    }

    public function detail(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        $transfer           = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                            'receptor.descripcion as almacen_receptor')
                            ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                            ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                            ->where('transfer_orders.id', $id)
                            ->first();

        $fecha_emision      = date('d-m-Y', strtotime($transfer->fecha_emision));
        $fecha_vencimiento  = date('d-m-Y', strtotime($transfer->fecha_vencimiento));

        $detail_transfer    = DetailTransferOrder::select('detail_transfer_orders.*', 'products.codigo_interno as codigo_interno',
                            'products.descripcion as producto')
                            ->join('products', 'detail_transfer_orders.idproducto', 'products.id')
                            ->where('detail_transfer_orders.idorden_traslado', $id)
                            ->get();

        echo json_encode([
            'status'            => true,
            'transfer'          => $transfer,
            'detail_transfer'   => $detail_transfer,
            'fecha_emision'     => $fecha_emision,
            'fecha_vencimiento' => $fecha_vencimiento
        ]);
    }

    public function anulled(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('id');
        TransferOrder::where('id', $id)->update([
            'estado'        => 2
        ]);
        echo json_encode([
            'status'    => true,
            'msg'       => 'Orden anulada correctamente',
            'type'      => 'success'
        ]);
    }

    public function move(Request $request)
    {
        if(!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id                 = $request->input('idtransfer');
        $transfer           = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                            'receptor.descripcion as almacen_receptor')
                            ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                            ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                            ->where('transfer_orders.id', $id)
                            ->first();

        $detail_transfer    = DetailTransferOrder::select('detail_transfer_orders.*', 'products.codigo_interno as codigo_interno',
                            'products.descripcion as producto')
                            ->join('products', 'detail_transfer_orders.idproducto', 'products.id')
                            ->where('detail_transfer_orders.idorden_traslado', $id)
                            ->get();

        // Aumentar la cantidad del almacen receptor y dismunuir de despacho
        foreach($detail_transfer as $i => $product)
        {
            $cantidad_actual_despacho      = StockProduct::where('idproducto', $product['idproducto'])
                                            ->where('idalmacen', $transfer->idalmacen_despacho)
                                            ->first()['cantidad'];

            $cantidad_actual_receptor      = StockProduct::where('idproducto', $product['idproducto'])
                                            ->where('idalmacen', $transfer->idalmacen_receptor)
                                            ->first()['cantidad'];

            StockProduct::where('idproducto', $product["idproducto"])
                        ->where('idalmacen', $transfer->idalmacen_despacho)
                        ->update([
                            'cantidad'  => ((int) $cantidad_actual_despacho - (int) $product['cantidad'])
                        ]);

            StockProduct::where('idproducto', $product["idproducto"])
                        ->where('idalmacen', $transfer->idalmacen_receptor)
                        ->update([
                            'cantidad'  => ((int) $cantidad_actual_receptor + (int) $product['cantidad'])
                        ]);
        }

        TransferOrder::where('id', $id)->update([
            'estado'    => 1
        ]);

        // Gen PDF
        $data['business']           = Business::where('id', 1)->first();
        $data['ubigeo']             = $this->get_ubigeo($data['business']->ubigeo);
        $data['transfer']           = TransferOrder::select('transfer_orders.*', 'despacho.descripcion as almacen_despacho',
                                    'receptor.descripcion as almacen_receptor', 'users.nombres as usuario_solicita')
                                    ->join('warehouses as despacho', 'transfer_orders.idalmacen_despacho', '=', 'despacho.id')
                                    ->join('warehouses as receptor', 'transfer_orders.idalmacen_receptor', '=', 'receptor.id')
                                    ->join('users', 'transfer_orders.idusuario', 'users.id')
                                    ->where('transfer_orders.id', $id)
                                    ->first();

        $data['detalle']            = DetailTransferOrder::select('detail_transfer_orders.*', 'products.codigo_interno as codigo_interno',
                                    'products.descripcion as producto', 'units.codigo as unidad')
                                    ->join('products', 'detail_transfer_orders.idproducto', 'products.id')
                                    ->join('units', 'products.idunidad', '=', 'units.id')
                                    ->where('detail_transfer_orders.idorden_traslado', $id)
                                    ->get();
        $name                       = $data["business"]->ruc . '-' . $data["transfer"]->serie . '-' . $data["transfer"]->correlativo . '.pdf';
        $pdf    = PDF::loadView('admin.transfer_orders.pdf', $data)->setPaper('A4', 'portrait');
        $pdf->save(public_path('files/transfer-orders/' . $name));

        echo json_encode([
            'status'    => true,
            'pdf'       => $name
        ]);
    }

    public function test()
    {
        $pdf    = PDF::loadView('admin.transfer_orders.create.test_pdf')->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    ## Functions to cart
    public function create_cart()
    {
        if (!session()->get('transfer') || empty(session()->get('transfer')['products'])) {
            $transfer =
                [
                    'transfer' =>
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

            session($transfer);
            return session()->get('transfer');
        }

        $exonerada  = 0;
        $gravada    = 0;
        $inafecta   = 0;
        $subtotal   = 0;
        $total      = 0;
        $igv        = 0;

        foreach (session('transfer')['products'] as $index => $product) {
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
            session()->put('transfer.products.' . $index, $product);
        }

        $total      = $subtotal + $igv;

        $transfer =
            [
                'transfer' =>
                [
                    'products'     => session('transfer')['products'],
                    'igv'          => $igv,
                    'exonerada'    => $exonerada,
                    'gravada'      => $gravada,
                    'inafecta'     => $inafecta,
                    'subtotal'     => $subtotal,
                    'total'        => $total,
                ]
            ];

        session($transfer);
        return session()->get('transfer');
    }

    public function add_product_cart($id, $cantidad, $idalmacen_despacho)
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
        ->where('products.id', $id)
        ->where('warehouses.id', $idalmacen_despacho)
        ->first();

        if(!$product)
            return false;

        if ($product->stock < $cantidad)  {
            return false;
        }
        elseif($product->stock == 0) {
            return false;
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
            'precio_venta'      => $product->precio_venta,
            'impuesto'          => $product->impuesto,
            'stock'             => $product->stock,
            'cantidad'          => $cantidad,
            'idalmacen'         => $product->idalmacen,
        ];

        if (empty(session()->get('transfer')['products'])) {
            session()->push('transfer.products', $new_product);
            return true;
        }

        foreach (session()->get('transfer')['products'] as $index => $product) {
            if ($id == $product['id']) {
                if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                    return false;
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('transfer.products.' . $index, $product);
                return true;
            }
        }

        session()->push('transfer.products', $new_product);
        return true;
    }

    public function delete_product_cart($id)
    {
        if (!session()->get('transfer') || empty(session()->get('transfer')['products'])) {
            return false;
        }

        foreach (session()->get('transfer')['products'] as $index => $product) {
            if ($id == $product['id']) {
                session()->forget('transfer.products.' . $index, $product);
                return true;
            }
        }
    }

    public function update_quantity($id, $cantidad)
    {
        if (empty(session()->get('transfer')['products'])) {
            return false;
        }

        foreach (session()->get('transfer')['products'] as $index => $product) {
            if ($id == $product['id']) {
                $product['cantidad']           =  $cantidad;
                session()->put('transfer.products.' . $index, $product);
                return true;
            }
        }
    }

    public function destroy_cart()
    {
        if (!session()->get('transfer') || empty(session()->get('transfer')['products'])) {
            return false;
        }

        session()->forget('transfer');
        return true;
    }
}
