<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParceiroRequest;
use App\Http\Requests\UpdateParceiroRequest;
use App\Services\ParceiroService;

class ParceiroController extends Controller
{

    protected $service;

    public function __construct(ParceiroService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $parceiros = $this->service->listar();
        return response()->json($parceiros);
    }

    public function store(ParceiroRequest $request)
    {
        $dados = $request->validated();
        $parceiro = $this->service->criar($dados);

        return response()->json($parceiro, 201);
    }

    public function show($id)
    {
        $parceiro = $this->service->buscarPorId($id);
        return response()->json($parceiro);
    }

    public function update(UpdateParceiroRequest $request, $id)
    {
        $parceiro = $this->service->atualizar($id, $request->validated());
        return response()->json($parceiro);
    }

    public function destroy($id)
    {
        $parceiro = $this->service->inativar($id);
        return response()->json(['message' => 'Parceiro marcado como inativo com sucesso!']);
    }
}
