<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistParceiroRequest;
use App\Http\Requests\UpdateChecklistParceiroRequest;
use App\Services\ChecklistParceiroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChecklistParceiroController extends Controller
{
    protected $service;

    public function __construct(ChecklistParceiroService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $usuario = Auth::user();

        $status = $request->query('status');

        $checklist = $this->service->listarFiltradoPorSetor(
            $usuario->parceiro_id,
            $usuario->departamento,
            $status
        );

        return response()->json($checklist);
    }

    public function store(ChecklistParceiroRequest $request)
    {
        $dados = $request->validated();
        $dados['setor_destino'] = $dados['setor_destino'] ?? null;

        $checklist = $this->service->criar($dados);

        return response()->json($checklist, 201);
    }

    public function show($id)
    {
        $checklist = $this->service->buscarPorId($id);
        return response()->json($checklist);
    }

    public function update(UpdateChecklistParceiroRequest $request, $id)
    {
        $checklist = $this->service->atualizar($id, $request->validated());

        return response()->json($checklist);
    }

    public function destroy($id)
    {
        $this->service->deletar($id);
        return response()->json(null, 204);
    }
}
