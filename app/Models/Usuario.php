<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $nome
 * @property string $email
 * @property string $senha
 * @property string $papel
 */

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['nome', 'email', 'senha', 'papel', 'departamento'];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function parceirosResponsaveis()
    {
        return $this->hasMany(Parceiro::class, 'responsavel_id');
    }

    public function checklistAtualizados()
    {
        return $this->hasMany(ChecklistParceiro::class, 'atualizado_por');
    }

    public function setSenhaAttribute($valor)
    {
        $this->attributes['senha'] = bcrypt($valor);
    }

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }
}
