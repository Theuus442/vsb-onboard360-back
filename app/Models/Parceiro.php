<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'telefone',
        'email',
        'responsavel_id',
        'status',
    ];

    public function responsavel()
    {
        return $this->belongsTo(Usuario::class, 'responsavel_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'parceiro_id');
    }

    public function checklists()
    {
        return $this->hasMany(ChecklistParceiro::class, 'parceiro_id');
    }

    public function tarefasPadrao()
    {
        return $this->hasManyThrough(TarefaPadrao::class, ChecklistParceiro::class, 
        'parceiro_id', 'id', 'id', 'tarefa_id');
    }
}
