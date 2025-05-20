<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisLayanan;
use App\Models\Layanan;

class HalamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the pages and their respective services
        $pages = [
            [
                'nama_layanan' => 'Home',
                'services' => [
                    ['judul_layanan' => 'Visi', 'deskripsi_layanan' => 'Deskripsi visi KPRI'],
                    ['judul_layanan' => 'Misi', 'deskripsi_layanan' => 'Deskripsi misi KPRI'],
                ]
            ],
            [
                'nama_layanan' => 'Tentang Kita',
                'services' => [
                    ['judul_layanan' => 'Visi', 'deskripsi_layanan' => 'Deskripsi visi KPRI'],
                    ['judul_layanan' => 'Misi', 'deskripsi_layanan' => 'Deskripsi misi KPRI'],
                    ['judul_layanan' => 'Sejarah Singkat', 'deskripsi_layanan' => 'Deskripsi sejarah singkat KPRI'],
                ]
            ],
            [
                'nama_layanan' => 'Gerai Layanan',
                'services' => [
                    ['judul_layanan' => 'Layanan Anggota', 'deskripsi_layanan' => 'Deskripsi layanan anggota'],
                    ['judul_layanan' => 'Layanan Umum', 'deskripsi_layanan' => 'Deskripsi layanan umum'],
                    ['judul_layanan' => 'Layanan Perwakilan', 'deskripsi_layanan' => 'Deskripsi layanan perwakilan'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Jasa',
                'services' => [
                    ['judul_layanan' => 'Jasa Umum Dan PPOB', 'deskripsi_layanan' => 'Deskripsi jasa umum dan PPOB'],
                    ['judul_layanan' => 'Jasa Rental Kendaraan Dan Pujasera', 'deskripsi_layanan' => 'Deskripsi jasa rental kendaraan dan pujasera'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Simpan Pinjam',
                'services' => [
                    ['judul_layanan' => 'Simpanan', 'deskripsi_layanan' => 'Deskripsi simpanan'],
                    ['judul_layanan' => 'Pinjaman', 'deskripsi_layanan' => 'Deskripsi pinjaman'],
                    ['judul_layanan' => 'Dana Sosial', 'deskripsi_layanan' => 'Deskripsi dana sosial'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Toko',
                'services' => [
                    ['judul_layanan' => 'Produk', 'deskripsi_layanan' => 'Deskripsi produk yang tersedia di unit toko'],
                ]
            ],
        ];

        // Insert data
        foreach ($pages as $page) {
            // Create the page
            $jenisLayanan = JenisLayanan::create([
                'nama_layanan' => $page['nama_layanan'],
            ]);

            // Create the services for this page
            foreach ($page['services'] as $service) {
                Layanan::create([
                    'id_jenis_layanan' => $jenisLayanan->id_jenis_layanan,
                    'judul_layanan' => $service['judul_layanan'],
                    'deskripsi_layanan' => $service['deskripsi_layanan'],
                ]);
            }
        }
    }
} 