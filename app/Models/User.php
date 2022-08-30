<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario',
        'password',
        'tipo',
        'fecha_registro',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $appends = ['full_name', 'full_ci', 'path_image'];

    public static function getNombreUsuario($nom, $apep)
    {
        //determinando el nombre de usuario inicial del 1er_nombre+apep+tipoUser
        $nombre_user = substr(mb_strtoupper($nom), 0, 1); //inicial 1er_nombre
        $nombre_user .= mb_strtoupper($apep);

        return $nombre_user;
    }
    public function getFullNameAttribute()
    {
        if ($this->informacion_usuario) {
            return $this->informacion_usuario->nombre . ' ' . $this->informacion_usuario->paterno . ($this->informacion_usuario->materno != NULL && $this->informacion_usuario->materno != '' ? ' ' . $this->informacion_usuario->materno : '');
        }
        return $this->usuario;
    }

    public function getFullCiAttribute()
    {
        if ($this->informacion_usuario) {
            return $this->informacion_usuario->ci . ' ' . $this->informacion_usuario->ci_exp;
        }
        return "";
    }

    public function getPathImageAttribute()
    {
        if ($this->informacion_usuario) {
            return asset('imgs/users/' . $this->informacion_usuario->foto);
        }
        return asset('imgs/users/default.png');
    }

    // RELACIONES
    public function informacion_usuario()
    {
        return $this->hasOne(InformacionUsuario::class, 'user_id');
    }

    public function personal()
    {
        return $this->hasOne(Personal::class, 'user_id');
    }
}
