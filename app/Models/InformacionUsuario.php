<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionUsuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'paterno', 'materno', 'nombre', 'ci', 'ci_exp',
        'dir', 'correo', 'cel', 'fono', 'foto',
    ];

    protected $appends = ['full_name', 'full_ci', 'path_image'];

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getPathImageAttribute()
    {
        return asset('imgs/users/' . $this->foto);
    }

    // RELACIONES
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
