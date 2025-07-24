<?php

namespace App\Http\Controllers;

use App\Models\TarefaPadrao;
use App\Http\Requests\TarefaPadraoRequest;
use App\Http\Requests\UpdateTarefaPadraoRequest;

class TarefaPadraoController extends Controller
{
    public function index()
    {
        $tarefas = TarefaPadrao::paginate(10);
        return response()->json($tarefas);
    }

    public function store(TarefaPadraoRequest $request)
    {
        $tarefa = TarefaPadrao::create($request->validated());
        return response()->json($tarefa, 201);
    }

    public function show($id)
    {
        $tarefa = TarefaPadrao::find($id);

        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        return response()->json($tarefa);
    }

    public function update(UpdateTarefaPadraoRequest $request, $id)
    {
        $tarefa = TarefaPadrao::find($id);

        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->update($request->validated());
        return response()->json($tarefa);
    }

    public function destroy($id)
    {
        $tarefa = TarefaPadrao::find($id);

        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->ativa = false;
        $tarefa->save();

        return response()->json(['message' => 'Tarefa desativada com sucesso!']);
    }
}
