<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChecklistParceiroRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parceiro_id' => 'required|exists:parceiros,id',
            'tarefa_id' => 'required|exists:tarefas_padrao,id',
            'status' => 'required|in:pendente,em_andamento,concluido,rejeitado',
            'observacao' => 'nullable|string',
            'atualizado_por' => 'required|exists:usuarios,id',
        ];
    }
}
