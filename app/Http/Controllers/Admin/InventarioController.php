<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Inventario;
use App\Models\Admin\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventario::with(['producto.categoria'])
            ->join('productos', 'inventario.id_producto', '=', 'productos.id_producto');

        if ($request->filled('categoria')) {
            $query->whereHas('producto', fn($q) => $q->where('id_categoria', $request->categoria));
        }
        if ($request->filled('bajo_stock')) {
            $query->whereRaw('inventario.cantidad <= inventario.stock_minimo');
        }
        if ($request->filled('buscar')) {
            $query->whereHas('producto', fn($q) => $q->where('nombre', 'like', '%' . $request->buscar . '%'));
        }

        $inventario    = $query->select('inventario.*')->paginate(15);
        $bajoStock     = Inventario::with('producto')->whereRaw('cantidad <= stock_minimo')->get();

        return view('admin.inventario.index', compact('inventario', 'bajoStock'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:0',
            'motivo'   => 'required|string|max:255',
        ], [
            'cantidad.integer' => 'Los datos ingresados son inválidos. Verifique los campos señalados.',
            'cantidad.min'     => 'Los datos ingresados son inválidos. Verifique los campos señalados.',
        ]);

        $inventario->update([
            'cantidad'             => $request->cantidad,
            'ultima_actualizacion' => now(),
        ]);

        return redirect()->route('admin.inventario.index')
            ->with('success', 'Inventario actualizado correctamente.');
    }
}
