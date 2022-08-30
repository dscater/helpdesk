<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolucionTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'user_id', 'tipo_incidencia', 'asunto',
        'descripcion', 'envio', 'estado',
    ];

    protected $with = ['archivos', 'user.informacion_usuario'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function archivos()
    {
        return $this->morphMany(ArchivosTicket::class, 'archivo', 'modelo_tipo', 'modelo_id');
    }
}
