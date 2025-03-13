<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::factory()->create([
            'name' => 'Real user',
            'email' => 'user@mail.com',
            'password' => Hash::make('Sudarmi12')
        ]);
        User::factory()->admin()->create([
            'name' => 'Real admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('Sudarmi12')
        ]);
    }
}
