<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_soporte_id', 'prioridad', 'asunto', 'descripcion', 'estado', 'editable', 'fecha_registro', 'envio',
        'user_id', 'eliminado'
    ];

    protected $with = ['archivos', 'solucion_tickets', 'user.informacion_usuario'];

    public function tipo_soporte()
    {
        return $this->belongsTo(TipoSoporte::class, 'tipo_soporte_id');
    }

    public function solucion_tickets()
    {
        return $this->hasMany(SolucionTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function archivos()
    {
        return $this->morphMany(ArchivosTicket::class, 'archivo', 'modelo_tipo', 'modelo_id');
    }

    public function getFechaRegistroAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    // METODOS
    public function registros()
    {
        return $this->solucion_tickets()->where('tipo_incidencia', 'REGISTRO');
    }
    public function soluciones()
    {
        return $this->solucion_tickets()->where('tipo_incidencia', 'SOLUCION');
    }
}
