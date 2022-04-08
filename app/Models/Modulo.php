<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;
    protected $casts = [
        'conteudo' => 'array',
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
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
