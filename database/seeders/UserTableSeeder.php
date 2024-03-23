<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Shop Manager',
            'email'             => 'admin@gmail.com',
            'user_type'         => 'manager',
            'date_of_birth'     => '1990-10-10',
            'mobile'            => '01795232590',
            'is_verified'       => true,
            'email_verified_at' => now(),
            'password'          => bcrypt('12345678'),
            'created_at'        => now(),
            'updated_at'        => now()
        ]);

        User::create([
            'name'              => 'Mahadi Hassan Babu',
            'email'             => 'user1@gmail.com',
            'date_of_birth'     => '1999-06-15',
            'mobile'            => '01996408439',
            'is_verified'       => true,
            'email_verified_at' => now(),
            'password'          => bcrypt('12345678'),
            'created_at'        => now(),
            'updated_at'        => now()
        ]);
    }
}
