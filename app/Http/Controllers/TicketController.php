<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Pregunta;
use App\Models\SolucionTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mail;

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
        DB::beginTransaction();
        try {
            $resultado = null;
            if ($request->prioridad == 'ALTO') {
                // buscar pregunta
                $tamanio_str = strlen(trim($request->descripcion));
                $descripcion_comodin = str_replace(" ", "%", $request->descripcion);
                $resultados = Pregunta::where("pregunta", "LIKE", "%$descripcion_comodin%")->get();
                foreach ($resultados as $res) {
                    $tamanio_res_str = strlen(trim($res->pregunta));
                    $elegible = ($tamanio_str * 100) / $tamanio_res_str;
                    Log::debug($elegible);
                    if ($elegible >= 90) {
                        $resultado = $res;
                        break;
                    }
                }
                if (!$resultado) {
                    return response()->JSON([
                        "no_coincidencias" => true,
                        "message" => "No se encontró una solución a su problema. Por favor reformule la descripción de la INCIDENCIA"
                    ], 422);
                }
            }

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

            // VALIDAR TIPO DE PRIORIDAD
            // SI ES ALTO DEBE REGISTRAR LA SOLUCION
            if ($ticket->prioridad == 'ALTO') {
                if ($resultado) {
                    $datos["envio"] = "ENVIADO";
                    $datos["fecha_registro"] = date("Y-m-d");
                    $usuario_tecnico = User::where('id', '!=', 1)->where("tipo", "TÉCNICO")->get()->first();
                    $user_id = 1;
                    if ($usuario_tecnico) {
                        $user_id = $usuario_tecnico->id;
                    }
                    $datos["user_id"] = $user_id;
                    $datos["eliminado"] = 0;
                    $datos["ticket_id"] = $ticket->id;
                    $datos["tipo_incidencia"] = "SOLUCION";
                    $datos["asunto"] = $ticket->asunto;
                    $datos["descripcion"] = $res->respuesta;
                    $datos["envio"] = "ENVIADO";
                    $datos["estado"] = "SOLUCIONADO";
                    $ticket->envio = "RECIBIDO";
                    $ticket->estado = "SOLUCIONADO";
                    $ticket->save();

                    $solucion_ticket = SolucionTicket::create(array_map('mb_strtoupper', $datos));
                    // ENVIO MAIL
                    $empresa = Configuracion::first();
                    if ($solucion_ticket->user->informacion_usuario->correo && $solucion_ticket->user->informacion_usuario->correo != '') {
                        $data = [
                            'asunto' => $solucion_ticket->asunto,
                            'descripcion' => $solucion_ticket->descripcion,
                            'usuario' => $solucion_ticket->user->full_name
                        ];

                        Mail::send('mail.mail', $data, function ($msj) use ($empresa, $ticket) {
                            $email_empresa = \mb_strtolower($empresa->correo ? $empresa->correo : "correosyseventos@gmail.com");
                            $msj->from($email_empresa);
                            $msj->subject($ticket->asunto);
                            $correo_cliente = \mb_strtolower($ticket->user->informacion_usuario->correo);
                            $msj->to($correo_cliente, $ticket->user->full_name);
                        });
                    }
                };
            }
            DB::commit();
            return response()->JSON(['ticket' => $ticket, 'msj' => 'Registro éxitoso']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->JSON(['message' => $e->getMessage()]);
        }
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
