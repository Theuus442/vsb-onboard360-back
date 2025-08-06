<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GarantirUsuarioAdminParceiro
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (!$usuario || $usuario->papel !== 'admin_parceiro') {
            return response()->json([
                'mensagem' => 'Acesso não autorizado. Apenas administradores da empresa parceira têm permissão.'
            ], 403);
        }

        return $next($request);
    }
}
