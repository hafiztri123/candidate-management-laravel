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

        User::factory()->admin()->create([
            'name' => 'Hafizh Tri Wahyu Muhammad',
            'email' => 'hafiz.triwahyu@gmailc.om',
            'password' => Hash::make('Sudarmi12')
        ]);
    }
}
