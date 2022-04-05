<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name'=>'Master',
                'description'=>'Desenvolvedores',
                'active'=>'s',
                'id_menu'=>addslashes('{"ler":{"cadastros":"s","familias":"s","bairros":"s","etapas":"s","escolaridade":"s","estado-civil":"s","relatorios":"s","relatorios_geral":"s","relatorios_evolucao":"s","config":"s","sistema":"s","users":"s","permissions":"s"},"create":{"familias":"s","bairros":"s","etapas":"s","escolaridade":"s","estado-civil":"s","relatorios_geral":"s","relatorios_evolucao":"s","sistema":"s","users":"s","permissions":"s"},"update":{"familias":"s","bairros":"s","etapas":"s","escolaridade":"s","estado-civil":"s","relatorios_geral":"s","relatorios_evolucao":"s","sistema":"s","users":"s","permissions":"s"},"delete":{"familias":"s","bairros":"s","etapas":"s","escolaridade":"s","estado-civil":"s","relatorios_geral":"s","relatorios_evolucao":"s","sistema":"s","users":"s","permissions":"s"}}'),
            ],
            ['name'=>'Adminstrador','description'=>'Adiminstradores do sistema','active'=>'s'],
            ['name'=>'Gerente','description'=>'Gerente do sistema menos que administrador secundário','active'=>'s'],
            ['name'=>'Escritório','description'=>'Pessoas do escritório','active'=>'s'],
            ['name'=>'Usuário','description'=>'Somente clientes, Sem privilêgios de administração acesso a área restrita do site','active'=>'s'],
        ]);
    }
}
