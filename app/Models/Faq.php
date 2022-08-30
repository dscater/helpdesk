<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_soporte_id', 'pregunta', 'respuesta', 'fecha_registro'];

    protected $with = ['tipo_soporte'];

    public function tipo_soporte()
    {
        return $this->belongsTo(TipoSoporte::class, 'tipo_soporte_id');
    }
}
