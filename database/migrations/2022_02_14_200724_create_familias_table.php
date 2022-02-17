<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome_completo','250')->nullable();
            $table->string('cpf','20')->unique();
            $table->string('area_alvo','100')->nullable();
            $table->string('matricula','100')->nullable();
            $table->string('lote','100')->nullable();
            $table->string('endereco','250')->nullable();
            $table->string('numero','100')->nullable();
            $table->string('quadra','50')->nullable();
            $table->string('bairro','200')->nullable();
            $table->string('cidade','100')->nullable();
            $table->string('escolaridade','50')->nullable();
            $table->string('estado_civil','50')->nullable();
            $table->string('situacao_proficional','150')->nullable();
            $table->integer('qtd_membros')->nullable();
            $table->enum('idoso',['n','s']);
            $table->enum('crianca_adolescente',['s','n']);
            $table->enum('bcp_bolsa_familia',['n','s']);
            $table->longText('config')->nullable();
            $table->double('renda_familiar',12,2);
            $table->enum('doc_imovel',['n','s']);
            $table->longText('obs')->nullable();
            $table->enum('excluido',['n','s']);
            $table->text('reg_excluido')->nullable();
            $table->enum('deletado',['n','s']);
            $table->text('reg_deletado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
