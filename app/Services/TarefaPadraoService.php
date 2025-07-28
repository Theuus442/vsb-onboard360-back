<?php

namespace App\Services;

use App\Models\TarefaPadrao;

class TarefaPadraoService
{
    public function getAllPaginated($perPage = 10)
    {
        return TarefaPadrao::paginate($perPage);
    }

    public function findByIdOrFail(int $id)
    {
        return TarefaPadrao::findOrFail($id);
    }

    public function create(array $data)
    {
        return TarefaPadrao::create($data);
    }

    public function update(int $id, array $data)
    {
        $tarefa = $this->findByIdOrFail($id);
        $tarefa->update($data);
        return $tarefa;
    }

    public function deactivate(int $id) {
        $tarefa = $this->findByIdOrFail($id);
        $tarefa->ativa = false;
        $tarefa->save();
        return $tarefa;
    }
}
