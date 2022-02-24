<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;
    protected $fillable = [
        'token',
        'area_alvo',
        'loteamento',
        'id_loteamento',
        'matricula',
        'quadra',
        'lote',
        'nome_completo',
        'cpf',
        'nome_conjuge',
        'cpf_conjuge',
        'telefone',
        'escolaridade',
        'estado_civil',
        'situacao_profissional',
        'qtd_membros',
        'idoso',
        'crianca_adolescente',
        'bcp_bolsa_familia',
        'renda_familiar',
        'doc_imovel',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'autor',
        'config',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
}
