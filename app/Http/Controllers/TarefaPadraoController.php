<?php

namespace App\Http\Controllers;

use App\Models\TarefaPadrao;
use Illuminate\Http\Request;

class TarefaPadraoController extends Controller
{
    public function index()
    {
        $tarefas = TarefaPadrao::paginate(10);

        return response()->json($tarefas);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'setor_responsavel' => 'required|string|max:100',
            'obrigatoria' => 'required|boolean',
            'ativa' => 'required|boolean'
        ]);

        $tarefa = TarefaPadrao::create($validatedData);

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

    public function update(Request $request, $id)
    {
        $tarefa = TarefaPadrao::find($id);

        if (!$tarefa) {
            return response()->json(['message' => 'Tarefa não enconrada'], 404);
        }

        $validatedData = $request->validate([
            'titulo' => 'sometimes|required|string|max:150',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'setor_responsavel' => 'sometimes|required|string|max:100',
            'obrigatoria' => 'required|boolean',
            'ativa' => 'required|boolean',
        ]);

        $tarefa->update($validatedData);

        return response()->json($tarefa);
    }

    public function destroy($id)
    {
        $tarefa = TarefaPadrao::find($id);

        if(!$tarefa) {
            return response()->json(['message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->ativa = false;
        $tarefa->save();

        return response()->json(['message' => 'Tarefa desativada com sucesso!']);
    }
}
