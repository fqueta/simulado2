<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;
    protected $casts = [
        'conteudo' => 'array',
        'config' => 'array',
    ];
    protected $fillable = [
        'token',
        'nome',
        'ativo',
        'autor',
        'obs',
        'url',
        'pai',
        'conteudo',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
