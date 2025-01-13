<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user')
        ])->assignRole(RolesEnum::User->value);

        User::factory()->create([
            'name' => 'vendor',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('vendor'),
        ])->assignRole(RolesEnum::Vendor->value);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ])->assignRole(RolesEnum::Admin->value);
    }
}