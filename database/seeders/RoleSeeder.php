<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert roles
        DB::table('role')->insert([
            ['id_role' => 1, 'nama_role' => 'kpri admin'],
            ['id_role' => 2, 'nama_role' => 'admin shop'],
        ]);
    }
} 