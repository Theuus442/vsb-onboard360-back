<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('parceiro')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($documentos);
    }

    public function store(DocumentoRequest $request)
    {
        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('documentos', 'public');
        } else {
            return response()->json(['erro' => 'Arquivo não enviado'], 422);
        }

        $documento = Documento::create([
            'parceiro_id' => $request->input('parceiro_id'),
            'nome' => $request->input('nome'),
            'arquivo' => $path,
            'status' => $request->input('status', 'pendente'),
        ]);

        return response()->json($documento, 201);
    }

    public function show(string $id)
    {
        $documento = Documento::with('parceiro')->findOrFail($id);
        return response()->json($documento);
    }

    public function update(UpdateDocumentoRequest $request, $id)
    {
        $documento = Documento::findOrFail($id);
        $documento->update($request->only('status'));

        return response()->json(['message' => 'Status atualizado', 'documento' => $documento]);
    }

    public function destroy(string $id)
    {
        $documento = Documento::findOrFail($id);

        if ($documento->arquivo && Storage::disk('public')->exists($documento->arquivo)) {
            Storage::disk('public')->delete($documento->arquivo);
        }

        $documento->delete();

        return response()->json(['message' => 'Documento excluído']);
    }

    public function download($id)
    {
        $documento = Documento::findOrFail($id);

        if (!Storage::disk('public')->exists($documento->arquivo)) {
            return response()->json(['message' => 'Arquivo não encontrado.'], 404);
        }

        return response()->download(storage_path('app/public/' . $documento->arquivo));
    }
}
