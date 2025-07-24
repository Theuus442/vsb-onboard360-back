<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'parceiro_id' => 'required|exists:parceiros,id',
            'nome' => 'required|string|max:150',
            'arquivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:5120',
            'status' => 'nullable|in:pendente,aprovado,rejeitado',
        ];
    }
}
