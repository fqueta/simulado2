<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome_completo',
        'cpf',
        'area_alvo',
        'matricula',
        'lote',
        'endereco',
        'numero',
        'quadra',
        'bairro',
        'cidade',
        'escolaridade',
        'estado_civil',
        'situacao_proficional',
        'qtd_membros',
        'idoso',
        'crianca_adolescente',
        'bcp_bolsa_familia',
        'config',
        'renda_familiar',
        'doc_imovel',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'red_deletado',
    ];
}
