<?php

namespace App\Http\Controllers;

use App\Models\ChecklistParceiro;
use Illuminate\Http\Request;

class ChecklistParceiroController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = ChecklistParceiro::with(['parceiro', 'tarefaPadrao']);

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pendente', 'em_andamento', 'concluido']);
        }

        $checklist = $query->orderBy('updated_at', 'desc')->paginate(10);

        return response()->json($checklist);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'parceiro_id' => 'required|exists:parceiros,id',
            'tarefa_id' => 'required|exists:tarefas_padrao,id',
            'status' => 'required|in:pendente,em_andamento,concluido,rejeitado',
            'observacao' => 'nullable|string',
            'atualizado_por' => 'required|exists:usuarios,id',
        ]);

        $checklist = ChecklistParceiro::create($validatedData);

        return response()->json($checklist, 201);
    }

    public function show($id)
    {
        $checklist = ChecklistParceiro::with(['parceiro', 'tarefaPadrao', 'atualizadoPor'])->findOrFail($id);

        return response()->json($checklist);
    }

    public function update(Request $request, $id)
    {
        $checklist = ChecklistParceiro::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:pendente,em_andamento,concluido,rejeitado',
            'observacao' => 'nullable|string',
            'atualizado_por' => 'required|exists:usuarios,id',
        ]);

        $checklist->update($validatedData);

        return response()->json($checklist);
    }

    public function destroy($id)
    {
        $checklist = ChecklistParceiro::findOrFail($id);
        $checklist->delete();

        return response()->json(null, 204);
    }
}
