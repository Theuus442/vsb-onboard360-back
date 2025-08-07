<?php

namespace App\Services;

use App\Models\Parceiro;
use App\Models\Usuario;

class ParceiroService
{
    public function listar($limit = 10, $page = 1, $search = '')
    {
        // Converte para inteiros seguros
        $limit = max(1, (int) $limit);
        $page = max(1, (int) $page);

        $query = Parceiro::withCount('usuarios')
            ->with([
                'responsavel:id,id,nome,email',
                'usuarios:id,id,nome,email,parceiro_id',
                'checklists:id,id,parceiro_id',
                'documentos:id,id,nome,status,parceiro_id'
            ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome_fantasia', 'like', "%{$search}%")
                    ->orWhere('razao_social', 'like', "%{$search}%")
                    ->orWhere('cnpj', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function criar(array $dados)
    {
        return Parceiro::create($dados);
    }

    public function buscarPorId($id)
    {
        return Parceiro::with(['checklists', 'documentos'])->findOrFail($id);
    }

    public function atualizar($id, array $dados)
    {
        $parceiro = Parceiro::findOrFail($id);
        $parceiro->update($dados);
        return $parceiro;
    }

    public function inativar($id)
    {
        $parceiro = Parceiro::findOrFail($id);
        $parceiro->status = 'inativo';
        $parceiro->save();
        return $parceiro;
    }

    public function toggleStatus($id)
    {
        $parceiro = Parceiro::findOrFail($id);
        $parceiro->status = $parceiro->status === 'ativo' ? 'inativo' : 'ativo';
        $parceiro->save();
        return $parceiro;
    }

    public function listarUsuariosDoParceiro($parceiroId)
    {
        $parceiro = Parceiro::with('usuarios')->findOrFail($parceiroId);
        return $parceiro->usuarios;
    }

    public function adicionarUsuarioAoParceiro($parceiroId, $dados)
    {
        $dados['papel'] = 'parceiro';
        $dados['parceiro_id'] = $parceiroId;

        if (isset($dados['senha'])) {
            $dados['senha'] = bcrypt($dados['senha']);
        }

        return Usuario::create($dados);
    }

    public function removerUsuarioDoParceiro($parceiroId, $usuarioId)
    {
        $usuario = Usuario::where('parceiro_id', $parceiroId)
            ->where('id', $usuarioId)
            ->firstOrFail();

        $usuario->delete();
    }
}
