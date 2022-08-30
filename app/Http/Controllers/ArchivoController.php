<?php

namespace App\Http\Controllers;

use App\Models\ArchivosTicket;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    public function store(Request $request)
    {
        $maximo = 10;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $size = $file->getSize();
            if ($size / 1048576  > $maximo) {
                return response()->JSON(['errors' => ['msj' => 'El archivo no debe ser mayor a ' . $maximo . ' MB']], 422);
            }
            return response()->JSON($file);
        }
        return response()->JSON(['errors' => ['listArchivos' => 'No se cargo ningún archivo']], 422);
    }

    public function destroy(ArchivosTicket $archivo)
    {
        \File::delete(public_path() . '/archivos/' . $archivo->archivo);
        $archivo->delete();
        return response()->JSON(['sw' => true, 'msj' => 'Eliminación éxitosa']);
    }

    public function descargar(ArchivosTicket $archivo)
    {
        return response()->download(public_path() . '/archivos/' . $archivo->archivo, $archivo->archivo);
    }
}
