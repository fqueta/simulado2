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
            ],
            [
                'name' => 'Andre Fialho',
                'email' => 'andre.fialho@institutobrasil.com',
                'password' => '$2y$10$wMgI57oJDXqtH56yhuilXuIRqxaI3TwWQ9r.Q.n/SHOQcuKxsHlc6',
                'status' => 'actived',
                'profile' => 'admin',
                'gender' => 'male',
            ],
            [
                'name' => 'Adriana Santos',
                'email' => 'adrianasantoscmd@gmail.com',
                'password' => '$2y$10$V.FqMA4lGqwXMqu8237DIuQfGxNma1Wlo8wZJh0.TIBIGPhNQVL26',
                'status' => 'actived',
                'profile' => 'admin',
                'gender' => 'female',
            ],
            [
                'name' => 'Regiane Correa',
                'email' => 'regianecorreaadv@gmail.com',
                'password' => '$2y$10$iJkTuHIo24Hd4crjhCgKmeLSYIqXIgOTBRP4U/B.YM.GA7LA1suD6',
                'status' => 'pre_registred',
                'profile' => 'admin',
                'gender' => 'female',
            ],
        ];
        foreach ($arr as $key => $value) {
            User::create($value);
        }
    }
}
