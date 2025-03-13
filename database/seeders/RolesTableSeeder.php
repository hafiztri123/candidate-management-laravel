<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('slug', 'admin')->exists()) {
            Role::create([
                'name' => 'Administrator',
                'slug' => 'admin'
            ]);
        }

        if (!Role::where('slug', 'user')->exists()) {
            Role::create([
                'name' => 'User',
                'slug' => 'user'
            ]);
        }
    }
}
