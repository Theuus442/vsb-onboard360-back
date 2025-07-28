<?php

namespace App\Http\Controllers;

use App\Models\TarefaPadrao;
use App\Http\Requests\TarefaPadraoRequest;
use App\Http\Requests\UpdateTarefaPadraoRequest;
use App\Services\TarefaPadraoService;

class TarefaPadraoController extends Controller
{
    protected TarefaPadraoService $service;

    public function __construct(TarefaPadraoService $service)
    {
        $this->service = $service;
    }

    public function index() {
        $tarefas = $this->service->getAllPaginated();
        return response()->json($tarefas);
    }

    public function store(TarefaPadraoRequest $request){
        $tarefa = $this->service->create($request->validated());
        return response()->json($tarefa, 201);
    }

    public function update(UpdateTarefaPadraoRequest $request, int $id) {
        $tarefa = $this->service->update($id, $request->validated());
        return response()->json($tarefa);
    }

    public function destroy(int $id){
        $this->service->deactivate($id);
        return response()->json(["message" => "Tarefa desativada."]);
    }

}
