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
            ['nome'=>'Cadastramento'],
            ['nome'=>'Enviado'],
            ['nome'=>'Ceritidão'],
            ['nome'=>'Documento Gerado'],
        ];
        foreach ($arr as $key => $value) {
            Etapa::create([
                'nome'=>$value['nome'],
                'token'=>uniqid(),
            ]);
        }
    }
}
