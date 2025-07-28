<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;
use App\Services\DocumentoService;
use InvalidArgumentException;

class DocumentoController extends Controller
{

    protected $service;

    public function __construct(DocumentoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $documentos = $this->service->listar();

        return response()->json($documentos);
    }

    public function store(DocumentoRequest $request)
    {
        try {
            $documento = $this->service->criar($request->validated(), $request->file('arquivo'));
            return response()->json($documento, 201);
        } catch (InvalidArgumentException $erro) {
            return response()->json(['erro' => $erro->getMessage()], 422);
        }
    }

    public function show($id)
    {
        $documento = $this->service->buscar($id);
        return response()->json($documento);
    }

    public function update(UpdateDocumentoRequest $request, $id)
    {
        $documento = $this->service->atualizarStatus($id, $request->input('status'));
        return response()->json(['message' => 'Status atualizado', 'documento' => $documento]);
    }

    public function destroy(string $id)
    {
        $this->service->excluir($id);
        return response()->json(['message' => 'Documento excluído']);
    }

    public function download($id)
    {
        $caminho = $this->service->download($id);

        if (!$caminho) {
            return response()->json(['message' => 'Arquivo não encontrado.'], 404);
        }
        return response()->download($caminho);
    }
}
