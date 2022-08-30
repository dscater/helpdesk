<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public $validacion = [
        'tipo_soporte_id' => 'required',
        'pregunta' => 'required|min:4',
        'respuesta' => 'required|min:4'
    ];

    public function index(Request $request)
    {
        $faqs = Faq::all();
        return response()->JSON(['faqs' => $faqs, 'total' => count($faqs)]);
    }

    public function lista(Request $request)
    {
        $faqs = Faq::select('faqs.*');
        if ($request->tipo_soporte_id != "") {
            $faqs = $faqs->where('tipo_soporte_id', $request->tipo_soporte_id);
        }
        $faqs = $faqs->get();
        return response()->JSON(['faqs' => $faqs, 'total' => count($faqs)]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion);
        $request['fecha_registro'] = date('Y-m-d');
        $faq = Faq::create(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'msj' => 'Registro éxitoso',
            'faq' => $faq
        ]);
    }

    public function update(Faq $faq, Request $request)
    {
        $request->validate($this->validacion);
        $faq->update(array_map('mb_strtoupper', $request->all()));
        return response()->JSON([
            'msj' => 'Actualización éxitosa',
            'faq' => $faq
        ]);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->JSON(['msj' => 'Registro eliminado']);
    }

    public function getAreas()
    {
        return response()->JSON(Faq::all());
    }
}
