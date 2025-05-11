<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function stock()
    {
        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $data["warehouses"]        = Warehouse::get();
        }else{
            $data["warehouses"]        = Warehouse::where('id', auth()->user()->idalmacen)->get();
        }
        
        return view('admin.alerts.stocks.home', $data);
    }

    public function tbody_stocks(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $products           = Product::select('products.*', 'stock_products.cantidad as stock', 'stock_products.idalmacen')
                                    ->join('stock_products', 'products.id', 'stock_products.idproducto')
                                    ->where([['stock_products.cantidad', '<=', 10], ['products.opcion', 1]])
                                    ->get();
            $warehouses         = Warehouse::get();
        }else{
            $products           = Product::select('products.*', 'stock_products.cantidad as stock', 'stock_products.idalmacen')
                                    ->join('stock_products', 'products.id', 'stock_products.idproducto')
                                    ->where([['stock_products.cantidad', '<=', 10], ['products.opcion', 1], ['stock_products.idalmacen', auth()->user()->idalmacen]])
                                    ->get();
            $warehouses         = Warehouse::where('id', auth()->user()->idalmacen)->get();
        }

        echo json_encode([
            'status'            => true,
            'products'          => $products,
            'warehouses'        => $warehouses
        ]);
    }

    public function expiration()
    {
        return view('admin.alerts.expirations.home');
    }

    public function tbody_expirations(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $products           = Product::where('fecha_vencimiento', '!=', NULL)->get();

        }else{
            $products           = Product::where('fecha_vencimiento', '!=', NULL)
                                ->join('stock_products', 'products.id', 'stock_products.idproducto')
                                ->where('stock_products.idalmacen', auth()->user()->idalmacen)    
                                ->get();
        }

        //dd($products);
        
        $current                 = date('d-m-Y');
        $messages                = [];
        $new_products            = [];
        foreach ($products as $expiration) {
            $fecha_vencimiento  = $expiration["fecha_vencimiento"];
            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 1 days")) == $current)
            {
                $messages[]         = "VENCE EN 01 DÍA";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 2 days")) == $current)
            {
                $messages[]         = "VENCE EN 02 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 3 days")) == $current)
            {
                $messages[]         = "VENCE EN 03 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 4 days")) == $current)
            {
                $messages[]         = "VENCE EN 04 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 5 days")) == $current)
            {
                $messages[]         = "VENCE EN 05 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 6 days")) == $current)
            {
                $messages[]         = "VENCE EN 06 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 7 days")) == $current)
            {
                $messages[]         = "VENCE EN 07 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 8 days")) == $current)
            {
                $messages[]         = "VENCE EN 08 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 9 days")) == $current)
            {
                $messages[]         = "VENCE EN 09 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date("d-m-Y", strtotime($fecha_vencimiento . "- 10 days")) == $current)
            {
                $messages[]         = "VENCE EN 10 DÍAS";
                $new_products[]     = $expiration;
            }

            if (date(strtotime($fecha_vencimiento)) < strtotime($current))
            {
                $messages[]         = "EL PRODUCTO YA VENCIÓ";
                $new_products[]     = $expiration;
            }
        }

        echo json_encode([
            'status'     => true,
            'products'   => $new_products,
            'messages'   => $messages
        ]);
    }

    public function sale()
    {
        return view('admin.alerts.sales.home');
    }

    public function tbody_sales(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $user = User::with('roles')->where('id', auth()->user()->id)->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $billings   = Billing::select('billings.*', 'clients.dni_ruc as dni_ruc', 'clients.nombres as nombre_cliente')
                        ->join('clients', 'billings.idcliente', '=', 'clients.id')
                        ->where([['idtipo_comprobante', '!=', 6], ['estado_cpe', 0]])
                        ->orderBy('id', 'DESC')
                        ->get();
        }else{
            $billings   = Billing::select('billings.*', 'clients.dni_ruc as dni_ruc', 'clients.nombres as nombre_cliente')
                        ->join('clients', 'billings.idcliente', '=', 'clients.id')
                        ->where([['idtipo_comprobante', '!=', 6], ['estado_cpe', 0], ['billings.idusuario', auth()->user()->id]])
                        ->orderBy('id', 'DESC')
                        ->get();
        }

        
        $html       = '';
        $contador   = 0;
        $mensaje    = '';
        $current    = date('d-m-Y');
        $day        = date("d-m-Y", strtotime($current . "- 3 days"));
        foreach ($billings as $i => $billing) {
            $dif_min        = new DateTime($billing["fecha_emision"]);
            $dif_max        = new DateTime($day);
            $intervalo      = $dif_min->diff($dif_max);
            $dias           = $intervalo->d + intval($intervalo->h / 24, 2);

            switch ($dias) {
                case '1':
                    $mensaje          = "VENCE EN 01 DÍA";
                    break;

                case '2':
                    $mensaje          = "VENCE EN 02 DÍAS";
                    break;

                case '3':
                    $mensaje          = "VENCE EN 03 DÍAS";
                    break;

                default:
                    $mensaje          = "SU COMPROBANTE YA VENCIÓ";
                    break;
            }
            $contador += 1;
            $disabled   = ($dias > 3) ? 'pointer-events: none; color: #aaa;' : '';
            $html .= '<tr>
                        <td class="text-center">' . $contador . '</td>
                        <td class="text-center">' . date('d-m-Y', strtotime($billing["fecha_emision"])) . '</td>
                        <td>' . $billing["serie"] . '-' . $billing["correlativo"] . '</td>
                        <td class="text-center">' . $billing["dni_ruc"] . '</td>
                        <td class="text-left">' . $billing["nombre_cliente"] . '</td>
                        <td class="text-center">' . $mensaje . '</td>
                        <td class="text-center"><a class="btn-send-sunat" href="javascript:void(0);" data-id="'. $billing["id"] .'" style="' . $disabled . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </a></td>
                    </tr>';
        }

        echo json_encode([
            'status'    => true,
            'html'      => $html
        ]);
    }
}
