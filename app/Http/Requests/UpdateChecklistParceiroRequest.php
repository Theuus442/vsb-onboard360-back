<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChecklistParceiroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pendente,em_andamento,concluido,rejeitado',
            'observacao' => 'nullable|string',
            'atualizado_por' => 'required|exists:usuarios,id',
        ];
    }
}
