<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParceiroRequest;
use App\Http\Requests\UpdateParceiroRequest;
use App\Models\Usuario;
use App\Services\ParceiroService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ParceiroController extends Controller
{
    protected ParceiroService $service;

    public function __construct(ParceiroService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);

        $resultado = $this->service->listar($limit, $search, $page);

        return response()->json([
            'data' => $resultado->items(),
            'pagination' => [
                'total' => $resultado->total(),
                'page' => $resultado->currentPage(),
                'limit' => $resultado->perPage(),
                'last_page' => $resultado->lastPage(),
            ]
        ]);
    }

    public function store(ParceiroRequest $request): JsonResponse
    {
        $dados = $request->validated();
        $parceiro = $this->service->criar($dados);

        return response()->json($parceiro, 201);
    }

    public function show(int $id): JsonResponse
    {
        $parceiro = $this->service->buscarPorId($id);
        return response()->json($parceiro);
    }

    public function update(UpdateParceiroRequest $request, int $id): JsonResponse
    {
        $parceiro = $this->service->atualizar($id, $request->validated());
        return response()->json($parceiro);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->inativar($id);
        return response()->json(['message' => 'Parceiro marcado como inativo com sucesso!']);
    }

    public function usuarios(Request $request): JsonResponse
    {
        /** @var \App\Models\Usuario|null $usuario */
        $usuario = $request->user();

        if (!$usuario || !$usuario->parceiro_id) {
            return response()->json(['message' => 'Usuário não vinculado a nenhum parceiro.'], 403);
        }

        $usuarios = $this->service->listarUsuariosDoParceiro($usuario->parceiro_id);

        return response()->json($usuarios);
    }

    public function adicionarUsuario(Request $request): JsonResponse
    {
        /** @var \App\Models\Usuario|null $usuario */
        $usuario = $request->user();

        if (!$usuario || !$usuario->parceiro_id) {
            return response()->json(['message' => 'Usuário não vinculado a nenhum parceiro.'], 403);
        }

        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'departamento' => 'required|string',
        ]);

        $novoUsuario = $this->service->adicionarUsuarioAoParceiro($usuario->parceiro_id, $dados);

        return response()->json($novoUsuario, 201);
    }

    public function removerUsuario(Request $request, int $usuarioId): JsonResponse
    {
        /** @var \App\Models\Usuario|null $usuario */
        $usuario = $request->user();

        if (!$usuario || !$usuario->parceiro_id) {
            return response()->json(['message' => 'Usuário não vinculado a nenhum parceiro.'], 403);
        }

        $this->service->removerUsuarioDoParceiro($usuario->parceiro_id, $usuarioId);

        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $parceiro = $this->service->toggleStatus($id);

        return response()->json([
            'message' => 'Status do parceiro alterado com sucesso!',
            'status' => $parceiro->status
        ]);
    }
}
