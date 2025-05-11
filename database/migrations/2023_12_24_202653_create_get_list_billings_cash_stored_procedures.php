<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGetListBillingsCashStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_list_billings_cash;");
        DB::unprepared(
            'CREATE PROCEDURE get_list_billings_cash(
                IN idcash BIGINT
            )
            BEGIN
                SELECT billings.*, clients.dni_ruc as dni_ruc, clients.nombres as  nombre_cliente 
                FROM billings 
                INNER JOIN clients ON billings.idcliente = clients.id
                WHERE billings.idtipo_comprobante != 6 AND billings.estado_cpe = 1 AND billings.idcaja = idCash
                ORDER BY billings.id ASC;
            END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_list_billings_cash_stored_procedures');
    }
}
