<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Catatan: Table status masih digunakan untuk entitas lain selain artikel.
     * Artikel sekarang menggunakan enum (draft, published, archived).
     */
    public function run(): void
    {
        // Status ini digunakan untuk entitas selain Artikel
        DB::table('status')->insert([
            [
                'id_status' => 1,
                'nama_status' => 'Aktif'
            ],
            [
                'id_status' => 2,
                'nama_status' => 'Nonaktif'
            ],
        ]);
        
    }
} 