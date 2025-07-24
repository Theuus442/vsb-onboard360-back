<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParceiroRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome_fantasia' => 'required|string|max:50',
            'razao_social' => 'required|string|max:150',
            'cnpj' => 'required|string|max:20|unique:parceiros,cnpj',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:parceiros,email',
            'responsavel_id' => 'nullable|exists:usuarios,id',
            'status' => 'required|in:ativo,inativo,suspenso',
        ];
    }
}
