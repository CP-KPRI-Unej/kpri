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
                    ['judul_layanan' => 'Gambar Visi', 'gambar' => 'layanan/home_visi.jpg'],
                    ['judul_layanan' => 'Gambar Misi', 'gambar' => 'layanan/home_misi.jpg'],
                    ['judul_layanan' => 'Gambar Deskripsi', 'gambar' => 'layanan/home_deskripsi.jpg'],
                ]
            ],
            [
                'nama_layanan' => 'Tentang Kita',
                'services' => [
                    ['judul_layanan' => 'Visi', 'deskripsi_layanan' => 'Deskripsi visi KPRI'],
                    ['judul_layanan' => 'Misi', 'deskripsi_layanan' => 'Deskripsi misi KPRI'],
                    ['judul_layanan' => 'Sejarah Singkat', 'deskripsi_layanan' => 'Deskripsi sejarah singkat KPRI'],
                    ['judul_layanan' => 'Gambar Sejarah 1', 'gambar' => 'layanan/tentang_sejarah1.jpg'],
                    ['judul_layanan' => 'Gambar Sejarah 2', 'gambar' => 'layanan/tentang_sejarah2.jpg'],
                ]
            ],
            [
                'nama_layanan' => 'Gerai Layanan',
                'services' => [
                    ['judul_layanan' => 'Layanan Anggota', 'deskripsi_layanan' => 'Deskripsi layanan anggota'],
                    ['judul_layanan' => 'Layanan Umum', 'deskripsi_layanan' => 'Deskripsi layanan umum'],
                    ['judul_layanan' => 'Layanan Perwakilan', 'deskripsi_layanan' => 'Deskripsi layanan perwakilan'],
                    ['judul_layanan' => 'Gambar Layanan Anggota', 'gambar' => 'layanan/gerai_anggota.jpg'],
                    ['judul_layanan' => 'Gambar Layanan Umum', 'gambar' => 'layanan/gerai_umum.jpg'],
                    ['judul_layanan' => 'Gambar Layanan Perwakilan', 'gambar' => 'layanan/gerai_perwakilan.jpg'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Jasa',
                'services' => [
                    ['judul_layanan' => 'Jasa Umum Dan PPOB', 'deskripsi_layanan' => 'Deskripsi jasa umum dan PPOB'],
                    ['judul_layanan' => 'Jasa Rental Kendaraan Dan Pujasera', 'deskripsi_layanan' => 'Deskripsi jasa rental kendaraan dan pujasera'],
                    ['judul_layanan' => 'Gambar Jasa Umum dan PPOB', 'gambar' => 'layanan/jasa_umum_ppob.jpg'],
                    ['judul_layanan' => 'Gambar Jasa Rental dan Pujasera', 'gambar' => 'layanan/jasa_rental_pujasera.jpg'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Simpan Pinjam',
                'services' => [
                    ['judul_layanan' => 'Simpanan', 'deskripsi_layanan' => 'Deskripsi simpanan'],
                    ['judul_layanan' => 'Pinjaman', 'deskripsi_layanan' => 'Deskripsi pinjaman'],
                    ['judul_layanan' => 'Dana Sosial', 'deskripsi_layanan' => 'Deskripsi dana sosial'],
                    ['judul_layanan' => 'Gambar Simpanan', 'gambar' => 'layanan/simpan_pinjam_simpanan.jpg'],
                    ['judul_layanan' => 'Gambar Pinjaman', 'gambar' => 'layanan/simpan_pinjam_pinjaman.jpg'],
                    ['judul_layanan' => 'Gambar Dana Sosial', 'gambar' => 'layanan/simpan_pinjam_dana_sosial.jpg'],
                ]
            ],
            [
                'nama_layanan' => 'Unit Toko',
                'services' => [
                    ['judul_layanan' => 'Produk', 'deskripsi_layanan' => 'Deskripsi produk yang tersedia di unit toko'],
                    ['judul_layanan' => 'Gambar Produk', 'gambar' => 'layanan/toko_produk.jpg'],
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
                $data = [
                    'id_jenis_layanan' => $jenisLayanan->id_jenis_layanan,
                    'judul_layanan' => $service['judul_layanan'],
                ];
                
                // Add description or image based on what's available
                if (isset($service['deskripsi_layanan'])) {
                    $data['deskripsi_layanan'] = $service['deskripsi_layanan'];
                }
                
                if (isset($service['gambar'])) {
                    $data['gambar'] = $service['gambar'];
                }
                
                Layanan::create($data);
            }
        }
    }
} 