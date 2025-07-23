<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistParceiro extends Model
{
    protected $table = 'checklist_parceiro';

    protected $fillable = [
        'parceiro_id',
        'tarefa_id',
        'status',
        'observacao',
        'atualizado_por',
    ];

    public function parceiro(){
        return $this->belongsTo(Parceiro::class, 'parceiro_id');
    }

    public function tarefaPadrao(){
        return $this->belongsTo(TarefaPadrao::class, 'tarefa_id');
    }

    public function atualizadoPor(){
        return $this->belongsTo(Usuario::class, 'atualizado_por');
    }


}
