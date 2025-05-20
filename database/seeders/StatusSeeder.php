<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            [
                'id_status' => 1,
                'nama_status' => 'Active'
            ],
            [
                'id_status' => 2,
                'nama_status' => 'Inactive'
            ],
        ]);
        
    }
} 