<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReclamosQuejasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_documento' => 'required|string|max:255',
            'documento_cliente' => 'required|integer|min:1',
            'nombre_cliente' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|regex:/^\d{9,15}$/', // Valida un teléfono con entre 9 y 15 dígitos
            'edad' => 'required|integer|min:0|max:120',
            'padre_madre' => 'nullable|string|max:255',
            'fecha_incidente' => 'required|date|before_or_equal:today',
            'canal_compra' => 'required|in:Tienda Virtual,Tienda Fisica',
            'bien_contratado' => 'required|in:Producto,Servicio',
            'monto' => 'required|numeric|min:0',
            'tienda' => 'nullable|required_if:canal_compra,Tienda Fisica|string|max:255',
            'direccion_tienda' => 'nullable|required_if:canal_compra,Tienda Fisica|string|max:255',
            'descripcion_item' => 'required|string',
            'op_queja_reclamo' => 'required|string|max:255',
            'op_motivo' => 'required|string|max:255',
            'detalle_reclamo' => 'required|string',
            'pedido_realizado_a' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
            'file_evidencia_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Archivos opcionales (máx. 2MB)
        ];
    }
}
