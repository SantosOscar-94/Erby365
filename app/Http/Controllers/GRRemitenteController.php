<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailGRRemitente;
use App\Models\GRRemitente;
use App\Models\IdentityDocumentType;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class GRRemitenteController extends Controller
{
    public function index()
    {
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        $data['clients']            = Client::where('iddoc', 1)->orWhere('iddoc', 2)->get();
        return view('admin.document_avanzados.guias.gr_remitente.home', $data);
    }

    public function add()
    {
        return view('admin.document_avanzados.guias.gr_remitente.add');
    }

    public function get()
    {
        $rows = GRRemitente::all();

        return Datatables()
            ->of($rows)
            ->addColumn('client', function ($rows) {
                return Client::find($rows->client_id)->nombres;
            })
            ->addColumn('document', function ($rows) {
                $documento  = 'T001' . '-' . '000000001';
                return $documento;
            })
            ->addColumn('status', function ($rows) {
                return '<span class="badge bg-success text-white">Aceptado</span>';
            })
            ->addColumn('xml', function ($rows) {
                return '<a target="_blank" href="#" class="text-center text-primary"><i class="far fa-file-code"></i></a>';
            })
            ->addColumn('cdr', function ($rows) {
                return '<a target="_blank" href="#" class="text-center text-primary"><i class="fas fa-file-alt"></i></a>';
            })
            ->addColumn('pdf', function ($rows) {
                return '<a target="_blank" href="#" class="text-center text-primary"><i class="fas fa-file-pdf"></i></a>';
            })
            ->addColumn('actions', function($sale_notes){
                $id                 = 1;
                $cliente            = 1;
                $idtipo_comprobante = 1;
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
            ->rawColumns(['client','document', 'status', 'xml', 'cdr', 'pdf', 'actions'])
            ->make(true);
    }

    public function save(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
            return;
        }

        if (empty(session()->get('gr_remitente')['products'])){
            echo json_encode([
                'status' => false,
                'msg' => 'Debe seleccionar al menos 1 producto',
                'type' => 'warning'
            ]);
            return;
        }

        try {
            $validatedData = $request->validate([
                'establecimiento' => 'required|integer',
                'serie' => 'required|string|max:255',
                'fecha-emision' => 'required|date',
                'fecha-traslado' => 'required|date',
                'cliente' => 'required|integer',
                'destinatario' => 'required|integer',
                'modo-traslado' => 'required|string|max:255',
                'motivo-traslado' => 'required|string|max:255',
                'desc-motivo-traslado' => 'nullable|string',
                'unidad-medida' => 'required|integer',
                'peso-total' => 'required|numeric',
                'num-paquetes' => 'required|integer',
                'observaciones' => 'nullable|string',
                'orden-pedido' => 'nullable|string|max:255',
                'orden-compra' => 'nullable|string|max:255',
                'referencia' => 'nullable|string|max:255',
                'punto-partida' => 'required|string|max:255',
                'punto-llegada' => 'required|string|max:255',
                'datos-conductor' => 'required|integer',
                'datos-vehiculo' => 'required|integer',
            ], [
                'required' => 'El campo :attribute es obligatorio.',
                'integer' => 'El campo :attribute debe ser un número entero.',
                'date' => 'El campo :attribute debe ser una fecha válida.',
                'numeric' => 'El campo :attribute debe ser un número.',
                'string' => 'El campo :attribute debe ser un texto.',
                'max.string' => 'El campo :attribute no puede exceder :max caracteres.',
                'attributes' => [
                    'establecimiento' => 'establecimiento',
                    'fecha-emision' => 'fecha de emisión',
                    'fecha-traslado' => 'fecha de traslado',
                    'unidad-medida' => 'unidad de medida',
                    'peso-total' => 'peso total',
                    'observaciones' => 'observaciones',
                    'orden-compra' => 'orden de compra',
                    'empresa' => 'empresa',
                    'remitente' => 'remitente',
                    'punto-partida' => 'punto de partida',
                    'destino' => 'destino',
                    'punto-llegada' => 'punto de llegada',
                    'datos-vehiculo' => 'datos del vehículo',
                    'datos-conductor' => 'datos del conductor',
                    'datos-vehiculo-secundario' => 'datos del vehículo secundario',
                    'datos-conductor-secundario' => 'datos del conductor secundario',
                ]
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Errores de validación',
                'errors' => $e->validator->errors(),
                'type' => 'danger'
            ], 200);
        }

        GRRemitente::create([
            'warehouseId' => $validatedData['establecimiento'],
            'issue_date' => $validatedData['fecha-emision'],
            'transfer_date' => $validatedData['fecha-traslado'],
            'client_id' => $validatedData['cliente'],
            'receiver_id' => $validatedData['destintario'],
            'transfer_mode' => $validatedData['modo-traslado'],
            'reason_transfer' => $validatedData['motivo-traslado'],
            'description_reason_transfer' => $validatedData['desc-motivo-traslado'],
            'measurement_unit' => $validatedData['unidad-medida'],
            'weight' => $validatedData['peso-total'],
            'quantity' => $validatedData['num-paquetes'],
            'quantity_packages' => $validatedData['num-carga'],
            'vehicle_config'    => $validatedData['vehicular-conf'],
            'observations' => $validatedData['observaciones'],
            'purchase_order' => $validatedData['orden-pedido'],
            'other_purchase_order' => $validatedData['orden-compra'],
            'purchase_order_reference' => $validatedData['referencia'],
            'start_point' => $validatedData['punto-partida'],
            'end_point' => $validatedData['punto-llegada'],
            'driverId' => $validatedData['datos-conductor'],
            'vehicleId' => $validatedData['datos-vehiculo'],
            'driverId2' => $validatedData['datos-conductor2'],
            'vehicleId2' => $validatedData['datos-vehiculo2'],
        ]);

        $gr_remitenteId = GRRemitente::latest()->first()->id;

        foreach (session()->get('gr_remitente')['products'] as $product) {
            DetailGRRemitente::create([
                'gr_remitenteId' => $gr_remitenteId,
                'productId' => $product['id'],
                'quantity' => $product['cantidad'],
                'weight' => $product['peso']
            ]);
        }

        $this->destroy_cart();

        echo json_encode([
            'status'    => true,
            'msg'       => 'La guía de remisión ha sido creada exitosamente',
            'type'      => 'success'
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
                                        <input type="text" data-id="' . $product["id"] . '" class="quantity-counter text-center form-control" value="' . $product["cantidad"] . '" disabled>
                                    </div>
                                </td>
                                <td class="text-center"><input type="text" class="form-control form-control-sm text-center input-update" value="' . $product["peso"] . '" data-cantidad="'.$product["cantidad"].'" data-id="' . $product["id"] . '" name="precio" disabled></td>
                                <td class="text-center"><span data-id="' . $product["id"] . '" class="text-danger btn-delete-product" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                            </tr>';
            }
        }

        echo json_encode([
            'status'        => true,
            'cart_products' => $cart,
            'html_cart'     => $html_cart,
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
        $peso       = (int) $request->input('peso');
        if(!$this->add_product_cart($id, $cantidad, $peso, $opcion))
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

    public function create_cart()
    {
        $sale_note =
            [
                'gr_remitente' =>
                    [
                        'products'     => session('gr_remitente')['products'] ?? null
                    ]
            ];

        session($sale_note);
        return session()->get('gr_remitente');
    }

    public function add_product_cart($id, $cantidad, $peso, $opcion)
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
                'precio_venta'      => $product->precio_venta,
                'impuesto'          => $product->impuesto,
                'stock'             => ($opcion == 1) ? $product->stock : null,
                'opcion'            => $opcion,
                'cantidad'          => $cantidad,
                'peso'              => $peso,
                'idalmacen'         => ($opcion == 1) ? $product->idalmacen : null,
            ];

        if (empty(session()->get('gr_remitente')['products'])) {
            session()->push('gr_remitente.products', $new_product);
            return true;
        }

        foreach (session()->get('gr_remitente')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                if ($opcion == 1) {
                    if ($product["stock"] < ($product['cantidad'] + $cantidad)) {
                        return false;
                    }
                }
                $product['cantidad'] = $product['cantidad'] + $cantidad;
                session()->put('gr_remitente.products.' . $index, $product);
                return true;
            }
        }

        session()->push('gr_remitente.products', $new_product);
        return true;
    }

    public function delete_product_cart($id, $opcion)
    {
        if (!session()->get('gr_remitente') || empty(session()->get('gr_remitente')['products'])) {
            return false;
        }

        foreach (session()->get('gr_remitente')['products'] as $index => $product) {
            if ($id == $product['id'] && $product['opcion'] == $opcion) {
                session()->forget('gr_remitente.products.' . $index, $product);
                return true;
            }
        }
    }

    public function destroy_cart()
    {
        if (!session()->get('gr_remitente') || empty(session()->get('gr_remitente')['products'])) {
            return false;
        }

        session()->forget('gr_remitente');
        return true;
    }
}
