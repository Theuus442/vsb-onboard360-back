<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();

        return response()->json($usuarios);
    }

    public function store(UsuarioRequest $request)
    {
        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'papel' => $request->papel,
        ]);

        return response()->json($usuario, 201);
    }

    public function show(int $id)
    {
        $usuario = Usuario::findOrFail($id);

        return response()->json($usuario);
    }

    public function update(UpdateUsuarioRequest $request, int $id)
    {
        $usuario = Usuario::findOrFail($id);

        $usuario->nome = $request->input('nome', $usuario->nome);
        $usuario->email = $request->input('email', $usuario->email);
        $usuario->papel = $request->input('papel', $usuario->papel);

        if ($request->filled('senha')) {
            $usuario->senha = Hash::make($request->senha);
        }

        $usuario->save();

        return response()->json($usuario);
    }

    public function destroy(int $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(['message' => 'Usuário removido']);
    }
}
