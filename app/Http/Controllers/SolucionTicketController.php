<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Personal;
use App\Models\SolucionTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class SolucionTicketController extends Controller
{
    public $validacion = [
        'asunto' => 'required',
        'descripcion' => 'required|min:4',
    ];
    public function store(Request $request)
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

        if (Auth::user()->tipo == 'PERSONAL') {
            $request["tipo_incidencia"] = 'REGISTRO';
        } elseif (Auth::user()->tipo == 'TÉCNICO') {
            $request["tipo_incidencia"] = 'SOLUCION';
        } elseif ($request['tipo_incidencia'] == 'SOLUCION') {
            $this->validacion['estado'] = "required";
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
        $request["ticket_id"] = $request->ticket_id;
        $solucion_ticket = SolucionTicket::create(array_map('mb_strtoupper', $request->except('listArchivos')));

        if ($solucion_ticket->tipo_incidencia == 'SOLUCION') {
            $ticket = $solucion_ticket->ticket;
            $ticket->estado = $solucion_ticket->estado;
            $ticket->save();
        }

        if ($listArchivos) {
            foreach ($request->listArchivos as $key => $value) {
                $nombre_archivo = random_int(1, 20) . random_int(1, 20) . time() . $solucion_ticket->id . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . '/archivos/', $nombre_archivo);
                $solucion_ticket->archivos()->create([
                    'archivo' => $nombre_archivo,
                    'nombre_original' => $value->getClientOriginalName()
                ]);
            }
        }

        if ($request['tipo_incidencia'] == 'SOLUCION') {
            // ENVIO MAIL
            $data = [
                'asunto' => $solucion_ticket->asunto,
                'descripcion' => $solucion_ticket->descripcion,
                'usuario' => $solucion_ticket->user->full_name
            ];

            $personals = Personal::with('user.informacion_usuario')->get();
            $empresa = Configuracion::first();

            $files = [];
            foreach ($solucion_ticket->archivos as $archivo) {
                $files[] = public_path('archivos/' . $archivo->archivo);
            }

            foreach ($personals as $p) {
                if ($p->user->informacion_usuario->correo && $p->user->informacion_usuario->correo != '') {
                    Mail::send('mail.mail', $data, function ($msj) use ($empresa, $ticket, $p, $files) {
                        $email_empresa = \mb_strtolower($empresa->correo ? $empresa->correo : "correosyseventos@gmail.com");
                        $msj->from($email_empresa);
                        $msj->subject($ticket->asunto);
                        $correo_cliente = \mb_strtolower($p->user->informacion_usuario->correo);
                        $msj->to($correo_cliente, $p->full_name);
                        foreach ($files as $file) {
                            $msj->attach($file);
                        }
                    });
                }
            }
        }

        return response()->JSON(['solucion_ticket' => $solucion_ticket, 'msj' => 'Registro éxitoso']);
    }

    public function show(SolucionTicket $solucion_ticket)
    {
        return response()->JSON($solucion_ticket);
    }

    public function update(SolucionTicket $solucion_ticket, Request $request)
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
        $solucion_ticket->update(array_map('mb_strtoupper', $request->except('listArchivos')));

        if ($solucion_ticket->tipo_incidencia == 'SOLUCION') {
            $ticket = $solucion_ticket->ticket;
            $ticket->estado = $solucion_ticket->estado;
            $ticket->save();
        }

        if ($listArchivos) {
            foreach ($request->listArchivos as $key => $value) {
                $nombre_archivo = random_int(1, 20) . random_int(1, 20) . time() . $solucion_ticket->id . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . '/archivos/', $nombre_archivo);
                $solucion_ticket->archivos()->create([
                    'archivo' => $nombre_archivo,
                    'nombre_original' => $value->getClientOriginalName()
                ]);
            }
        }

        return response()->JSON(['solucion_ticket' => $solucion_ticket, 'msj' => 'Actualización éxitosa']);
    }

    public function destroy(SolucionTicket $solucion_ticket)
    {
        $solucion_ticket->delete();
        return response()->JSON(['msj' => 'Registro eliminado']);
    }
}
