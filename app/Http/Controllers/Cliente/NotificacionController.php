<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Admin\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Mostrar notificaciones del cliente
     */
    public function index(Request $request)
    {
        $query = Notificacion::where('id_usuario_destinatario', Auth::id())
            ->with(['orden.vehiculo', 'vehiculo'])
            ->orderByDesc('created_at');

        // Filtrar según el parámetro
        if ($request->has('filtro') && $request->filtro) {
            if ($request->filtro === 'no-leidas') {
                $query->where('leida', false);
            } elseif ($request->filtro === 'leidas') {
                $query->where('leida', true);
            } else {
                // Filtrar por tipo
                $query->where('tipo', $request->filtro);
            }
        }

        $notificaciones = $query->paginate(15);
        $notificacionesSinLeer = Notificacion::where('id_usuario_destinatario', Auth::id())
            ->where('leida', false)
            ->count();

        return view('cliente.notificaciones', compact('notificaciones', 'notificacionesSinLeer'));
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarLeida(Notificacion $notificacion)
    {
        // Verificar que el usuario sea el destinatario
        abort_if($notificacion->id_usuario_destinatario !== Auth::id(), 403);

        $notificacion->marcarLeida();

        return back()->with('success', 'Notificación marcada como leída');
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas(Request $request)
    {
        Notificacion::where('id_usuario_destinatario', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    /**
     * Obtener contador de notificaciones sin leer (para API/Ajax)
     */
    public function contadorNoLeidas()
    {
        $cantidad = Notificacion::where('id_usuario_destinatario', Auth::id())
            ->where('leida', false)
            ->count();

        return response()->json(['no_leidas' => $cantidad]);
    }
}
