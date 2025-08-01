<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $usuario->createToken('token_acesso')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nome'         => 'required|string|max:255',
            'email'        => 'required|email|unique:usuarios,email',
            'senha'        => 'required|string|min:6',
            'papel'        => 'required|in:admin,interno,parceiro',
            'departamento' => 'nullable|string|max:100',
        ]);

        $usuario = Usuario::create([
            'nome'         => $request->nome,
            'email'        => $request->email,
            'senha'        => $request->senha,
            'papel'        => $request->papel,
            'departamento' => $request->departamento,
            'parceiro_id'  => $request->parceiro_id,
        ]);

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'usuario' => $usuario
        ], 201);
    }
}
