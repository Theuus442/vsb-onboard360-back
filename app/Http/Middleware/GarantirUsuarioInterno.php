<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GarantirUsuarioInterno
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->papel === 'interno') {
            return $next($request);
        }
        return response()->json([
            'message' => 'Acesso negado. Você não tem permissão para acessar esta rota.'
        ], 403);
    }
}
