<?php

namespace App\Http\Controllers;

use App\Models\InformacionUsuario;
use App\Models\Personal;
use App\Models\ProgramaAcademico;
use App\Models\SolucionTicket;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $validacion = [
        'nombre' => 'required|min:4',
        'paterno' => 'required|min:4',
        'ci' => 'required|numeric|digits_between:4, 20|unique:informacion_usuarios,ci',
        'ci_exp' => 'required',
        'dir' => 'required|min:4',
        'cel' => 'required|min:4',
        'fono' => 'required|min:4',
        'tipo' => 'required',
    ];

    public $mensajes = [
        'nombre.required' => 'Esta campo es obligatorio',
        'nombre.min' => 'Debes ingressar al menos 4 carácteres',
        'paterno.required' => 'Esta campo es obligatorio',
        'paterno.min' => 'Debes ingresar al menos 4 carácteres',
        'ci.required' => 'Esta campo es obligatorio',
        'ci.numeric' => 'Debes ingresar un valor númerico',
        'ci.unique' => 'Este número de C.I. ya fue registrado',
        'ci_exp.required' => 'Esta campo es obligatorio',
        'dir.required' => 'Esta campo es obligatorio',
        'dir.min' => 'Debes ingresar al menos 4 carácteres',
        'fono.required' => 'Esta campo es obligatorio',
        'fono.min' => 'Debes ingresar al menos 4 carácteres',
        'cel.required' => 'Esta campo es obligatorio',
        'cel.min' => 'Debes ingresar al menos 4 carácteres',
        'tipo.required' => 'Esta campo es obligatorio',
    ];

    public $permisos = [
        'ADMINISTRADOR' => [
            'usuarios.index',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.destroy',

            'personals.index',
            'personals.create',
            'personals.edit',
            'personals.destroy',

            'tipo_soportes.index',
            'tipo_soportes.create',
            'tipo_soportes.edit',
            'tipo_soportes.destroy',

            'tickets.index',
            'tickets.create',
            'tickets.edit',
            'tickets.destroy',

            'faqs.index',
            'faqs.create',
            'faqs.edit',
            'faqs.destroy',

            'configuracion.index',
            'configuracion.edit',

            'reportes.usuarios',
            'reportes.registros',
            'reportes.soluciones',
            'reportes.historial_tickets',
            'reportes.atencion_tickets',
            'reportes.estado_tickets',
        ],
        'TÉCNICO' => [
            'tickets.index',
            'tickets.edit',
            'tickets.destroy',

            'faqs.index',
            'faqs.create',
            'faqs.edit',
            'faqs.destroy',

            'reportes.historial_tickets',
            'reportes.atencion_tickets',
            'reportes.estado_tickets',
        ],
        'PERSONAL' => [
            'tickets.index',
            'tickets.create',
            'tickets.edit',
            'tickets.destroy',

            'faqs.lista',
        ],
    ];


    public function index(Request $request)
    {
        $usuarios = User::with('informacion_usuario')->where('id', '!=', 1)->get();
        return response()->JSON(['usuarios' => $usuarios, 'total' => count($usuarios)], 200);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,png|max:2048';
        }

        if ($request->tipo == 'PERSONAL') {
            $this->validacion['correo'] = 'required|email|unique:informacion_usuarios,correo';
        } elseif ($request->correo && $request->correo != "") {
            $this->validacion['correo'] = 'nullable|email|unique:informacion_usuarios,correo';
        } else {
            unset($request["correo"]);
        }

        $request->validate($this->validacion, $this->mensajes);
        $cont = 0;
        do {
            $nombre_usuario = User::getNombreUsuario($request->nombre, $request->paterno);
            if ($cont > 0) {
                $nombre_usuario = $nombre_usuario . $cont;
            }
            $request['usuario'] = $nombre_usuario;
            $cont++;
        } while (User::where('usuario', $nombre_usuario)->get()->first());
        $request['password'] = 'NoNulo';
        $request['fecha_registro'] = date('Y-m-d');

        // CREAR EL USER
        $nuevo_usuario = User::create(array_map('mb_strtoupper', $request->only('usuario', 'password', 'tipo', 'fecha_registro')));
        $nuevo_usuario->password = Hash::make($request->ci);
        $nuevo_usuario->save();

        // REGISTRAR INFORMACIÓN DE USUARIO
        $informacion_usuario = new InformacionUsuario(array_map('mb_strtoupper', $request->except('usuario', 'password', 'tipo', 'fecha_registro', 'foto')));
        $informacion_usuario->user_id = $nuevo_usuario->id;
        $informacion_usuario->foto = 'default.png';
        if ($request->hasFile('foto')) {
            $file = $request->foto;
            $nom_foto = time() . '_' . $informacion_usuario->user->usuario . '.' . $file->getClientOriginalExtension();
            $informacion_usuario->foto = $nom_foto;
            $file->move(public_path() . '/imgs/users/', $nom_foto);
        }
        $informacion_usuario->correo = mb_strtolower($informacion_usuario->correo);
        $informacion_usuario->save();
        return response()->JSON([
            'sw' => true,
            'usuario' => $nuevo_usuario->load('informacion_usuario'),
            'msj' => 'El registro se realizó de forma correcta',
        ], 200);
    }

    public function update(Request $request, User $usuario)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,png|max:2048';
        }

        if ($request->tipo == 'PERSONAL') {
            $this->validacion['correo'] = 'required|email|unique:informacion_usuarios,correo,' . $usuario->informacion_usuario->id;
        } elseif ($request->correo && $request->correo != "") {
            $this->validacion['correo'] = 'nullable|email|unique:informacion_usuarios,correo,' . $usuario->informacion_usuario->id;
        } else {
            unset($request['correo']);
        }

        $this->validacion['ci'] = 'required|min:4|numeric|unique:informacion_usuarios,ci,' . $usuario->informacion_usuario->id;
        $request->validate($this->validacion, $this->mensajes);

        // TIPO DE USUARIO
        if ($request->tipo != 'PERSONAL') {
            $usuario->update(array_map('mb_strtoupper', $request->only('tipo')));
        }
        $informacion_usuario = $usuario->informacion_usuario;
        $informacion_usuario->update(array_map('mb_strtoupper', $request->except('tipo', 'foto')));
        if ($informacion_usuario->correo == "") {
            $informacion_usuario->correo = NULL;
        }

        if ($request->hasFile('foto')) {
            $antiguo = $informacion_usuario->foto;
            if ($antiguo != 'default.png') {
                \File::delete(public_path() . '/imgs/users/' . $antiguo);
            }
            $file = $request->foto;
            $nom_foto = time() . '_' . $informacion_usuario->user->usuario . '.' . $file->getClientOriginalExtension();
            $informacion_usuario->foto = $nom_foto;
            $file->move(public_path() . '/imgs/users/', $nom_foto);
        }
        $informacion_usuario->correo = mb_strtolower($informacion_usuario->correo);
        $informacion_usuario->save();
        return response()->JSON([
            'sw' => true,
            'usuario' => $usuario->load('informacion_usuario'),
            'msj' => 'El registro se actualizó de forma correcta'
        ], 200);
    }

    public function show(User $usuario)
    {
        return response()->JSON([
            'sw' => true,
            'usuario' => $usuario
        ], 200);
    }

    public function actualizaContrasenia(User $usuario, Request $request)
    {
        $request->validate([
            'password_actual' => ['required', function ($attribute, $value, $fail) use ($usuario, $request) {
                if (!\Hash::check($request->password_actual, $usuario->password)) {
                    return $fail(__('La contraseña no coincide con la actual.'));
                }
            }],
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required|min:5'
        ]);

        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return response()->JSON([
            'sw' => true,
            'msj' => 'La contraseña se actualizó correctamente'
        ], 200);
    }

    public function actualizaFoto(User $usuario, Request $request)
    {
        if ($usuario->id != 1) {
            if ($request->hasFile('foto')) {
                $antiguo = $usuario->informacion_usuario->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/users/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $usuario->usuario . '.' . $file->getClientOriginalExtension();
                $usuario->informacion_usuario->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $usuario->informacion_usuario->save();
            return response()->JSON([
                'sw' => true,
                'usuario' => $usuario,
                'msj' => 'Foto actualizada con éxito'
            ], 200);
        } else {
            return response()->JSON([
                'sw' => true,
                'usuario' => $usuario,
                'msj' => 'La foto de este usuario no es actualizable'
            ], 200);
        }
    }

    public function destroy(User $usuario)
    {
        $antiguo = $usuario->foto;
        if ($antiguo != 'default.png') {
            \File::delete(public_path() . '/imgs/users/' . $antiguo);
        }
        $usuario->informacion_usuario()->delete();
        if ($usuario->personal) {
            $usuario->personal()->delete();
        }
        $usuario->delete();
        return response()->JSON([
            'sw' => true,
            'msj' => 'El registro se eliminó correctamente'
        ], 200);
    }

    public function getPermisos(User $usuario)
    {
        $tipo = $usuario->tipo;
        return response()->JSON($this->permisos[$tipo]);
    }

    public function getInfoBox()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];
        if (in_array('usuarios.index', $this->permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Usuarios',
                'cantidad' => count(User::where('id', '!=', 1)->get()),
                'color' => 'bg-info',
                'icon' => 'fas fa-users',
            ];
        }

        if (in_array('personals.index', $this->permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Personal',
                'cantidad' => count(Personal::all()),
                'color' => 'bg-warning',
                'icon' => 'fas fa-users',
            ];
        }

        if (in_array('tickets.index', $this->permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'Tickets',
                'cantidad' => count(Ticket::where('tickets.eliminado', 0)->get()),
                'color' => 'bg-primary',
                'icon' => 'el-icon-notebook-2',
            ];

            if (Auth::user()->tipo != 'TÉCNICO') {
                $array_infos[] = [
                    'label' => 'Mis Tickets',
                    'cantidad' => count(Ticket::where('tickets.eliminado', 0)->where('user_id', Auth::user()->id)->get()),
                    'color' => 'bg-success',
                    'icon' => 'el-icon-notebook-2',
                ];
            } else {
                $array_infos[] = [
                    'label' => 'Mis soluciones',
                    'cantidad' => count(SolucionTicket::where('tipo_incidencia', 'SOLUCION')->where('user_id', Auth::user()->id)->get()),
                    'color' => 'bg-success',
                    'icon' => 'el-icon-notebook-2',
                ];
            }
        }

        return response()->JSON($array_infos);
    }

    public function userActual()
    {
        return response()->JSON(Auth::user());
    }

    public function getEstudiantes()
    {
        return response()->JSON(User::where('tipo', 'ESTUDIANTE')->get());
    }

    public function getDocentes()
    {
        return response()->JSON(User::where('tipo', 'DOCENTE')->get());
    }

    public function getUsuario(User $usuario)
    {
        return response()->JSON($usuario);
    }
}
