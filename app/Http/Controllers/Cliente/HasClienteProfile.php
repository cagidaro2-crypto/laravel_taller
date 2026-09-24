<?php

namespace App\Http\Controllers\Cliente;

use App\Models\Cliente\Cliente;
use Illuminate\Support\Facades\Auth;

trait HasClienteProfile
{
    protected function getCliente(): Cliente
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if (!$user->cliente) {
            return Cliente::firstOrCreate(
                ['id_usuario' => $user->id_usuario],
                [
                    'documento' => 'CLI-' . str_pad($user->id_usuario, 5, '0', STR_PAD_LEFT),
                    'direccion' => null,
                ]
            );
        }

        return $user->cliente;
    }

    protected function clienteId(): int
    {
        return $this->getCliente()->id_cliente;
    }
}
