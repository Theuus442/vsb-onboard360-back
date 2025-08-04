<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $usuarios = $this->service->listar();
        return response()->json($usuarios, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'papel' => 'required|string',
            'departamento' => 'required|string',
        ]);

        $usuario = $this->service->criar($dados);

        return response()->json($usuario, 201);
    }

    public function show($id): JsonResponse
    {
        $usuario = $this->service->buscarPorId($id);
        return response()->json($usuario, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $dados = $request->validate([
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id,
            'senha' => 'sometimes|string|min:6',
            'papel' => 'sometimes|string',
            'departamento' => 'sometimes|string',
        ]);

        $usuario = $this->service->atualizar($id, $dados);

        return response()->json($usuario, 200);
    }

    public function destroy($id): JsonResponse
    {
        $this->service->deletar($id);

        return response()->json(null, 204);
    }

    // Novo método para listar departamentos
    public function listarDepartamentos(): JsonResponse
    {
        $departamentos = [
            'Financeiro',
            'RH',
            'Comercial',
            'Operações',
            'Desenvolvimento',
        ];

        return response()->json($departamentos, 200);
    }
}
