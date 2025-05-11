<?php

namespace App\Http\Controllers;



use App\Models\Ajustes;
use App\Models\ReclamosQuejas;

use Illuminate\Http\Request;

class AjustesController extends Controller
{
    public function index()
    {

        return view('admin.ajustes.home');
    }






    public function get()
    {
        $Ajustes     = Ajustes::orderBy('id', 'DESC')->get();
        return Datatables()
            ->of($Ajustes)
            ->addColumn('acciones', function ($Ajustes) {
                $id     = $Ajustes->id;
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


    // Para listar correo
    public function save(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'msg' => 'Intente de nuevo',
                'type' => 'warning'
            ]);
        }

        // Validación de los campos


        if ($request->hasFile('portada_reclamos')) {
            $path = public_path('assets/img/logo-form-reclamos/');
            $file = $request->file('portada_reclamos');
            $fileName = 'logo_form_reclamos.png';

            if (file_exists($path . $fileName)) {
                unlink($path . $fileName);
            }

            $file->move($path, $fileName);
        }



        return response()->json([
            'status' => true,
            'msg' => 'Registro insertado correctamente',
            'type' => 'success'
        ]);
    }

    public function save1(Request $request)
{
    if (!$request->ajax()) {
        return response()->json([
            'status' => false,
            'msg' => 'Intente de nuevo',
            'type' => 'warning'
        ]);
    }

    $correo = trim($request->input('correo'));
    $responsable = trim($request->input('responsable'));

    //dd($correo, $responsable);

    Ajustes::create([
        'correo' => mb_strtoupper($correo),
        'responsable' => mb_strtoupper($responsable)
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
        $ajustes     = Ajustes::where('id', $id)->first();
        echo json_encode([

     'status'  => true,
     'ajustes' => $ajustes

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
        $correo               = trim($request->input('correo'));
        $responsable        = trim($request->input('responsable'));

        Ajustes::where('id', $id)->update([

            'correo'   => mb_strtoupper($correo),
            'responsable'   => mb_strtoupper($responsable)
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
        Ajustes::where('id', $id)->delete();

        echo json_encode([
            'status'    => true,
            'msg'       => 'Registro eliminado con éxito',
            'type'      => 'success'
        ]);
    }
}
