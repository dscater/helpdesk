<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivosTicket extends Model
{
    use HasFactory;
    protected $fillable = [
        'modelo_tipo', 'modelo_id', 'archivo', 'nombre_original'
    ];

    protected $appends = ['url', 'url_destroy', 'url_descarga', 'size'];

    public function archivo()
    {
        return $this->morphTo(__FUNCTION__, 'modelo_tipo', 'modelo_id');
    }

    // mutadores
    public function getUrlAttribute()
    {
        return asset('archivos/' . $this->archivo);
    }

    public function getUrlDestroyAttribute()
    {
        return route('archivos.destroy', $this->id);
    }

    public function getUrlDescargaAttribute()
    {
        return route('archivos.descargar', $this->id);
    }

    public function getSizeAttribute()
    {
        $size = \File::size(public_path() . '/archivos/' . $this->archivo);
        $precision = 2;
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return 0;
    }
}
