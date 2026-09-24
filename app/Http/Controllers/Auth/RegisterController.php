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
            'password'   => 'required|string|min:6|confirmed',
            'direccion'  => 'nullable|string|max:200',
        ], [
            'nombre.required'      => 'El nombre completo es obligatorio.',
            'documento.required'   => 'El número de identificación o cédula es obligatorio.',
            'documento.unique'     => 'Ya existe una cuenta registrada con este número de identificación.',
            'correo.required'      => 'El correo electrónico es obligatorio.',
            'correo.email'         => 'Ingrese un correo electrónico válido.',
            'correo.unique'        => 'Ya existe una cuenta registrada con este correo electrónico.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ]);

        $usuario = DB::transaction(function () use ($request) {
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

            return $usuario;
        });

        // Iniciar sesión automáticamente y redirigir al portal del cliente
        \Illuminate\Support\Facades\Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Cuenta creada con éxito! Bienvenido a tu portal de cliente.');
    }
}
