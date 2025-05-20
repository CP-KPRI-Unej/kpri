<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_KPRI')->insert([
            'id_user' => 1,
            'id_role' => 1, // Admin role
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'), // Default password, change in production
        ]);
    }
} 