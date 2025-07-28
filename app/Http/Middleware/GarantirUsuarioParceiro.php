<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GarantirUsuarioParceiro
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->papel === 'parceiro') {
            return $next($request);
        }
        return response()->json([
            'message' => 'Acesso negado. Você não tem permissão para acessar esta rota.'
        ], 403);
    }
}
