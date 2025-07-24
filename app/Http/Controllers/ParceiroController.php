<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Http\Requests\ParceiroRequest;
use App\Http\Requests\UpdateParceiroRequest;

class ParceiroController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::with(['checklists', 'documentos'])->paginate(10);
        return response()->json($parceiros);
    }

    public function store(ParceiroRequest $request)
    {
        $validatedData = $request->validated();
        $parceiro = Parceiro::create($validatedData);

        return response()->json($parceiro, 201);
    }

    public function show($id)
    {
        $parceiro = Parceiro::with(['checklists', 'documentos'])->findOrFail($id);

        return response()->json($parceiro);
    }

    public function update(UpdateParceiroRequest $request, $id)
    {
        $parceiro = Parceiro::findOrFail($id);
        $validatedData = $request->validated();

        $parceiro->update($validatedData);

        return response()->json($parceiro);
    }

    public function destroy(string $id)
    {
        $parceiro = Parceiro::findOrFail($id);
        $parceiro->status = 'inativo';
        $parceiro->save();

        return response()->json(['message' => 'Parceiro marcado como inativo com sucesso!']);
    }
}
