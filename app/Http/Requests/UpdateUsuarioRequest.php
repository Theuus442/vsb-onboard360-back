<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $nome
 * @property string|null $email
 * @property string|null $senha
 * @property string|null $papel
 */

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route('id');

        return [
            'nome' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique|usuarios,email,' . $usuarioId,
            'senha' => 'sometimes|string|min:6',
            'papel' => 'sometimes|in:admin,parceiro,interno',
        ];
    }
}
