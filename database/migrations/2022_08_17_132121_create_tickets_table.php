<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("tipo_soporte_id");
            $table->enum("prioridad", ["ALTO", "MEDIO", "BAJO"]);
            $table->text("asunto");
            $table->text("descripcion");
            $table->enum("estado", ["PENDIENTE", "SOLUCIONADO"]);
            $table->boolean("editable")->default(true);
            $table->date("fecha_registro");
            $table->unsignedBigInteger("user_id");
            $table->integer("eliminado");
            $table->timestamps();

            $table->foreign("tipo_soporte_id")->on("tipo_soportes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
