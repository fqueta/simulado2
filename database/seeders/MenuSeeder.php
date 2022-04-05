<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'categoria'=>'CADASTROS',
                'description'=>'Cadastros de Lotes',
                'icon'=>'fas fa-copy',
                'actived'=>true,
                'url'=>'cadastros',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Todos Lotes',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'familias',
                'route'=>'familias.index',
                'pai'=>'cadastros'
            ],
            [
                'categoria'=>'',
                'description'=>'Bairros',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'bairros',
                'route'=>'bairros.index',
                'pai'=>'cadastros'
            ],
            [
                'categoria'=>'',
                'description'=>'Etapas',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'etapas',
                'route'=>'etapas.index',
                'pai'=>'cadastros'
            ],
            [
                'categoria'=>'',
                'description'=>'Escolaridade',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'escolaridade',
                'route'=>'escolaridades.index',
                'pai'=>'cadastros'
            ],
            [
                'categoria'=>'',
                'description'=>'Estado civil',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'estado-civil',
                'route'=>'estadocivils.index',
                'pai'=>'cadastros'
            ],
            [
                'categoria'=>'',
                'description'=>'Relatórios',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'relatorios',
                'route'=>'relatorios.index',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Geral',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'relatorios_geral',
                'route'=>'relatorios.geral',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'',
                'description'=>'Evolução',
                'icon'=>'fa fa-chart-bar',
                'actived'=>true,
                'url'=>'relatorios_evolucao',
                'route'=>'relatorios.evolucao',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'SISTEMA',
                'description'=>'Configurações',
                'icon'=>'fas fa-cogs',
                'actived'=>true,
                'url'=>'config',
                'route'=>'sistema.config',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Perfil',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'sistema',
                'route'=>'sistema.perfil',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Usuários',
                'icon'=>'fas fa-users',
                'actived'=>true,
                'url'=>'users',
                'route'=>'users.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Permissões',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'permissions',
                'route'=>'permissions.index',
                'pai'=>'config'
            ],
        ]);
    }
}
