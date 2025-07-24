<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistParceiroRequest;
use App\Http\Requests\UpdateChecklistParceiroRequest;
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

    public function store(ChecklistParceiroRequest $request)
    {
        $validatedData = $request->validated();

        $checklist = ChecklistParceiro::create($validatedData);

        return response()->json($checklist, 201);
    }

    public function show($id)
    {
        $checklist = ChecklistParceiro::with(['parceiro', 'tarefaPadrao', 'atualizadoPor'])->findOrFail($id);

        return response()->json($checklist);
    }

    public function update(UpdateChecklistParceiroRequest $request, $id)
    {
        $checklist = ChecklistParceiro::findOrFail($id);

        $validatedData = $request->validated();

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
