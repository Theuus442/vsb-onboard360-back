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

    public function listar(int $pagina = 1, int $limit = 15, ?string $filtro = null)
    {
        $query = Usuario::query();

        if ($filtro) {
            $query->where('nome', 'like', "%{$filtro}%")
                ->orWhere('email', 'like', "%{$filtro}%");
        }

        $paginaObj = $query->paginate($limit, ['*'], 'page', $pagina);

        return [
            'data' => $paginaObj->items(),
            'pagination' => [
                'page' => $paginaObj->currentPage(),
                'limit' => $paginaObj->perPage(),
                'total' => $paginaObj->total(),
                'totalPages' => $paginaObj->lastPage(),
            ],
        ];
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
