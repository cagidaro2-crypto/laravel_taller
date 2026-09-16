<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Rol;
use App\Models\Admin\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('rol');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('correo', 'like', '%' . $request->buscar . '%');
        }

        $usuarios = $query->paginate(15);
        $roles    = Rol::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'correo'    => 'required|email|max:150|unique:usuarios,correo',
            'id_rol'    => 'required|exists:roles,id_rol',
            'telefono'  => 'nullable|string|max:20',
            'password'  => 'required|min:8|confirmed',
            'documento' => 'nullable|string|max:30',
        ], [
            'nombre.required'  => 'Debe completar todos los campos obligatorios antes de continuar.',
            'correo.unique'    => 'Ya existe un usuario registrado con este número de identificación.',
            'id_rol.required'  => 'Debe completar todos los campos obligatorios antes de continuar.',
            'password.required'=> 'Debe completar todos los campos obligatorios antes de continuar.',
        ]);

        $usuario = Usuario::create([
            'id_rol'   => $request->id_rol,
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'activo'   => true,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    public function show(Usuario $usuario)
    {
        $usuario->load('rol', 'cliente');
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:150|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario',
            'id_rol'   => 'required|exists:roles,id_rol',
            'telefono' => 'nullable|string|max:20',
        ], [
            'nombre.required' => 'Los datos ingresados contienen errores. Por favor corrija los campos señalados.',
            'correo.email'    => 'Los datos ingresados contienen errores. Por favor corrija los campos señalados.',
        ]);

        $usuario->update([
            'id_rol'   => $request->id_rol,
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'telefono' => $request->telefono,
            'activo'   => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Información actualizada correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        // Verificar registros activos (TDLP-003 escenario 8)
        $tieneActivos = $usuario->ordenesTrabajo()->exists()
            || $usuario->citas()->exists()
            || $usuario->cotizaciones()->exists();

        if ($tieneActivos) {
            return back()->with('error', 'No es posible eliminar este usuario porque tiene registros activos asociados. Finalice o reasigne los registros antes de continuar.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
