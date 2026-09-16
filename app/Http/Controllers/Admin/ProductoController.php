<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CategoriaProducto;
use App\Models\Admin\Producto;
use App\Models\Admin\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'proveedor', 'inventario']);

        if ($request->filled('categoria')) {
            $query->where('id_categoria', $request->categoria);
        }
        if ($request->filled('marca')) {
            $query->where('marca', 'like', '%' . $request->marca . '%');
        }
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
        }

        $productos   = $query->paginate(15);
        $categorias  = CategoriaProducto::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();

        return view('admin.productos.index', compact('productos', 'categorias', 'proveedores'));
    }

    public function create()
    {
        $categorias  = CategoriaProducto::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();

        return view('admin.productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:150',
            'id_categoria' => 'required|exists:categorias_productos,id_categoria',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'nombre.required'       => 'Por favor complete todos los campos obligatorios.',
            'id_categoria.required' => 'Por favor complete todos los campos obligatorios.',
            'precio_venta.required' => 'Por favor complete todos los campos obligatorios.',
            'foto.mimes'            => 'Formato de imagen no permitido. Use JPG o PNG.',
            'foto.max'              => 'El archivo supera el tamaño máximo permitido de 10 MB.',
        ]);

        // TDLP-017 escenario duplicado
        $existe = Producto::where('nombre', $request->nombre)
            ->where('id_categoria', $request->id_categoria)
            ->exists();

        if ($existe) {
            return back()->withInput()->with('error', 'Este repuesto ya está registrado en el inventario.');
        }

        $producto = Producto::create($request->only([
            'id_categoria', 'id_proveedor', 'nombre', 'codigo',
            'descripcion', 'marca', 'unidad_medida',
            'precio_compra', 'precio_venta', 'stock_minimo',
        ]) + ['activo' => true]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('productos', 'public');
            $producto->fotos()->create(['ruta_foto' => $path]);
        }

        // Crear registro de inventario inicial
        $producto->inventario()->create([
            'cantidad'             => $request->stock_inicial ?? 0,
            'stock_minimo'         => $request->stock_minimo,
            'ultima_actualizacion' => now(),
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto registrado exitosamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'proveedor', 'fotos', 'inventario']);
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias  = CategoriaProducto::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();

        return view('admin.productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'       => 'required|string|max:150',
            'id_categoria' => 'required|exists:categorias_productos,id_categoria',
            'precio_venta' => 'required|numeric|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ], [
            'foto.mimes' => 'Formato de imagen no permitido. Use JPG o PNG.',
            'foto.max'   => 'El archivo supera el tamaño máximo permitido de 10 MB.',
        ]);

        $producto->update($request->only([
            'id_categoria', 'id_proveedor', 'nombre', 'codigo',
            'descripcion', 'marca', 'unidad_medida',
            'precio_compra', 'precio_venta', 'stock_minimo', 'activo',
        ]));

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('productos', 'public');
            $producto->fotos()->create(['ruta_foto' => $path]);
        }

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function desactivar(Producto $producto)
    {
        $producto->update(['activo' => false]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto desactivado exitosamente.');
    }
}
