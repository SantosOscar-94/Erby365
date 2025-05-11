<?php

namespace App\Http\Controllers;


use App\Http\Requests\ReclamosQuejasRequest;
use App\Mail\ReclamosMail;
use App\Mail\RespuestaReclamosMail;
use App\Models\Ajustes;
use App\Models\ArchingCash;
use App\Models\Warehouse;
use App\Models\Bill;
use App\Models\Billing;
use App\Models\SaleNote;
use App\Models\Business;
use App\Models\DetailBilling;
use App\Models\PurchaseDescription;
use App\Models\User;
use App\Models\Currency;
use App\Models\IdentityDocumentType;
use App\Models\ReclamosQuejas;
use App\Models\ReporteReclamos;
use App\Models\TypeDocument;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\MockObject\Builder\Identity;

class ReclamosQuejasController extends Controller
{
    public function index()
    {
        $data["users"]          = User::all();
        $data["type_documents"] = TypeDocument::where('id', 1)->orWhere('id', 2)->orWhere('id', 7)->orWhere('id', 6)->get();
        $data["warehouses"]     = Warehouse::all();

        return view('admin.reclamos_quejas.list', $data);
    }

    public function get(Request $request)
    {
        parse_str($request->filters, $filter);
        $fecha_inicial              = $filter['fecha_inicial'];
        $fecha_final                = $filter['fecha_final'];
        $warehouse                  = $filter['warehouse'] ?? null;
        $op_queja_reclamo           = $filter['op_queja_reclamo'] ?? null;

        $user = User::with('roles')->where('id', Auth::user()['id'])->first();
        $role = $user->roles->first();

        if ($role->name == "SUPERADMIN" || $role->name == "ADMIN") {
            $reclamos_quejas     = ReclamosQuejas::select('reclamos_quejas.*')
                ->whereBetween('reclamos_quejas.created_at', [$fecha_inicial, $fecha_final])
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $reclamos_quejas     = ReclamosQuejas::select('reclamos_quejas.*')
                ->where("idusuario", "=", Auth::user()->id)
                ->whereBetween('reclamos_quejas.created_at', [$fecha_inicial, $fecha_final])
                ->orderBy('id', 'DESC')
                ->get();
        }

        if (!empty($warehouse)) {
            $reclamos_quejas = $reclamos_quejas->where('tienda', $warehouse);
        }

        if (!empty($op_queja_reclamo)) {
            $reclamos_quejas = $reclamos_quejas->where('op_queja_reclamo', $op_queja_reclamo);
        }

        return Datatables()
            ->of($reclamos_quejas)
            ->addColumn('ticket', function () {
                return 'ticket';
            })
            ->addColumn('fecha', function ($reclamos_quejas) {
                $fecha = date('d-m-Y H:i:s', strtotime($reclamos_quejas->created_at));
                return $fecha;
            })
            ->addColumn('tipo', function ($reclamos_quejas) {
                return $reclamos_quejas->op_queja_reclamo;
            })
            ->addColumn('tienda', function ($reclamos_quejas) {
                $tienda = $reclamos_quejas->tienda;
                if ($tienda) {
                    return $tienda;
                } else {
                    return 'Tienda Virtual';
                }
            })
            ->addColumn('estado', function ($reclamos_quejas) {
                $estado    = $reclamos_quejas->estado;
                $btn        = '';
                switch ($estado) {
                    case 'Pendiente':
                        $btn .= '<span class="badge bg-danger  text-white">Pendiente</span>';
                        break;

                    case 'Resuelto':
                        $btn .= '<span class="badge bg-success text-white">Resuelto</span>';
                        break;
                }
                return $btn;
            })
            ->addColumn('acciones', function ($reclamos_quejas) {
                $id     = $reclamos_quejas->id;
                $btn    = '<div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 6h18M3 18h18"/></svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn-detail-reclamos_quejas" data-id="' . $id . '" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                        <span> Ver Detalle</span>
                                    </a>
                                    <a class="dropdown-item btn-download" data-id="' . $id . '" href="javascript:void(0);">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        <span>Descargar Reporte</span>
                                    </a>
                                </div>
                            </div>';
                return $btn;
            })
            ->rawColumns(['fecha1', 'fecha2', 'cajero', 'estado', 'acciones'])
            ->make(true);
    }

    public function details()
    {
        $id = request()->input('id');
        $data = ReclamosQuejas::find($id);
        $data["business"] = Business::first();

        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    public function respuestaReclamo(Request $request)
    {
        $id                     = $request->input('reclamo_id');
        $respuesta              = $request->input('respuesta');
        $reclamo                = ReclamosQuejas::find($id);

        $reclamo->estado        = 'Resuelto';
        $reclamo->respuesta     = $respuesta;
        $reclamo->save();

        $data = [
            'fecha_reclamo' => $reclamo->created_at->format('d/m/Y'), // Fecha del reclamo
            'respuesta' => $request->respuesta
        ];


        $correosAdmins = Ajustes::all()->correo;
        Mail::to($reclamo->correo)
            ->cc($correosAdmins)
            ->send(new RespuestaReclamosMail($data));

        return response()->json([
            'status' => true,
            'msg' => 'Reclamo resuelto con éxito',
            'type' => 'success'
        ]);
    }

    public function form()
    {
        $types = IdentityDocumentType::get()->reject(function ($type) {
            return strtolower($type->descripcion_documento) === 'otros';
        });
        $data["identity_type"]      = $types;
        $data["business"]           = Business::first();
        $data["warehouses"]         = Warehouse::all();
        $data["identity_type"]      = $types;
        $data["business"]           = Business::first();
        $data["warehouses"]         = Warehouse::all();
        return view('admin.reclamos_quejas.formulario', $data);
    }

    public function save(ReclamosQuejasRequest $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
        }

        // Recolectamos toda la información en un solo arreglo
        $data = $request->only([
            'tipo_documento',
            'documento_cliente',
            'nombre_cliente',
            'apellido_paterno',
            'apellido_materno',
            'direccion',
            'correo',
            'telefono',
            'edad',
            'padre_madre',
            'fecha_incidente',
            'canal_compra',
            'bien_contratado',
            'monto',
            'tienda',
            'direccion_tienda',
            'tienda',
            'direccion_tienda',
            'descripcion_item',
            'op_queja_reclamo',
            'op_motivo',
            'detalle_reclamo',
            'pedido_realizado_a',
            'observaciones'
        ]);

        $reclamo                            = ReclamosQuejas::create($data);

        // Si existe un archivo de evidencia, lo subimos y actualizamos el registro
        if ($request->hasFile('file_evidencia')) {
            $image                          = $request->file('file_evidencia');
            $imageName                      = $reclamo->id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('reclamos'), $imageName);

            // Generar la URL relativa
            $relativePath = 'reclamos/' . $imageName;

            // Actualizar el campo en la base de datos con la URL relativa
            $reclamo->update(['file_evidencia_path' => $relativePath]);
        }

        $correosAdmins      = Ajustes::pluck('correo')->toArray();

        Mail::to($data['correo'])
            ->cc($correosAdmins)
            ->send(new ReclamosMail($reclamo->id));

        return response()->json([
            'status' => true,
        ], 201);
    }


    public function formCopy($id)
    {

        $data["reclamo"]            = ReclamosQuejas::find($id);
        $data["business"]           = Business::first();
        return view('admin.reclamos_quejas.formulario-copia', $data);
    }
}
