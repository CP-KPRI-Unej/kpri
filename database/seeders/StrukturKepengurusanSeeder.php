<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StrukturKepengurusan;
use App\Models\Jabatan;
use App\Models\PeriodeKepengurusan;
use Illuminate\Support\Facades\DB;

class StrukturKepengurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if table is empty
        if (StrukturKepengurusan::count() === 0) {
            // Get periode aktif
            $periodeAktif = PeriodeKepengurusan::where('status', 'aktif')->first();
            $periodeLama = PeriodeKepengurusan::where('status', 'nonaktif')->orderBy('tanggal_mulai', 'desc')->first();
            
            if (!$periodeAktif || !$periodeLama) {
                // If no periode exists, create them
                $this->call(PeriodeKepengurusanSeeder::class);
                $periodeAktif = PeriodeKepengurusan::where('status', 'aktif')->first();
                $periodeLama = PeriodeKepengurusan::where('status', 'nonaktif')->orderBy('tanggal_mulai', 'desc')->first();
            }
            
            // Get jabatan
            $ketua = Jabatan::where('nama_jabatan', 'Ketua')->first();
            $sekretaris = Jabatan::where('nama_jabatan', 'Sekretaris')->first();
            $bendahara = Jabatan::where('nama_jabatan', 'Bendahara')->first();
            $anggota = Jabatan::where('nama_jabatan', 'Anggota')->first();
            $pengawas = Jabatan::where('nama_jabatan', 'Pengawas')->first();
            
            // Create pengurus for active periode
            if ($periodeAktif) {
                if ($ketua) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $ketua->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Dr. Ahmad Syafii, M.Pd.'
                    ]);
                }
                
                if ($sekretaris) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $sekretaris->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Dra. Ratna Dewi, M.M.'
                    ]);
                }
                
                if ($bendahara) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $bendahara->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Ir. Bambang Susilo, M.Sc.'
                    ]);
                }
                
                if ($anggota) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $anggota->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Hendra Wibowo, S.E.'
                    ]);
                    
                    StrukturKepengurusan::create([
                        'id_jabatan' => $anggota->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Siti Fatimah, S.Pd.'
                    ]);
                    
                    StrukturKepengurusan::create([
                        'id_jabatan' => $anggota->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Drs. Budi Santoso, M.Si.'
                    ]);
                }
                
                if ($pengawas) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $pengawas->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Prof. Dr. Hadi Gunawan, M.A.'
                    ]);
                    
                    StrukturKepengurusan::create([
                        'id_jabatan' => $pengawas->id_jabatan,
                        'id_periode' => $periodeAktif->id_periode,
                        'nama_pengurus' => 'Dr. Sri Rahayu, M.Kom.'
                    ]);
                }
            }
            
            // Create pengurus for old periode
            if ($periodeLama) {
                if ($ketua) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $ketua->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Prof. Dr. Adi Wijaya, M.Sc.'
                    ]);
                }
                
                if ($sekretaris) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $sekretaris->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Drs. Agus Hermawan, M.M.'
                    ]);
                }
                
                if ($bendahara) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $bendahara->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Sri Wahyuni, S.E., M.Ak.'
                    ]);
                }
                
                if ($anggota) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $anggota->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Dra. Maya Indah, M.Pd.'
                    ]);
                    
                    StrukturKepengurusan::create([
                        'id_jabatan' => $anggota->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Ir. Tono Hartono, M.T.'
                    ]);
                }
                
                if ($pengawas) {
                    StrukturKepengurusan::create([
                        'id_jabatan' => $pengawas->id_jabatan,
                        'id_periode' => $periodeLama->id_periode,
                        'nama_pengurus' => 'Dr. Eko Prasetyo, M.Si.'
                    ]);
                }
            }
            
            $this->command->info('Struktur data seeded successfully!');
        } else {
            $this->command->info('Struktur data already exists, skipping seeder.');
        }
    }
}
