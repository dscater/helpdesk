<?php

namespace App\Http\Controllers;

use App\Models\TipoSoporte;
use Illuminate\Http\Request;

class TipoSoporteController extends Controller
{
    public $validacion = [
        'nombre' => 'required|min:4|unique:tipo_soportes,nombre',
        'descripcion' => 'nullable|min:4'
    ];

    public function index(Request $request)
    {
        $tipo_soportes = TipoSoporte::all();
        return response()->JSON(['tipo_soportes' => $tipo_soportes, 'total' => count($tipo_soportes)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion);
        $request['fecha_registro'] = date('Y-m-d');
        $tipo_soporte = TipoSoporte::create(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'msj' => 'Registro éxitoso',
            'tipo_soporte' => $tipo_soporte
        ]);
    }

    public function update(TipoSoporte $tipo_soporte, Request $request)
    {
        $this->validacion['nombre'] = 'required|min:4|unique:tipo_soportes,nombre,' . $tipo_soporte->id;
        $request->validate($this->validacion);
        $tipo_soporte->update(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'msj' => 'Actualización éxitosa',
            'tipo_soporte' => $tipo_soporte
        ]);
    }

    public function destroy(TipoSoporte $tipo_soporte)
    {
        $tipo_soporte->delete();
        return response()->JSON(['msj' => 'Registro eliminado']);
    }

    public function getAreas()
    {
        return response()->JSON(TipoSoporte::all());
    }
}
