<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolucionTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solucion_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("ticket_id");
            $table->unsignedBigInteger("user_id");
            $table->enum('tipo_incidencia', ["REGISTRO", "SOLUCION"]);
            $table->text("asunto");
            $table->text("descripcion");
            $table->enum("envio", ["ENVIADO", "RECIBIDO"]);
            $table->enum("estado", ["PENDIENTE", "SOLUCIONADO"]);
            $table->timestamps();
            $table->foreign("ticket_id")->on("tickets")->references("id");
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solucion_tickets');
    }
}
