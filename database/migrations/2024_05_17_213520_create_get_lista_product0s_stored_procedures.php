<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateGetListaProduct0sStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_lista_productos;");
        DB::unprepared(
            'CREATE PROCEDURE get_lista_productos()
            BEGIN
            SELECT products.*, stock_products.cantidad as cantidad  , warehouses.descripcion as descrip
            FROM stock_products
            INNER JOIN products ON stock_products.idproducto = products.id
            INNER JOIN warehouses ON stock_products.idalmacen = warehouses.id
            
                ORDER BY id DESC;
            END;');


             

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('get_lista_product0s_stored_procedures');
    }
}
