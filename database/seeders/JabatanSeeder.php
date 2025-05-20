<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if table is empty
        if (Jabatan::count() === 0) {
            $jabatan = [
                ['nama_jabatan' => 'Ketua'],
                ['nama_jabatan' => 'Wakil'],
                ['nama_jabatan' => 'Sekretaris'],
                ['nama_jabatan' => 'Bendahara'],
                ['nama_jabatan' => 'Anggota'],
            ];
            
            foreach ($jabatan as $jbt) {
                Jabatan::create($jbt);
            }
            
            $this->command->info('Jabatan data seeded successfully!');
        } else {
            $this->command->info('Jabatan data already exists, skipping seeder.');
        }
    }
} 