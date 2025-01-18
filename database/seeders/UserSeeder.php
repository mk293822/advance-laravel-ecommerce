<?php

namespace Database\Seeders;

use App\Enums\VendorEnum;
use App\Models\User;
use App\Enums\RolesEnum;
use App\Models\Vendor;
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

        $user = User::factory()->create([
            'name' => 'vendor',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('vendor'),
        ]);

        $user->assignRole(RolesEnum::Vendor->value);

        Vendor::factory()->create([
          'user_id' => $user->id,
          'status'=> VendorEnum::Approved,
          'store_name' => 'Vendor Store',
          'store_address'=> fake()->address(),
        ]);


        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ])->assignRole(RolesEnum::Admin->value);
    }
}
