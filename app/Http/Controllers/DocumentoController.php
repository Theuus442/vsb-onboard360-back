<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Services\DocumentoService;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    protected $service;

    public function __construct(DocumentoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $usuario = Auth::user();

        $documentos = $this->service->listarFiltradoPorSetor(
            $usuario->parceiro_id,
            $usuario->departamento
        );

        return response()->json($documentos);
    }

    public function store(DocumentoRequest $request)
    {
        try {
            $dados = $request->validated();
            $dados['setor_destino'] = $request->input('setor_destino');

            $documento = $this->service->criar($dados, $request->file('arquivo'));

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

        return response()->json([
            'message' => 'Status atualizado',
            'documento' => $documento
        ]);
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
