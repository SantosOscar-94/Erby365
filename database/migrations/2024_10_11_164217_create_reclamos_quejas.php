<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReclamosquejas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reclamos_quejas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->bigInteger('documento_cliente');
            $table->string('nombre_cliente');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('direccion');
            $table->string('correo');
            $table->string('telefono');
            $table->integer('edad');
            $table->string('padre_madre')->nullable();
            $table->date('fecha_incidente');
            $table->enum('canal_compra', ['Tienda Virtual', 'Tienda Fisica']);
            $table->enum('bien_contratado', ['Producto', 'Servicio']);
            $table->decimal('monto', 18, 2);
            $table->string('tienda')->nullable();
            $table->string('direccion_tienda')->nullable();
            $table->text('descripcion_item');
            $table->string('op_queja_reclamo');
            $table->string('op_motivo');
            $table->text('detalle_reclamo');
            $table->string('pedido_realizado_a');
            $table->text('observaciones')->nullable();
            $table->string('file_evidencia_path')->nullable();
            $table->enum('estado', ['Pendiente', 'Resuelto'])->default('Pendiente');
            $table->text('respuesta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reclamos_quejas');
    }
}
