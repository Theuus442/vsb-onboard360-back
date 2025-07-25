<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function criar(array $dados)
    {
        $dados['senha'] = Hash::make($dados['senha']);
        return Usuario::create($dados);
    }

    public function listar()
    {
        return Usuario::all();
    }

    public function buscarPorId($id)
    {
        return Usuario::findOrFail($id);
    }

    public function atualizar($id, array $dados)
    {
        $usuario = Usuario::findOrFail($id);

        if (isset($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        }

        $usuario->update($dados);
        return $usuario;
    }

    public function deletar($id)
    {
        $usuario = Usuario::findOrFail($id);
        return $usuario->delete();
    }
}
