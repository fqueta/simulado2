<?php

namespace Database\Seeders;

use App\Models\Bairro;
use Illuminate\Database\Seeder;

class bairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'Camponesa e adjacências com matricula','matricula'=>'624'],
            ['nome'=>'Camponesa e adjacências sem matricula','matricula'=>''],
            ['nome'=>'Santa luzia e adjacências com matricula','matricula'=>'654'],
            ['nome'=>'Santa luzia e adjacências sem matricula','matricula'=>''],
        ];
        foreach ($arr as $key => $value) {
            Bairro::create([
                'nome'=>$value['nome'],
                'matricula'=>$value['matricula'],
                'token'=>uniqid(),
            ]);
        }
    }
}
