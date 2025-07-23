<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarefaPadrao extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'ordem',
        'setor_responsavel',
        'obrigatoria',
        'ativa',
    ];

    public function checklistsParceiros()
    {
        return $this->hasMany(ChecklistParceiro::class, 'tarefa_id');
    }
}
