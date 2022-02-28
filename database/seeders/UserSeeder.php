<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            [
                'name' => 'Fernando Queta',
                'email' => 'fernando@maisaqui.com.br',
                'password' => Hash::make('ferqueta'),
                'status' => 'actived',
                'profile' => 'dev',
            ],
            [
                'name' => 'Usuario de teste',
                'email' => 'teste@databrasil.app.br',
                'password' => Hash::make('mudar123'),
                'status' => 'actived',
                'profile' => 'admin',
            ]
        ];
        foreach ($arr as $key => $value) {
            User::create($value);
        }
    }
}
