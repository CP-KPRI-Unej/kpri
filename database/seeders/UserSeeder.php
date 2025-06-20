<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample users
        DB::table('user_KPRI')->insert([
            [
                'id_user' => 1,
                'id_role' => 1, // kpri admin
                'nama_user' => 'KPRI Admin',
                'username' => 'kpriadmin',
                'password' => Hash::make('password123'),
            ],
            [
                'id_user' => 2,
                'id_role' => 2, // admin shop
                'nama_user' => 'Shop Admin',
                'username' => 'shopadmin',
                'password' => Hash::make('password123'),
            ],
            [
                'id_user' => 3,
                'id_role' => 1, // kpri admin
                'nama_user' => 'KPRI Admin 2',
                'username' => 'kpriadmin2',
                'password' => Hash::make('password123'),
            ],
        ]);
    }
} 