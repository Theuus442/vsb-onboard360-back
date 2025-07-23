<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use Illuminate\Http\Request;

class ParceiroController extends Controller
{

    public function index()
    {
        $parceiros = Parceiro::with(['checklists', 'documentos'])->paginate(10);
        return response()->json($parceiros);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nome_fantasia' => 'required|string|max:50',
            'razao_social' => 'required|string|max:150',
            'cnpj' => 'required|string|max:20|unique:parceiros,cnpj',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:parceiros,email',
            'responsavel_id' => 'nullable|integer|exists:usuario,id',
            'status' => 'required|in:ativo,inativo,suspenso',
        ]);

        $parceiro = Parceiro::create($validateData);

        return response()->json($parceiro, 201);
    }

    public function show($id)
    {
        $parceiro = Parceiro::with(['checklists', 'documentos'])->findOrFail($id);

        return response()->json($parceiro);
    }

    public function update(Request $request, $id)
    {
        $parceiro = Parceiro::findOrFail($id);

        $validatedData = $request->validate([
            'nome_fantasia' => 'required|string|max:50',
            'razao_social' => 'required|string|max:150',
            'cnpj' => 'required|string|max:20|unique:parceiros,cnpj,' . $id,
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:parceiros,email,' . $id,
            'responsavel_id' => 'nullable|integer|exists:usuarios,id',
            'status' => 'required|in:ativo,inativo,suspenso',
        ]);

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
