<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformacionUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("nombre");
            $table->string("paterno");
            $table->string("materno");
            $table->string("ci");
            $table->string("ci_exp");
            $table->string("dir", 255);
            $table->string('correo')->unique()->nullable();
            $table->string("fono", 255)->nullable();
            $table->string("cel", 255)->nullable();
            $table->string("foto", 255)->nullable();

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
        Schema::dropIfExists('informacion_usuarios');
    }
}
