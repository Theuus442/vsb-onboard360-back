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
        $user = Auth::user();

        if ($user && $user->papel === 'parceiro' && $user->parceiro_id) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Acesso negado. Você não tem permissão para acessar esta rota ou não está vinculado a um parceiro.'
        ], 403);
    }
}
