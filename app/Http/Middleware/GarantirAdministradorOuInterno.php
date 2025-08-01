<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class GarantirAdministradorOuInterno
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->papel, ['admin', 'interno'])) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Acesso negado. Apenas administradores ou internos podem acessar esta rota.'
        ], 403);
    }
}
