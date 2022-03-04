<?php

namespace Database\Seeders;

use App\Models\Etapa;
use Illuminate\Database\Seeder;

class etapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'1. Selagem dos imóveis (topografia + equipe de campo)'],
            ['nome'=>'2. Cadastros (assis social e equipe de campo)'],
            ['nome'=>'3. Elaboração do relatório social (assist social)'],
            ['nome'=>'4. Levantamentos topográficos individualizados'],
            ['nome'=>'5. Montagem processo jurídico'],
            ['nome'=>'6. Atendimentos'],
            ['nome'=>'7. Finalização do processo de CRF'],
            ['nome'=>'8. Entrega das CRFs à Contratante (SMMAGU/PM CMD)'],
        ];
        foreach ($arr as $key => $value) {
            Etapa::create([
                'nome'=>$value['nome'],
                'token'=>uniqid(),
            ]);
        }
    }
}
