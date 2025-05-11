<?php

namespace App\Http\Controllers;

use App\Exports\SaleReportGeneral;
use App\Exports\SaleReportProduct;
use App\Exports\SaleReportSeller;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Client;
use App\Models\SaleNote;
use App\Models\TypeDocument;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Direccion_Partida;
use App\Models\Vehiculos;
use App\Models\Conductores;
use App\Models\Transportistas;
use App\Models\Department;
use App\Models\District;
use App\Models\IdentityDocumentType;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class DocumentAvanzadosController extends Controller
{
    ## G_R_REMITENTE
    public function sales_general1()
    {
        $data["clients"]        = Client::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        return view('admin.document_avanzados.guias.gr_remitente.home', $data);
    }

    public function add_sales_general1()
    {
        $data["clients"]        = Client::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        return view('admin.document_avanzados.guias.gr_remitente.add', $data);
    }

    public function search_sales_general1(Request $request)
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
        $idclient           = $request->input('idclient');
        $data["pagos"]      = [];
        $data["total"]      = 0;
        $document           = $request->input('document');
        switch ($document) {
            case '0':
                switch ($idclient) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM sale_notes
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM sale_notes
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idcliente = $idclient
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;
                }
                break;

            case '1':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '2':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '6':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                    clients.dni_ruc as dni_ruc,
                                                    clients.nombres as nombre_cliente
                                                    FROM billings
                                                    INNER JOIN clients ON billings.idcliente = clients.id
                                                    WHERE idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                    ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                    clients.dni_ruc as dni_ruc,
                                                    clients.nombres as  nombre_cliente
                                                    FROM billings
                                                    INNER JOIN clients ON billings.idcliente = clients.id
                                                    WHERE idcliente = $idclient AND idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                    ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '7':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                        clients.nombres as  nombre_cliente
                                        FROM sale_notes
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc,
                                            clients.nombres as  nombre_cliente
                                            FROM sale_notes
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idcliente = $idclient AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;
        }

        $doc_relacionados           = [];
        foreach ($data["billings"] as &$billing) { // & para modificar el array
            $data["total"]          += strval($billing["total"]);
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura_anular       = $billing["idfactura_anular"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante,
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");

            // Añadir el tipo de documento
            $billing["tipo_documento"] = TypeDocument::find($billing["idtipo_comprobante"])->descripcion;

            if ($idtipo_comprobante != 7) {
                $doc_relacionados[] = Billing::where('id', $idfactura_anular)->first();
            } else {
                $doc_relacionados[] = null;
            }
        }
        $quantity           = count($data["billings"]);
        echo json_encode([
            "status"            => true,
            "billings"          => $data["billings"],
            "pagos"             => $data["pagos"],
            "quantity"          => $quantity,
            "total"             => $data["total"],
            'doc_relacionados'  => $doc_relacionados
        ]);
    }

    public function export_sales_general1(Request $request)
    {
        $fecha_inicial              = $request->input('fecha_inicial');
        $fecha_final                = $request->input('fecha_final');
        $idclient                   = $request->input('idclient');
        $data["pagos"]              = [];
        $data["total"]              = 0;
        $data["anulado"]            = 0;
        $data["total_neto"]         = 0;
        $export_pdf                 = $request->input('export_pdf');
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $document                   = $request->input('document');
        switch ($document) {
            case '0':
                switch ($idclient) {
                    case '0':
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM sale_notes
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;

                    default:
                        $b_f                    = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante != 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $n_v                    = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM sale_notes
                                                INNER JOIN clients ON sale_notes.idcliente = clients.id
                                                WHERE idcliente = $idclient
                                                AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");

                        $b_f                    = json_encode($b_f);
                        $n_v                    = json_encode($n_v);
                        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));
                        break;
                }
                break;

            case '1':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 1 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '2':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                clients.dni_ruc as dni_ruc,
                                                clients.nombres as  nombre_cliente
                                                FROM billings
                                                INNER JOIN clients ON billings.idcliente = clients.id
                                                WHERE idcliente = $idclient AND idtipo_comprobante = 2 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '6':
                switch ($idclient) {
                    case '0':
                        $billings           = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                        clients.dni_ruc as dni_ruc,
                                                        clients.nombres as nombre_cliente
                                                        FROM billings
                                                        INNER JOIN clients ON billings.idcliente = clients.id
                                                        WHERE idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT billings.*, billings.estado_cpe as estado_venta,
                                                        clients.dni_ruc as dni_ruc,
                                                        clients.nombres as  nombre_cliente
                                                        FROM billings
                                                        INNER JOIN clients ON billings.idcliente = clients.id
                                                        WHERE idcliente = $idclient AND idtipo_comprobante = 6 AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;

            case '7':
                switch ($idclient) {
                    case '0':
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta ,clients.dni_ruc as dni_ruc,
                                        clients.nombres as  nombre_cliente
                                        FROM sale_notes
                                        INNER JOIN clients ON sale_notes.idcliente = clients.id
                                        WHERE fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                        ORDER BY id ASC");
                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;

                    default:
                        $billings   = DB::select("SELECT sale_notes.*, sale_notes.estado as estado_venta,
                                            clients.dni_ruc as dni_ruc,
                                            clients.nombres as  nombre_cliente
                                            FROM sale_notes
                                            INNER JOIN clients ON sale_notes.idcliente = clients.id
                                            WHERE idcliente = $idclient AND fecha_emision BETWEEN '$fecha_inicial' AND '$fecha_final'
                                            ORDER BY id ASC");

                        $data["billings"]   = json_encode($billings);
                        $data["billings"]   = json_decode($data["billings"], true);
                        break;
                }
                break;
        }

        $data["doc_relacionados"]           = [];
        foreach ($data["billings"] as $billing) {
            if ($billing["estado_venta"] == 2)
                $data["anulado"]    += $billing["total"];

            if ($billing["idtipo_comprobante"] != 6)
                $data["total"]    += $billing["total"];

            $data["total_neto"]     = $data["total"] - $data["anulado"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura_anular       = $billing["idfactura_anular"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante,
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");

            if ($idtipo_comprobante != 7) {
                $data["doc_relacionados"][] = Billing::where('id', $idfactura_anular)->first();
            } else {
                $data["doc_relacionados"][] = null;
            }
        }
        $data["quantity"]           = count($data["billings"]);
        $data["fecha_inicial"]      = $fecha_inicial;
        $data["fecha_final"]        = $fecha_final;
        $nombre_excel               = 'Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.xlsx';
        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.sales.general.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new SaleReportGeneral($data), $nombre_excel);
        }
    }

    ##++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ## G_R_TRANSPORTISTAS
    public function sales_seller1()
    {
        $data["sellers"]        = User::get();
        $data["warehouses"]        = Warehouse::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->get();
        //dd($data);
        return view('admin.document_avanzados.guias.gr_transportista.home', $data);
    }

    public function add_sales_seller1()
    {
        $data["clients"]        = Client::get();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        return view('admin.document_avanzados.guias.gr_transportista.add', $data);
    }

    public function search_sales_seller1(Request $request)
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
        $user             = $request->input('iduser');
        $warehouse             = $request->input('warehouse');
        $data["pagos"]      = [];
        $data["total"]      = 0;
        $document           = $request->input('document');

        $b_f = Billing::select(
            "billings.*",
            "billings.estado_cpe as estado_venta",
            "clients.dni_ruc as dni_ruc",
            "clients.nombres as nombre_cliente",
            "users.nombres as usuario",
            "users.idalmacen"
        )
            ->join("clients", "billings.idcliente", "clients.id")
            ->join("users", "billings.idusuario", "users.id")
            ->where("billings.idtipo_comprobante", "!=", 6)
            ->whereBetween('billings.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('billings.id', 'ASC')
            ->get();

        $n_v = SaleNote::select(
            "sale_notes.*",
            "sale_notes.estado as estado_venta",
            "clients.dni_ruc as dni_ruc",
            "clients.nombres as  nombre_cliente",
            "users.nombres as usuario",
            "users.idalmacen as idalmacen"
        )
            ->join("clients", "sale_notes.idcliente", "clients.id")
            ->join("users", "sale_notes.idusuario", "users.id")
            ->whereBetween('sale_notes.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('sale_notes.id', 'ASC')
            ->get();

        if (!empty($user)) {
            $b_f = $b_f->where('idusuario', $user);
            $n_v = $n_v->where('idusuario', $user);
        }

        if (!empty($warehouse)) {
            $b_f = $b_f->where('idalmacen', $warehouse);
            $n_v = $n_v->where('idalmacen', $warehouse);
        }

        if (!empty($document)) {
            $b_f = $b_f->where('idtipo_comprobante', $document);
            $n_v = $n_v->where('idtipo_comprobante', $document);
        }

        $b_f                    = json_encode($b_f);
        $n_v                    = json_encode($n_v);
        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));

        foreach ($data["billings"] as $billing) {
            $data["total"]          += strval($billing["total"]);
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante,
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
        }
        $quantity           = count($data["billings"]);
        echo json_encode([
            "status"        => true,
            "billings"      => $data["billings"],
            "pagos"         => $data["pagos"],
            "quantity"      => $quantity,
            "total"         => $data["total"]
        ]);
    }

    public function export_sales_seller1(Request $request)
    {
        $fecha_inicial              = $request->input('fecha_inicial');
        $fecha_final                = $request->input('fecha_final');
        $user                       = $request->input('iduser');
        $data["pagos"]              = [];
        $data["total"]              = 0;
        $data["anulado"]            = 0;
        $data["total_neto"]         = 0;
        $export_pdf                 = $request->input('export_pdf');
        $data["business"]           = Business::where('id', 1)->first();
        $data['ruc']                = $data["business"]->ruc;
        $data['nombre_comercial']   = $data["business"]->nombre_comercial;
        $document                   = $request->input('document');

        $b_f = Billing::select(
            "billings.*",
            "billings.estado_cpe as estado_venta",
            "clients.dni_ruc as dni_ruc",
            "clients.nombres as nombre_cliente",
            "users.nombres as usuario",
            "users.idalmacen"
        )
            ->join("clients", "billings.idcliente", "clients.id")
            ->join("users", "billings.idusuario", "users.id")
            ->where("billings.idtipo_comprobante", "!=", 6)
            ->whereBetween('billings.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('billings.id', 'ASC')
            ->get();

        $n_v = SaleNote::select(
            "sale_notes.*",
            "sale_notes.estado as estado_venta",
            "clients.dni_ruc as dni_ruc",
            "clients.nombres as  nombre_cliente",
            "users.nombres as usuario",
            "users.idalmacen as idalmacen"
        )
            ->join("clients", "sale_notes.idcliente", "clients.id")
            ->join("users", "sale_notes.idusuario", "users.id")
            ->whereBetween('sale_notes.created_at', [$fecha_inicial, $fecha_final])
            ->orderBy('sale_notes.id', 'ASC')
            ->get();

        $data["seller"]         = "TODOS";

        if (!empty($user)) {
            $b_f = $b_f->where('idusuario', $user);
            $n_v = $n_v->where('idusuario', $user);

            $data["seller"]         = mb_strtoupper(User::where('id', $user)->first()["nombres"]);
        }

        if (!empty($warehouse)) {
            $b_f = $b_f->where('idalmacen', $warehouse);
            $n_v = $n_v->where('idalmacen', $warehouse);
        }

        if (!empty($document)) {
            $b_f = $b_f->where('idtipo_comprobante', $document);
            $n_v = $n_v->where('idtipo_comprobante', $document);
        }

        $b_f                    = json_encode($b_f);
        $n_v                    = json_encode($n_v);
        $data["billings"]       = array_merge(json_decode($b_f, true), json_decode($n_v, true));

        foreach ($data["billings"] as $billing) {
            if ($billing["estado_venta"] == 2)
                $data["anulado"]    += $billing["total"];

            if ($billing["idtipo_comprobante"] != 6)
                $data["total"]    += $billing["total"];

            $data["total_neto"]     = $data["total"] - $data["anulado"];
            $idtipo_comprobante     = $billing["idtipo_comprobante"];
            $idfactura              = $billing["id"];
            $idcaja                 = $billing["idcaja"];
            $data["pagos"][]        = DB::select("SELECT SUM(monto) as monto, idpago, idfactura, idtipo_comprobante,
                                    pay_modes.descripcion as tipo_pago
                                    FROM detail_payments
                                    INNER JOIN pay_modes ON detail_payments.idpago = pay_modes.id
                                    WHERE idcaja = $idcaja AND idtipo_comprobante = $idtipo_comprobante AND idfactura = $idfactura
                                    GROUP BY idpago, pay_modes.descripcion, idfactura, idtipo_comprobante");
        }

        $data["quantity"]           = count($data["billings"]);
        $data["fecha_inicial"]      = $fecha_inicial;
        $data["fecha_final"]        = $fecha_final;

        $nombre_excel               = 'Reporte de Ventas General ' . date('d-m-Y H-i-s') . '.xlsx';
        if (!empty($export_pdf)) {
            $pdf    = PDF::loadView('admin.reports.sales.sellers.pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->download('Reporte de Ventas por Vendedor ' . date('d-m-Y H-i-s') . '.pdf');
        } else {
            return Excel::download(new SaleReportSeller($data), $nombre_excel);
        }
    }

    ##++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ## TRANSPORTISTAS
    public function sales_product1()
    {
        return view('admin.document_avanzados.guias.transportistas.list');
    }


    public function index_t()
    {
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        return view('admin.transportistas.list', $data);
    }

    public function get_t()
    {
        $transportistas     = Transportistas::orderBy('id', 'DESC')->get();
        return Datatables()
            ->of($transportistas)
            ->addColumn('acciones', function ($transportistas) {
                $id     = $transportistas->id;
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function load_ubigeo_t()
    {
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
        ]);
    }

    public function search_t(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $type_document      = trim($request->input('type_document'));
        $dni_ruc            = trim($request->input('dni_ruc'));
        $client             = $this->verify__client($dni_ruc);
        //dd($client);

        if (!$client->success) {
            echo json_encode([
                'status'    => false,
                'msg'       => $client->message,
                'type'      => 'warning'
            ]);
        } else {

            $data           = $client->data;
            $nombres        = '';
            $licencia      = '';
            $ubigeo         = NULL;

            // Ubigeo por default de lima
            $ubigeo         = '150101';
            if ($type_document == '2') // DNI
            {
                $nombres        = $data->nombres . ' ' . $data->apellido_paterno . ' ' . $data->apellido_materno;
            } else if ($type_document == '4' && str_starts_with($dni_ruc, '10')) {
                $nombres        = $data->nombre_o_razon_social;
            } else {
                $nombres        = $data->nombre_o_razon_social;
                $licencia      = $data->licencia;
                $ubigeo         = $data->ubigeo[2];
            }

            echo json_encode([
                'status'    => true,
                'nombres'   => $nombres,
                'licencia' => $licencia,
                'ubigeo'    => $ubigeo
            ]);
        }
    }

    public function save_t(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $tipo_documento         = $request->input('tipo_documento');
        $idtipo_comprobante_    = ($tipo_documento == "4") ? "1" : "2";
        $dni_ruc                = $request->input('dni_ruc');
        $razon_social           = trim($request->input('razon_social'));
        $direccion              = trim($request->input('direccion'));
        $mtc              = trim($request->input('mtc'));
        $telefono               = trim($request->input('telefono'));
        $departamento           = $request->input('departamento');
        $provincia              = $request->input('provincia');
        $distrito               = $request->input('distrito');
        $search_client          = Client::where('dni_ruc', $dni_ruc)->first();

        if (!empty($search_client)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El cliente ya se encuentra registrado',
                'type'      => 'warning'
            ]);
            return;
        }

        if (!empty($departamento)) {
            if (empty($provincia)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } elseif (empty($distrito)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } else {
                Transportistas::create([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'mtc'     => mb_strtoupper($mtc),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);

                $last_id            = Transportistas::latest('id')->first()['id'];
                echo json_encode([
                    'status'                => true,
                    'msg'                   => 'Registro agregado correctamente',
                    'type'                  => 'success',
                    'idtipo_comprobante_'   => $idtipo_comprobante_,
                    'last_id'               => $last_id
                ]);
            }
        }
    }

    public function detail_t(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }


        $id             = $request->input('id');
        $transportistas    = Transportistas::where('id', $id)->first();
        $district       = District::where('codigo', $transportistas->ubigeo)->first();
        $province       = Province::where('codigo', $district->provincia_codigo)->first();
        $department     = Department::where('codigo', $district->departamento_codigo)->first();
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'status'        => true,
            'transportistas'        => $transportistas,
            'district'      => $district,
            'province'      => $province,
            'department'    => $department,
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
        ]);
    }

    public function store_t(Request $request)
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
        $tipo_documento     = $request->input('tipo_documento');
        $dni_ruc            = $request->input('dni_ruc');
        $razon_social       = trim($request->input('razon_social'));
        $direccion          = trim($request->input('direccion'));
        $mtc          = trim($request->input('mtc'));
        $departamento       = $request->input('departamento');
        $provincia          = $request->input('provincia');
        $distrito           = $request->input('distrito');

        if (!empty($departamento)) {
            if (empty($provincia)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } elseif (empty($distrito)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } else {
                Transportistas::where('id', $id)->update([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'mtc'     => mb_strtoupper($mtc),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,

                ]);

                echo json_encode([
                    'status'    => true,
                    'msg'       => 'Registro actualizado correctamente',
                    'type'      => 'success'
                ]);
            }
        }
    }

    public function delete_t(Request $request)
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
        $search_bf  = count(Billing::where('idcliente', $id)->get());
        $search_nv  = count(SaleNote::where('idcliente', $id)->get());

        if ($search_bf > 0 || $search_nv > 0) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se puede eliminar. El cliente tiene ventas realizadas',
                'type'      => 'warning'
            ]);
            return;
        }

        Transportistas::where('id', $id)->delete();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }



    ##++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ## CONDUCTORES
    public function sales_conductores()
    {
        return view('admin.document_avanzados.guias.conductores.list');
    }

    public function index_c()
    {
        $data['type_documents']     = IdentityDocumentType::where('estado', 1)->get();
        return view('admin.conductores.list', $data);
    }

    public function get_c()
    {
        $conductores     = Conductores::orderBy('id', 'DESC')->get();
        return Datatables()
            ->of($conductores)
            ->addColumn('acciones', function ($conductores) {
                $id     = $conductores->id;
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }

    public function load_ubigeo_c()
    {
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
        ]);
    }

    public function search_c(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $type_document      = trim($request->input('type_document'));
        $dni_ruc            = trim($request->input('dni_ruc'));
        $client             = $this->verify__client($dni_ruc);
        //dd($client);

        if (!$client->success) {
            echo json_encode([
                'status'    => false,
                'msg'       => $client->message,
                'type'      => 'warning'
            ]);
        } else {

            $data           = $client->data;
            $nombres        = '';
            $licencia      = '';
            $ubigeo         = NULL;

            // Ubigeo por default de lima
            $ubigeo         = '150101';
            if ($type_document == '2') // DNI
            {
                $nombres        = $data->nombres . ' ' . $data->apellido_paterno . ' ' . $data->apellido_materno;
            } else if ($type_document == '4' && str_starts_with($dni_ruc, '10')) {
                $nombres        = $data->nombre_o_razon_social;
            } else {
                $nombres        = $data->nombre_o_razon_social;
                $licencia      = $data->licencia ?? 0;
                $ubigeo         = $data->ubigeo[2];
            }

            echo json_encode([
                'status'    => true,
                'nombres'   => $nombres,
                'licencia' => $licencia,
                'ubigeo'    => $ubigeo
            ]);
        }
    }

    public function save_c(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $tipo_documento         = $request->input('tipo_documento');
        $idtipo_comprobante_    = ($tipo_documento == "4") ? "1" : "2";
        $dni_ruc                = $request->input('dni_ruc');
        $razon_social           = trim($request->input('razon_social'));
        $direccion              = trim($request->input('direccion'));
        $licencia              = trim($request->input('licencia'));
        $telefono               = trim($request->input('telefono'));
        $departamento           = $request->input('departamento');
        $provincia              = $request->input('provincia');
        $distrito               = $request->input('distrito');
        $search_client          = Client::where('dni_ruc', $dni_ruc)->first();

        if (!empty($search_client)) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'El cliente ya se encuentra registrado',
                'type'      => 'warning'
            ]);
            return;
        }

        if (!empty($departamento)) {
            if (empty($provincia)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } elseif (empty($distrito)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } else {
                Conductores::create([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'licencia'     => mb_strtoupper($licencia),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);

                $last_id            = Conductores::latest('id')->first()['id'];
                echo json_encode([
                    'status'                => true,
                    'msg'                   => 'Registro agregado correctamente',
                    'type'                  => 'success',
                    'idtipo_comprobante_'   => $idtipo_comprobante_,
                    'last_id'               => $last_id
                ]);
            }
        }
    }

    public function detail_c(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }


        $id             = $request->input('id');
        $conductores    = Conductores::where('id', $id)->first();
        $district       = District::where('codigo', $conductores->ubigeo)->first();
        $province       = Province::where('codigo', $district->provincia_codigo)->first();
        $department     = Department::where('codigo', $district->departamento_codigo)->first();
        $departments    = Department::get();
        $provinces      = Province::get();
        $districts      = District::get();

        echo json_encode([
            'status'        => true,
            'conductores'        => $conductores,
            'district'      => $district,
            'province'      => $province,
            'department'    => $department,
            'departments'   => $departments,
            'provinces'     => $provinces,
            'districts'     => $districts
        ]);
    }

    public function store_c(Request $request)
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
        $tipo_documento     = $request->input('tipo_documento');
        $dni_ruc            = $request->input('dni_ruc');
        $razon_social       = trim($request->input('razon_social'));
        $direccion          = trim($request->input('direccion'));
        $licencia          = trim($request->input('licencia'));
        $telefono           = trim($request->input('telefono'));
        $departamento       = $request->input('departamento');
        $provincia          = $request->input('provincia');
        $distrito           = $request->input('distrito');

        if (!empty($departamento)) {
            if (empty($provincia)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } elseif (empty($distrito)) {
                echo json_encode([
                    'status'    => false,
                    'msg'       => 'Ingrese una dirección de ubigeo válida',
                    'type'      => 'warning'
                ]);
            } else {
                Conductores::where('id', $id)->update([
                    'iddoc'         => $tipo_documento,
                    'dni_ruc'       => $dni_ruc,
                    'nombres'       => mb_strtoupper($razon_social),
                    'direccion'     => mb_strtoupper($direccion),
                    'licencia'     => mb_strtoupper($licencia),
                    'codigo_pais'   => 'PE',
                    'ubigeo'        => $distrito,
                    'telefono'      => $telefono
                ]);

                echo json_encode([
                    'status'    => true,
                    'msg'       => 'Registro actualizado correctamente',
                    'type'      => 'success'
                ]);
            }
        }
    }

    public function delete_c(Request $request)
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
        $search_bf  = count(Billing::where('idcliente', $id)->get());
        $search_nv  = count(SaleNote::where('idcliente', $id)->get());

        if ($search_bf > 0 || $search_nv > 0) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'No se puede eliminar. El cliente tiene ventas realizadas',
                'type'      => 'warning'
            ]);
            return;
        }

        Conductores::where('id', $id)->delete();
        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado correctamente',
            'type'      => 'success'
        ]);
    }

    ##++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ## VEHICULOS
    public function sales_providers()
    {
        return view('admin.document_avanzados.guias.vehiculos.home');
    }


    public function get_v()
    {
        $Vehiculos     = Vehiculos::orderBy('id', 'DESC')->get();
        return Datatables()
            ->of($Vehiculos)
            ->addColumn('acciones', function ($Vehiculos) {
                $id     = $Vehiculos->id;
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }


    // Para listar Los Vehiculos

    public function save_v(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
        }

        $num_placa = trim($request->input('num_placa'));
        $tuc = trim($request->input('tuc'));
        $autori_placa_principal = trim($request->input('autori_placa_principal'));
        $placa_secundario = trim($request->input('placa_secundario'));
        $tuc_placa_secundario = trim($request->input('tuc_placa_secundario'));
        $autori_placa_secundario = trim($request->input('autori_placa_secundario'));
        $modelo = trim($request->input('modelo'));
        $marca  = trim($request->input('marca'));

        //dd();

        Vehiculos::create([
            'num_placa' => mb_strtoupper($num_placa),
            'tuc' => mb_strtoupper($tuc),
            'autori_placa_principal' => mb_strtoupper($autori_placa_principal),
            'placa_secundario' => mb_strtoupper($placa_secundario),
            'tuc_placa_secundario' => mb_strtoupper($tuc_placa_secundario),
            'autori_placa_secundario' => mb_strtoupper($autori_placa_secundario),
            'modelo' => mb_strtoupper($modelo),
            'marca' => mb_strtoupper($marca)
        ]);

        return response()->json([
            'status' => true,
            'msg' => 'Registro insertado correctamente',
            'type' => 'success'
        ]);
    }

    public function detail_v(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id       = $request->input('id');
        $vehiculos     = Vehiculos::where('id', $id)->first();
        echo json_encode([

            'status'  => true,
            'vehiculos' => $vehiculos

        ]);
    }

    public function store_v(Request $request)
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
        $num_placa = trim($request->input('num_placa'));
        $tuc = trim($request->input('tuc'));
        $autori_placa_principal = trim($request->input('autori_placa_principal'));
        $placa_secundario = trim($request->input('placa_secundario'));
        $tuc_placa_secundario = trim($request->input('tuc_placa_secundario'));
        $autori_placa_secundario = trim($request->input('autori_placa_secundario'));
        $modelo = trim($request->input('modelo'));
        $marca  = trim($request->input('marca'));

        Vehiculos::where('id', $id)->update([

            'num_placa' => mb_strtoupper($num_placa),
            'tuc' => mb_strtoupper($tuc),
            'autori_placa_principal' => mb_strtoupper($autori_placa_principal),
            'placa_secundario' => mb_strtoupper($placa_secundario),
            'tuc_placa_secundario' => mb_strtoupper($tuc_placa_secundario),
            'autori_placa_secundario' => mb_strtoupper($autori_placa_secundario),
            'modelo' => mb_strtoupper($modelo),
            'marca' => mb_strtoupper($marca)
        ]);

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro actualizado correctamente',
            'type'      => 'success'
        ]);
    }

    public function delete_v(Request $request)
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
        Direccion_Partida::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado con éxito',
            'type'      => 'success'
        ]);
    }


    ##++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ## DIRECCION DE PARTIDAS
    public function sales_expenses()
    {
        return view('admin.document_avanzados.guias.direcciones_partida.home');
    }


    public function get()
    {
        $Direccion_Partida     = Direccion_Partida::orderBy('id', 'DESC')->get();
        return Datatables()
            ->of($Direccion_Partida)
            ->addColumn('acciones', function ($Direccion_Partida) {
                $id     = $Direccion_Partida->id;
                $btn    = '<div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg></button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50 menu-icon"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span> Editar</span>
                                        </a>
                                        <a class="dropdown-item btn-confirm" data-id="' . $id . '" href="javascript:void(0);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50 menu-icon"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span> Eliminar</span>
                                        </a>
                                    </div>
                                </div>';
                return $btn;
            })
            ->rawColumns(['acciones'])
            ->make(true);
    }


    // Para listar Las Direccion De Partida

    public function save1(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
        }

        $direccion = trim($request->input('direccion'));
        $ubigeo = trim($request->input('ubigeo'));

        //dd($direccion, $ubigeo);

        Direccion_Partida::create([
            'direccion' => mb_strtoupper($direccion),
            'ubigeo' => mb_strtoupper($ubigeo)
        ]);

        return response()->json([
            'status' => true,
            'msg' => 'Registro insertado correctamente',
            'type' => 'success'
        ]);
    }

    public function detail(Request $request)
    {
        if (!$request->ajax()) {
            echo json_encode([
                'status'    => false,
                'msg'       => 'Intente de nuevo',
                'type'      => 'warning'
            ]);
            return;
        }

        $id       = $request->input('id');
        $direccion_partida     = Direccion_Partida::where('id', $id)->first();
        echo json_encode([

            'status'  => true,
            'direccion_partida' => $direccion_partida

        ]);
    }

    public function store(Request $request)
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
        $direccion               = trim($request->input('direccion'));
        $ubigeo        = trim($request->input('ubigeo'));

        Direccion_Partida::where('id', $id)->update([

            'direccion'   => mb_strtoupper($direccion),
            'ubigeo'   => mb_strtoupper($ubigeo)
        ]);

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
                'type'      => 'warning'
            ]);
            return;
        }

        $id            = $request->input('id');
        Direccion_Partida::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado con éxito',
            'type'      => 'success'
        ]);
    }
}
