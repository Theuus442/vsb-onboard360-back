<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarefaPadraoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'titulo' => 'sometimes|required|string|max:150',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer',
            'setor_responsavel' => 'sometimes|required|string|max:100',
            'obrigatoria' => 'required|boolean',
            'ativa' => 'required|boolean'
        ];
    }
}
