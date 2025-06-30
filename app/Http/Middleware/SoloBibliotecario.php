<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoloBibliotecario
{
    public function handle(Request $request, Closure $next)
    {
        if (session('rol_id') != 1) {
            abort(403, 'No autorizado');
        }
        return $next($request);
    }
}
