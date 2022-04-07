<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class categoria extends Model
{
    use HasFactory;protected $fillable = [
        'token',
        'nome',
        'ativo',
        'autor',
        'obs',
        'url',
        'pai',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
