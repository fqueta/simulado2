<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome',300)->nullable();
            $table->string('titulo',300)->nullable();
            $table->double('matricula',12,2)->nullable();
            $table->string('token','100')->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
            $table->longText('descricao')->nullable();
            $table->longText('obs')->nullable();
            $table->json('conteudo')->nullable();
            $table->json('config')->nullable();
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
        Schema::dropIfExists('questoes');
    }
}
