<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $parceiro_id
 * @property string $nome
 * @property string $arquivo
 * @property string $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Documento extends Model
{
    protected $fillable = [
        'parceiro_id',
        'nome',
        'arquivo',
        'status',
    ];

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class, 'parceiro_id');
    }
}
