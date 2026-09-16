<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Rol;
use App\Models\Admin\Usuario;
use App\Models\Cliente\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'documento'  => 'required|string|max:30|unique:clientes,documento',
            'correo'     => 'required|email|max:150|unique:usuarios,correo',
            'telefono'   => 'nullable|string|max:20',
            'password'   => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
            'direccion'  => 'nullable|string|max:200',
        ], [
            'nombre.required'      => 'Por favor complete todos los campos obligatorios.',
            'documento.required'   => 'Por favor complete todos los campos obligatorios.',
            'documento.unique'     => 'Ya existe una cuenta registrada con este correo o identificación.',
            'correo.required'      => 'Por favor complete todos los campos obligatorios.',
            'correo.email'         => 'Ingrese un correo electrónico válido.',
            'correo.unique'        => 'Ya existe una cuenta registrada con este correo o identificación.',
            'password.required'    => 'Por favor complete todos los campos obligatorios.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.',
            'password.regex'       => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ]);

        DB::transaction(function () use ($request) {
            $rolCliente = Rol::where('nombre_rol', 'Cliente')->firstOrFail();

            $usuario = Usuario::create([
                'id_rol'   => $rolCliente->id_rol,
                'nombre'   => $request->nombre,
                'correo'   => $request->correo,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'activo'   => true,
            ]);

            Cliente::create([
                'id_usuario' => $usuario->id_usuario,
                'documento'  => $request->documento,
                'direccion'  => $request->direccion,
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registro exitoso. Ya puede iniciar sesión con sus credenciales.');
    }
}
