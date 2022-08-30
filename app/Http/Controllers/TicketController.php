<?php

namespace App\Http\Controllers;

use App\Models\SolucionTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public $validacion = [
        'tipo_soporte_id' => 'required',
        'asunto' => 'required',
        'prioridad' => 'required',
        'descripcion' => 'required|min:4',
    ];
    public function index(Request $request)
    {
        $search = $request->search;
        $prioridad = $request->prioridad;
        $tipo_soporte = $request->tipo_soporte;

        $tickets = Ticket::select("tickets.*")
            ->with('user')
            ->with('tipo_soporte')
            ->with('archivos')
            ->join('tipo_soportes', 'tickets.tipo_soporte_id', '=', 'tipo_soportes.id')
            ->where('tickets.eliminado', 0);
        if ($prioridad) {
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($search) {
            $tickets->where(DB::raw('CONCAT(tickets.asunto," ", tickets.descripcion)'), 'LIKE', "%$search%");
        }
        if ($tipo_soporte) {
            $tickets->where('tipo_soportes.nombre', $tipo_soporte);
        }
        $tickets  = $tickets->orderBy('created_at', 'desc')->paginate(50);
        return response()->JSON(['tickets' => $tickets]);
    }

    public function mis_tickets(Request $request)
    {
        $search = $request->search;
        $prioridad = $request->prioridad;
        $tipo_soporte = $request->tipo_soporte;

        $tickets = Ticket::select("tickets.*")
            ->with('user')
            ->with('tipo_soporte')
            ->with('archivos')
            ->join('tipo_soportes', 'tickets.tipo_soporte_id', '=', 'tipo_soportes.id')
            ->where('tickets.eliminado', 0);
        $tickets->where('tickets.user_id', Auth::user()->id);

        if ($prioridad) {
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($search) {
            $tickets->where(DB::raw('CONCAT(tickets.asunto," ", tickets.descripcion)'), 'LIKE', "%$search%");
        }
        if ($tipo_soporte) {
            $tickets->where('tipo_soportes.nombre', $tipo_soporte);
        }
        $tickets  = $tickets->orderBy('created_at', 'desc')->paginate(50);
        return response()->JSON(['tickets' => $tickets]);
    }

    public function tickets_eliminados(Request $request)
    {
        $search = $request->search;
        $prioridad = $request->prioridad;
        $tipo_soporte = $request->tipo_soporte;

        $tickets = Ticket::select("tickets.*")
            ->with('user')
            ->with('tipo_soporte')
            ->with('archivos')
            ->join('tipo_soportes', 'tickets.tipo_soporte_id', '=', 'tipo_soportes.id')
            ->where('tickets.eliminado', 1);

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $tickets->where('tickets.user_id', Auth::user()->id);
        }

        if ($prioridad) {
            $tickets->where('tickets.prioridad', $prioridad);
        }
        if ($search) {
            $tickets->where(DB::raw('CONCAT(tickets.asunto," ", tickets.descripcion)'), 'LIKE', "%$search%");
        }
        if ($tipo_soporte) {
            $tickets->where('tipo_soportes.nombre', $tipo_soporte);
        }
        $tickets  = $tickets->orderBy('created_at', 'desc')->paginate(50);
        return response()->JSON(['tickets' => $tickets]);
    }

    public function listaIncidencias(Ticket $ticket)
    {
        if (Auth::user()->tipo == 'ADMINISTRADOR') {
            DB::update("UPDATE solucion_tickets SET envio='RECIBIDO' WHERE ticket_id = $ticket->id AND envio ='ENVIADO'");
        } else {
            $user = Auth::user();
            if (Auth::user()->tipo == 'TÉCNICO') {
                DB::update("UPDATE solucion_tickets SET envio='RECIBIDO' WHERE ticket_id = $ticket->id AND envio ='ENVIADO' AND tipo_incidencia='REGISTRO' AND user_id != $user->id");
            }
            if (Auth::user()->tipo == 'PERSONAL') {
                DB::update("UPDATE solucion_tickets SET envio='RECIBIDO' WHERE ticket_id = $ticket->id AND envio ='ENVIADO' AND tipo_incidencia='SOLUCION' AND user_id != $user->id");
            }
        }
        $incidencias = SolucionTicket::where('ticket_id', $ticket->id)->get();
        return response()->JSON(['incidencias' => $incidencias]);
    }

    public function store(Request $request)
    {
        $listArchivos = $request->listArchivos;
        if ($listArchivos) {
            $this->validacion['listArchivos'] = 'required|array|min:1|max:10';
            $this->validacion['listArchivos.*'] = 'file|max:10240';
            // $this->validacion['listArchivos.*'] = 'file|max:32768';
            $messages = [];
            foreach ($request->listArchivos as $key => $value) {
                $messages['listArchivos.' . $key . '.max'] = 'El archivo "' . $value->getClientOriginalName() . '" no debe ser mayor que :max kilobytes';
            }
        }

        if (count($messages) > 0) {
            $request->validate($this->validacion, $messages);
        } else {
            $request->validate($this->validacion);
        }

        // REGISTRAR EL TICKET
        $request["envio"] = 'ENVIADO';
        $request["fecha_registro"] = date('Y-m-d');
        $request["user_id"] = Auth::user()->id;
        $request["eliminado"] = 0;
        $ticket = Ticket::create(array_map('mb_strtoupper', $request->except('listArchivos')));

        if ($listArchivos) {
            foreach ($request->listArchivos as $key => $value) {
                $nombre_archivo = random_int(1, 20) . random_int(1, 20) . time() . $ticket->id . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . '/archivos/', $nombre_archivo);
                $ticket->archivos()->create([
                    'archivo' => $nombre_archivo,
                    'nombre_original' => $value->getClientOriginalName()
                ]);
            }
        }

        return response()->JSON(['ticket' => $ticket, 'msj' => 'Registro éxitoso']);
    }

    public function show(Ticket $ticket)
    {
        return response()->JSON($ticket);
    }

    public function update(Ticket $ticket, Request $request)
    {
        $listArchivos = $request->listArchivos;
        $messages = [];
        if ($listArchivos) {
            $this->validacion['listArchivos'] = 'required|array|min:1|max:10';
            $this->validacion['listArchivos.*'] = 'file|max:10240';
            // $this->validacion['listArchivos.*'] = 'file|max:32768';
            foreach ($request->listArchivos as $key => $value) {
                $messages['listArchivos.' . $key . '.max'] = 'El archivo "' . $value->getClientOriginalName() . '" no debe ser mayor que :max kilobytes';
            }
        }

        if (count($messages) > 0) {
            $request->validate($this->validacion, $messages);
        } else {
            $request->validate($this->validacion);
        }
        $ticket->update(array_map('mb_strtoupper', $request->except('listArchivos')));

        if ($listArchivos) {
            foreach ($request->listArchivos as $key => $value) {
                $nombre_archivo = random_int(1, 20) . random_int(1, 20) . time() . $ticket->id . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . '/archivos/', $nombre_archivo);
                $ticket->archivos()->create([
                    'archivo' => $nombre_archivo,
                    'nombre_original' => $value->getClientOriginalName()
                ]);
            }
        }

        return response()->JSON(['ticket' => $ticket, 'msj' => 'Actualización éxitosa']);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->eliminado = 1;
        $ticket->save();
        return response()->JSON(['msj' => 'Registro eliminado']);
    }

    public function restaura_ticket(Ticket $ticket)
    {
        $ticket->eliminado = 0;
        $ticket->save();
        return response()->JSON(['msj' => 'Registro restaurado']);
    }

    public function cantidadTicketsSinVer()
    {
        $tickets = Ticket::where("envio", 'ENVIADO')->get();
        return response()->JSON(count($tickets));
    }

    public function setEstadoEnvio(Ticket $ticket, Request $request)
    {
        if ($ticket->user_id != Auth::user()->id && Auth::user()->tipo != 'PERSONAL') {
            $ticket->envio = $request->envio;
            $ticket->save();
        }
        return response()->JSON($ticket);
    }
}
