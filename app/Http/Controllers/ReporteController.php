<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class ReporteController extends Controller
{
    public function usuarios(Request $request)
    {
        $filtro =  $request->filtro;
        $usuarios = User::where('id', '!=', 1)->get();
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $usuarios = User::where('id', '!=', 1)->whereBetween('fecha_registro', [$request->fecha_ini, $request->fecha_fin])->get();
        }

        if ($filtro == 'Tipo de usuario') {
            $request->validate([
                'tipo' => 'required|in:ADMINISTRADOR,PERSONAL,TÉCNICO',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }


    public function registros(Request $request)
    {

        $filtro = $request->filtro;
        $prioridad = $request->prioridad;
        $tipo_soporte_id = $request->tipo_soporte_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $tickets = Ticket::where('tickets.eliminado', 0);

        if ($filtro == 'Prioridad') {
            $request->validate([
                'prioridad' => 'required',
            ]);
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($filtro == 'Tipo de soporte') {
            $request->validate([
                'tipo_soporte_id' => 'required',
            ]);
            $tickets->where('tipo_soporte_id', $tipo_soporte_id);
        }
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $tickets->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $tickets  = $tickets->orderBy('created_at', 'asc')->get();

        $pdf = PDF::loadView('reportes.registros', compact('tickets'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }
    public function soluciones(Request $request)
    {
        $filtro = $request->filtro;
        $prioridad = $request->prioridad;
        $estado = $request->estado;
        $tipo_soporte_id = $request->tipo_soporte_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $tickets = Ticket::where('tickets.eliminado', 0);

        if ($filtro == 'Prioridad') {
            $request->validate([
                'prioridad' => 'required',
            ]);
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($filtro == 'Tipo de soporte') {
            $request->validate([
                'tipo_soporte_id' => 'required',
            ]);
            $tickets->where('tipo_soporte_id', $tipo_soporte_id);
        }

        if ($filtro == 'Estado de ticket') {
            $request->validate([
                'estado' => 'required',
            ]);
            $tickets->where('estado', $estado);
        }
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $tickets->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $tickets  = $tickets->orderBy('created_at', 'asc')->get();

        $pdf = PDF::loadView('reportes.soluciones', compact('tickets'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }
    public function historial_tickets(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $filtro = $request->filtro;
        $user_id = $request->user_id;
        $prioridad = $request->prioridad;
        $estado = $request->estado;
        $tipo_soporte_id = $request->tipo_soporte_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $tickets = Ticket::where('tickets.eliminado', 0)->where('user_id', $user_id);

        if ($filtro == 'Prioridad') {
            $request->validate([
                'prioridad' => 'required',
            ]);
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($filtro == 'Tipo de soporte') {
            $request->validate([
                'tipo_soporte_id' => 'required',
            ]);
            $tickets->where('tipo_soporte_id', $tipo_soporte_id);
        }

        if ($filtro == 'Estado de ticket') {
            $request->validate([
                'estado' => 'required',
            ]);
            $tickets->where('estado', $estado);
        }
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $tickets->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $tickets  = $tickets->orderBy('created_at', 'asc')->get();

        $pdf = PDF::loadView('reportes.historial_tickets', compact('tickets'))->setPaper('legal', 'landscape');
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }
    public function atencion_tickets(Request $request)
    {
        $filtro = $request->filtro;
        $user_id = $request->user_id;
        $estado = $request->estado;
        $tipo_soporte_id = $request->tipo_soporte_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $tickets_bajos = Ticket::where('tickets.eliminado', 0);
        $tickets_medios = Ticket::where('tickets.eliminado', 0);
        $tickets_altos = Ticket::where('tickets.eliminado', 0);
        $categories = ['ALTO', 'MEDIO', 'BAJO'];

        if ($filtro == 'Personal') {
            $request->validate([
                'user_id' => 'required',
            ]);
            $tickets_bajos->where('user_id', $user_id);
            $tickets_medios->where('user_id', $user_id);
            $tickets_altos->where('user_id', $user_id);
        }
        if ($filtro == 'Tipo de soporte') {
            $request->validate([
                'tipo_soporte_id' => 'required',
            ]);
            $tickets_bajos->where('tipo_soporte_id', $tipo_soporte_id);
            $tickets_medios->where('tipo_soporte_id', $tipo_soporte_id);
            $tickets_altos->where('tipo_soporte_id', $tipo_soporte_id);
        }

        if ($filtro == 'Estado de ticket') {
            $request->validate([
                'estado' => 'required',
            ]);
            $tickets_bajos->where('estado', $estado);
            $tickets_medios->where('estado', $estado);
            $tickets_altos->where('estado', $estado);
        }
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $tickets_bajos->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
            $tickets_medios->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
            $tickets_altos->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $tickets_bajos  = $tickets_bajos->where('prioridad', 'BAJO')->get();
        $tickets_medios  = $tickets_medios->where('prioridad', 'MEDIO')->get();
        $tickets_altos  = $tickets_altos->where('prioridad', 'ALTO')->get();

        $total_atenciones_bajo = 0;
        $total_atenciones_medio = 0;
        $total_atenciones_alto = 0;

        foreach ($tickets_bajos as $t) {
            $total_atenciones_bajo += (int)count($t->soluciones);
        }

        foreach ($tickets_medios as $t) {
            $total_atenciones_medio += (int)count($t->soluciones);
        }
        foreach ($tickets_altos as $t) {
            $total_atenciones_alto += (int)count($t->soluciones);
        }

        $data = [(int)$total_atenciones_alto, (int)$total_atenciones_medio, (int)$total_atenciones_bajo];
        return response()->JSON(['categories' => $categories, 'data' => $data]);
    }
    public function estado_tickets(Request $request)
    {
        $filtro = $request->filtro;
        $user_id = $request->user_id;
        $prioridad = $request->prioridad;
        $tipo_soporte_id = $request->tipo_soporte_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $tickets_solucionados = Ticket::where('tickets.eliminado', 0);
        $tickets_pendientes = Ticket::where('tickets.eliminado', 0);
        $categories = ['SOLUCIONADOS', 'PENDIENTES'];

        if ($filtro == 'Personal') {
            $request->validate([
                'user_id' => 'required',
            ]);
            $tickets_solucionados->where('user_id', $user_id);
            $tickets_pendientes->where('user_id', $user_id);
        }
        if ($filtro == 'Tipo de soporte') {
            $request->validate([
                'tipo_soporte_id' => 'required',
            ]);
            $tickets_solucionados->where('tipo_soporte_id', $tipo_soporte_id);
            $tickets_pendientes->where('tipo_soporte_id', $tipo_soporte_id);
        }

        if ($filtro == 'Prioridad') {
            $request->validate([
                'prioridad' => 'required',
            ]);
            $tickets_solucionados->where('prioridad', $prioridad);
            $tickets_pendientes->where('prioridad', $prioridad);
        }
        if ($filtro == 'Rango de fechas') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $tickets_solucionados->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
            $tickets_pendientes->whereBetween('fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $tickets_solucionados  = $tickets_solucionados->where('estado', 'SOLUCIONADO')->get();
        $tickets_pendientes  = $tickets_pendientes->where('estado', 'PENDIENTE')->get();

        $total_atenciones_solucionados = 0;
        $total_atenciones_pendientes = 0;

        foreach ($tickets_solucionados as $t) {
            $total_atenciones_solucionados += (int)count($t->soluciones);
        }

        foreach ($tickets_pendientes as $t) {
            $total_atenciones_pendientes += (int)count($t->soluciones);
        }
        $data = [(int)$total_atenciones_solucionados, (int)$total_atenciones_pendientes];
        return response()->JSON(['categories' => $categories, 'data' => $data]);
    }
}
