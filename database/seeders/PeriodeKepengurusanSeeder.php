<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeriodeKepengurusan;
use Illuminate\Support\Facades\DB;

class PeriodeKepengurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table is empty
        if (PeriodeKepengurusan::count() === 0) {
            // Create periods
            PeriodeKepengurusan::create([
                'nama_periode' => 'Periode 2023-2028',
                'tanggal_mulai' => '2023-01-01',
                'tanggal_selesai' => '2028-12-31',
                'status' => 'aktif',
                'keterangan' => 'Periode kepengurusan aktif saat ini'
            ]);
            
            PeriodeKepengurusan::create([
                'nama_periode' => 'Periode 2018-2022',
                'tanggal_mulai' => '2018-01-01',
                'tanggal_selesai' => '2022-12-31',
                'status' => 'nonaktif',
                'keterangan' => 'Periode kepengurusan sebelumnya'
            ]);
            
            PeriodeKepengurusan::create([
                'nama_periode' => 'Periode 2013-2017',
                'tanggal_mulai' => '2013-01-01',
                'tanggal_selesai' => '2017-12-31',
                'status' => 'nonaktif',
                'keterangan' => 'Periode kepengurusan lama'
            ]);
            
            $this->command->info('Periode data seeded successfully!');
        } else {
            $this->command->info('Periode data already exists, skipping seeder.');
        }
    }
}
