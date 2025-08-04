<?php

namespace App\Services;

use App\Models\Documento;
use Illuminate\Support\Facades\Storage;

class DocumentoService
{
    public function listar()
    {
        return Documento::with('parceiro')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function listarFiltradoPorSetor($parceiroId, $departamento)
    {
        return Documento::where('parceiro_id', $parceiroId)
            ->where(function ($query) use ($departamento) {
                $query->whereNull('setor_destino')
                    ->orWhere('setor_destino', $departamento);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function criar(array $dados, $arquivo)
    {
        if (!$arquivo) {
            throw new \InvalidArgumentException('Arquivo não enviado');
        }

        $path = $arquivo->store('documentos', 'public');

        return Documento::create([
            'parceiro_id' => $dados['parceiro_id'],
            'nome' => $dados['nome'],
            'arquivo' => $path,
            'status' => $dados['status'] ?? 'pendente',
            'setor_destino' => $dados['setor_destino'] ?? null,
        ]);
    }

    public function buscar($id)
    {
        return Documento::with('parceiro')->findOrFail($id);
    }

    public function atualizarStatus($id, $status)
    {
        $documento = Documento::findOrFail($id);
        $documento->update(['status' => $status]);
        return $documento;
    }

    public function excluir($id)
    {
        $documento = Documento::findOrFail($id);

        if ($documento->arquivo && Storage::disk('public')->exists($documento->arquivo)) {
            Storage::disk('public')->delete($documento->arquivo);
        }

        $documento->delete();

        return true;
    }

    public function download($id)
    {
        $documento = Documento::findOrFail($id);

        if (!Storage::disk('public')->exists($documento->arquivo)) {
            return null;
        }

        return storage_path('app/public/' . $documento->arquivo);
    }
}
