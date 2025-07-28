<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistParceiroRequest;
use App\Http\Requests\UpdateChecklistParceiroRequest;
use App\Services\ChecklistParceiroService;
use Illuminate\Http\Request;

class ChecklistParceiroController extends Controller
{
    protected $service;

    public function __construct(ChecklistParceiroService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $checklist = $this->service->listar($status);

        return response()->json($checklist);
    }

    public function store(ChecklistParceiroRequest $request)
    {
        $checklist = $this->service->criar($request->validated());

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
