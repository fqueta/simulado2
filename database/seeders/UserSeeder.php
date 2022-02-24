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
        User::create([
            'name' => 'Fernando Queta',
            'email' => 'fernando@maisaqui.com.br',
            'password' => Hash::make('ferqueta'),
            'status' => 'actived',
            'profile' => 'dev',
        ]);
    }
}
