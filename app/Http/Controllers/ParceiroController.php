<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParceiroRequest;
use App\Http\Requests\UpdateParceiroRequest;
use App\Services\ParceiroService;
use Illuminate\Http\Request;

class ParceiroController extends Controller
{
    protected $service;

    public function __construct(ParceiroService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $search = $request->get('search', null);

        // Passar para o serviço listar com paginação e filtro
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

    public function usuarios($parceiroId)
    {
        $usuarios = $this->service->listarUsuariosDoParceiro($parceiroId);
        return response()->json($usuarios);
    }

    public function adicionarUsuario($parceiroId, Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'departamento' => 'required|string',
        ]);

        $usuario = $this->service->adicionarUsuarioAoParceiro($parceiroId, $dados);

        return response()->json($usuario, 201);
    }

    public function removerUsuario($parceiroId, $usuarioId)
    {
        $this->service->removerUsuarioDoParceiro($parceiroId, $usuarioId);
        return response()->json(['message' => 'Usuário removido com sucesso.']);
    }
}
