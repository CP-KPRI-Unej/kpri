<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if articles already exist to avoid duplication
        if (Artikel::count() > 0) {
            $this->command->info('Articles already exist, skipping seeding.');
            return;
        }

        // Get admin users for authorship
        $users = User::whereHas('role', function($query) {
            $query->where('nama_role', 'admin');
        })->get();
        
        if ($users->isEmpty()) {
            $this->command->error('No admin users found. Please run UserSeeder first.');
            return;
        }

        $artikelTitles = [
            'Promo Akhir Tahun KPRI',
            'Laporan Keuangan Triwulan II 2023',
            'Pembukaan Cabang Baru di Bekasi',
            'Program Simpanan Hari Tua',
            'Penyesuaian Bunga Pinjaman',
            'Hasil Rapat Anggota Tahunan',
            'Tips Mengelola Keuangan bagi Anggota Koperasi',
            'Peresmian Gedung Baru KPRI',
            'Kolaborasi dengan Bank Lokal',
            '10 Tahun KPRI: Retrospeksi dan Visi Ke Depan',
            'Panduan Pengajuan Pinjaman Online',
            'Peluncuran Aplikasi Mobile KPRI',
            'Seminar Kewirausahaan untuk Anggota',
            'Update Kebijakan Privasi dan Keamanan Data',
            'Penghargaan Koperasi Terbaik 2023'
        ];

        $deskripsi = [
            'Artikel ini membahas tentang promo dan diskon yang ditawarkan KPRI pada akhir tahun. Anggota dapat menikmati berbagai penawaran menarik untuk berbagai produk dan layanan.',
            'Laporan keuangan triwulan II menunjukkan pertumbuhan positif pada aset dan pendapatan KPRI. Transparansi keuangan ini merupakan komitmen kami kepada seluruh anggota.',
            'KPRI dengan bangga mengumumkan pembukaan cabang baru di Bekasi untuk memperluas jangkauan layanan kami. Cabang ini akan melayani anggota di wilayah Bekasi dan sekitarnya.',
            'Program Simpanan Hari Tua adalah inisiatif terbaru dari KPRI untuk membantu anggota mempersiapkan masa pensiun dengan lebih baik melalui skema simpanan khusus.',
            'Membahas tentang penyesuaian bunga pinjaman yang akan diberlakukan mulai bulan depan. Penyesuaian ini merupakan respons terhadap perubahan kebijakan moneter nasional.',
            'Ringkasan hasil dan keputusan yang diambil dalam Rapat Anggota Tahunan yang telah diselenggarakan. Beberapa program baru telah disetujui untuk implementasi tahun ini.',
            'Artikel ini berisi tips praktis bagi anggota koperasi dalam mengelola keuangan pribadi dan keluarga. Tips ini disusun berdasarkan pengalaman dan masukan dari konsultan keuangan.',
            'Peresmian gedung baru KPRI telah dilaksanakan dengan dihadiri oleh pejabat daerah dan perwakilan dari Dinas Koperasi. Gedung ini akan menjadi pusat aktivitas KPRI ke depannya.',
            'KPRI menjalin kerja sama strategis dengan bank lokal untuk memperluas akses layanan keuangan bagi anggota. Kolaborasi ini membuka peluang baru bagi pengembangan koperasi.',
            'Merayakan satu dekade keberadaan KPRI dengan melihat kembali perjalanan dan pencapaian yang telah diraih, serta visi dan strategi untuk sepuluh tahun ke depan.',
            'Panduan lengkap bagi anggota untuk mengajukan pinjaman secara online melalui platform digital KPRI. Proses yang lebih cepat dan efisien kini tersedia bagi seluruh anggota.',
            'KPRI meluncurkan aplikasi mobile untuk memudahkan anggota mengakses layanan koperasi dari mana saja. Aplikasi ini tersedia untuk perangkat Android dan iOS.',
            'KPRI akan menyelenggarakan seminar kewirausahaan untuk membekali anggota dengan pengetahuan dan keterampilan dalam memulai dan mengembangkan usaha.',
            'Informasi terbaru mengenai kebijakan privasi dan langkah-langkah yang diambil KPRI untuk memastikan keamanan data anggota.',
            'KPRI menerima penghargaan sebagai Koperasi Terbaik 2023 dari Dinas Koperasi dan UMKM. Penghargaan ini merupakan bukti dedikasi dan kerja keras seluruh pengurus dan anggota.'
        ];

        $tags = [
            'promo, diskon, akhir tahun',
            'laporan keuangan, transparansi, triwulan',
            'cabang baru, ekspansi, bekasi',
            'simpanan, pensiun, hari tua',
            'bunga, pinjaman, keuangan',
            'rapat anggota, keputusan, program',
            'tips, keuangan, manajemen uang',
            'peresmian, gedung baru, fasilitas',
            'kerja sama, bank, layanan keuangan',
            'anniversary, retrospeksi, visi',
            'panduan, pinjaman, online',
            'aplikasi, mobile, teknologi',
            'seminar, kewirausahaan, pelatihan',
            'privasi, keamanan, data',
            'penghargaan, prestasi, rekognisi'
        ];

        $statusIds = [1, 2]; // 1 = Draft, 2 = Published (adjust based on your status IDs)
        $articleCount = 0;

        foreach ($artikelTitles as $index => $title) {
            $status = $statusIds[array_rand($statusIds)];
            $user = $users->random();
            
            // Release date is random date within last 1 year if published, null if draft
            $releaseDate = null;
            if ($status == 2) { // Published
                $releaseDate = Carbon::now()->subDays(rand(1, 365))->format('Y-m-d');
            } else { // Draft articles
                $releaseDate = Carbon::now()->format('Y-m-d'); // Set current date for drafts
            }
            
            Artikel::create([
                'id_status' => $status,
                'id_user' => $user->id_user,
                'nama_artikel' => $title,
                'deskripsi_artikel' => $deskripsi[$index],
                'tgl_rilis' => $releaseDate,
                'tags_artikel' => $tags[$index],
            ]);
            
            $articleCount++;
        }

        $this->command->info("Created {$articleCount} articles successfully!");
    }
} 