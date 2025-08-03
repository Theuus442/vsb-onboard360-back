<?php

namespace App\Services;

use App\Models\Parceiro;
use App\Models\Usuario;

class ParceiroService
{
    public function listar()
    {
        return Parceiro::withCount('usuarios')
            ->with([
                'responsavel:id,id,nome,email',
                'usuarios:id,id,nome,email,parceiro_id',
                'checklists:id,id,parceiro_id',
                'documentos:id,id,nome,status,parceiro_id'
            ])
            ->paginate(10);
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

    public function listarUsuariosDoParceiro($parceiroId)
    {
        $parceiro = Parceiro::with('usuarios')->findOrFail($parceiroId);
        return $parceiro->usuarios;
    }

    public function adicionarUsuarioAoParceiro($parceiroId, $dados)
    {
        $dados['papel'] = 'parceiro';
        $dados['parceiro_id'] = $parceiroId;
        return Usuario::create($dados);
    }

    public function removerUsuarioDoParceiro($parceiroId, $usuarioId)
    {
        $usuario = Usuario::where('parceiro_id', $parceiroId)->where('id', $usuarioId)->firstOrFail();
        $usuario->delete();
    }
}
