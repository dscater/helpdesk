<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index(Request $request)
    {
        $personals = Personal::with('user.informacion_usuario')->get();

        return response()->JSON([
            'personals' => $personals
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|unique:personals,user_id'
        ]);
        $personal = Personal::create($request->all());
        return response()->JSON(['personal' => $personal->load('user')->load('user.informacion_usuario'), 'msj' => 'Registro éxitoso']);
    }
}
