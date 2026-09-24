<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Si es POST a /login, loguear los datos
        if ($request->isMethod('post') && $request->path() === 'login') {
            Log::info('DEBUG LOGIN REQUEST', [
                'method' => $request->method(),
                'path' => $request->path(),
                'correo' => $request->input('correo'),
                'has_password' => $request->has('password'),
                'csrf_token_present' => $request->has('_token'),
                'all_inputs' => $request->except('password', '_token'),
            ]);
        }

        return $next($request);
    }
}
