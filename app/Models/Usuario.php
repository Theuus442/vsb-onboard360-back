<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'papel',
        'departamento',
        'parceiro_id',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'parceiro_id' => 'integer',
    ];

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }
}
