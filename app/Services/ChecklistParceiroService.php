<?php

namespace App\Services;

use App\Models\ChecklistParceiro;

class ChecklistParceiroService
{
    public function listar($status = null)
    {
        $query = ChecklistParceiro::with(['parceiro', 'tarefaPadrao']);

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pendente', 'em_andamento', 'concluido']);
        }

        return $query->orderBy('updated_at', 'desc')->paginate(10);
    }

    public function listarFiltradoPorSetor($parceiroId, $departamento, $status = null)
    {
        $query = ChecklistParceiro::with(['parceiro', 'tarefaPadrao'])
            ->where('parceiro_id', $parceiroId)
            ->where(function ($query) use ($departamento) {
                $query->whereNull('setor_destino')
                    ->orWhere('setor_destino', $departamento);
            });

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pendente', 'em_andamento', 'concluido']);
        }

        return $query->orderBy('updated_at', 'desc')->paginate(10);
    }

    public function criar(array $dados)
    {
        return ChecklistParceiro::create($dados);
    }

    public function buscarPorId($id)
    {
        return ChecklistParceiro::with(['parceiro', 'tarefaPadrao', 'atualizadoPor'])->findOrFail($id);
    }

    public function atualizar($id, array $dados)
    {
        $checklist = ChecklistParceiro::findOrFail($id);
        $checklist->update($dados);
        return $checklist;
    }

    public function deletar($id)
    {
        $checklist = ChecklistParceiro::findOrFail($id);
        $checklist->delete();
        return true;
    }
}
