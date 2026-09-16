<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::paginate(15);
        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => 'required|string|max:30|unique:proveedores,documento',
            'correo'    => 'nullable|email|max:150|unique:proveedores,correo',
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:200',
        ], [
            'nombre.required'     => 'Debe completar todos los campos obligatorios.',
            'documento.unique'    => 'Ya existe un proveedor registrado con este NIT o correo.',
            'correo.unique'       => 'Ya existe un proveedor registrado con este NIT o correo.',
        ]);

        Proveedor::create($request->only(['nombre', 'documento', 'telefono', 'correo', 'direccion']) + ['activo' => true]);

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor registrado exitosamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load('productos');
        return view('admin.proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => 'required|string|max:30|unique:proveedores,documento,' . $proveedor->id_proveedor . ',id_proveedor',
            'correo'    => 'nullable|email|max:150|unique:proveedores,correo,' . $proveedor->id_proveedor . ',id_proveedor',
        ]);

        $proveedor->update($request->only(['nombre', 'documento', 'telefono', 'correo', 'direccion', 'activo']));

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Datos del proveedor actualizados correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
