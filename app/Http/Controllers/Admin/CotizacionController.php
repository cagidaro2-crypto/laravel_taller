<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cotizacion;
use App\Models\Admin\Factura;
use App\Models\Admin\Servicio;
use App\Models\Admin\Producto;
use App\Models\Cliente\Cliente;
use App\Models\Tecnico\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::with(['cliente.usuario', 'vehiculo', 'usuario']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('cliente')) {
            $query->whereHas('cliente.usuario', fn($q) => $q->where('nombre', 'like', '%' . $request->cliente . '%'));
        }

        $cotizaciones = $query->orderByDesc('fecha')->paginate(15);

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes  = Cliente::with('usuario')->get();
        $vehiculos = Vehiculo::with('cliente.usuario')->get();
        $servicios = Servicio::where('activo', true)->get();
        $productos = Producto::where('activo', true)->get();

        return view('admin.cotizaciones.create', compact('clientes', 'vehiculos', 'servicios', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente'        => 'required|exists:clientes,id_cliente',
            'fecha'             => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha',
            'servicios'         => 'nullable|array',
            'productos'         => 'nullable|array',
        ], [
            'id_cliente.required' => 'Complete todos los campos requeridos para generar la cotización.',
        ]);

        if (empty($request->servicios) && empty($request->productos)) {
            return back()->withInput()->with('error', 'Complete todos los campos requeridos para generar la cotización.');
        }

        DB::transaction(function () use ($request) {
            $cotizacion = Cotizacion::create([
                'id_cliente'        => $request->id_cliente,
                'id_vehiculo'       => $request->id_vehiculo,
                'id_usuario'        => Auth::id(),
                'fecha'             => $request->fecha,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'estado'            => 'Pendiente',
                'subtotal'          => $request->subtotal ?? 0,
                'impuesto'          => $request->impuesto ?? 0,
                'total'             => $request->total ?? 0,
                'observaciones'     => $request->observaciones,
            ]);

            if ($request->filled('servicios')) {
                foreach ($request->servicios as $s) {
                    $cotizacion->servicios()->create([
                        'id_servicio' => $s['id'],
                        'cantidad'    => $s['cantidad'],
                        'precio'      => $s['precio'],
                        'subtotal'    => $s['subtotal'],
                    ]);
                }
            }

            if ($request->filled('productos')) {
                foreach ($request->productos as $p) {
                    $cotizacion->productos()->create([
                        'id_producto'    => $p['id'],
                        'cantidad'       => $p['cantidad'],
                        'precio_unitario'=> $p['precio'],
                        'subtotal'       => $p['subtotal'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.cotizaciones.index')
            ->with('success', 'Cotización creada exitosamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        $cotizacione->load(['cliente.usuario', 'vehiculo', 'usuario', 'servicios.servicio', 'productos.producto']);
        return view('admin.cotizaciones.show', compact('cotizacione'));
    }

    // TDLP-010 escenario 3: Convertir cotización aprobada en factura
    public function convertirFactura(Cotizacion $cotizacione)
    {
        if ($cotizacione->estado !== 'Aprobada') {
            return back()->with('error', 'Solo se pueden convertir cotizaciones aprobadas.');
        }

        DB::transaction(function () use ($cotizacione) {
            $numero = 'F-' . str_pad(Factura::count() + 1, 6, '0', STR_PAD_LEFT);

            Factura::create([
                'id_cliente'     => $cotizacione->id_cliente,
                'id_orden'       => null,
                'numero_factura' => $numero,
                'fecha'          => now()->toDateString(),
                'subtotal'       => $cotizacione->subtotal,
                'impuesto'       => $cotizacione->impuesto,
                'total'          => $cotizacione->total,
                'estado'         => 'Pendiente',
            ]);
        });

        return redirect()->route('admin.cotizaciones.show', $cotizacione)
            ->with('success', 'Factura generada exitosamente.');
    }

    // TDLP-010 escenario 6: Rechazar cotización
    public function rechazar(Cotizacion $cotizacione)
    {
        $cotizacione->update(['estado' => 'Rechazada']);

        return redirect()->route('admin.cotizaciones.show', $cotizacione)
            ->with('success', 'Cotización marcada como Rechazada.');
    }
}
