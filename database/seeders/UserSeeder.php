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
        if(!User::where('email', 'hafiz.triwahyu@gmail.com')->exists()){
            User::factory()->admin()->create([
                'name' => 'Hafizh Tri Wahyu Muhammad',
                'email' => 'hafiz.triwahyu@gmail.com',
                'password' => Hash::make('Sudarmi12')
            ]);
        }


    }
}
