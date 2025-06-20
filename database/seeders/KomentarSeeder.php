<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komentar;
use App\Models\Artikel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KomentarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if comments already exist to avoid duplication
        if (Komentar::count() > 0) {
            $this->command->info('Comments already exist, skipping seeding.');
            return;
        }

        // Get published articles to attach comments to
        $articles = Artikel::where('status', 'published')->get(); // Menggunakan enum status 'published'
        
        if ($articles->isEmpty()) {
            $this->command->error('No published articles found. Please run ArtikelSeeder first.');
            return;
        }

        $commenters = [
            'John Doe',
            'Jane Smith',
            'Ahmad Fauzi',
            'Maria Garcia',
            'David Wilson',
            'Siti Nurhaliza',
            'Michael Brown',
            'Putri Indah',
            'Robert Johnson',
            'Dewi Sartika'
        ];

        $commentTexts = [
            'Artikelnya sangat informatif, terima kasih!',
            'Saya setuju dengan poin-poin yang disampaikan.',
            'Mohon informasi lebih lanjut tentang program ini.',
            'Kapan acara ini akan diselenggarakan?',
            'Bagaimana cara mendaftar untuk program ini?',
            'Terima kasih atas informasinya yang bermanfaat.',
            'Apakah ada syarat khusus untuk mengikuti program ini?',
            'Sangat tertarik dengan program yang ditawarkan.',
            'Artikel ini sangat membantu saya memahami prosedurnya.',
            'Berapa lama proses pengajuan sampai persetujuan?',
            'Saya sudah mencoba program ini dan hasilnya memuaskan!',
            'Bisakah dibuat artikel lanjutan tentang topik ini?',
            'Informasi yang sangat relevan untuk anggota baru.',
            'Mohon penjelasan lebih detail tentang syarat dan ketentuan.',
            'Saya tunggu update berikutnya tentang program ini!'
        ];

        $statuses = ['pending', 'approved', 'rejected'];
        $commentCount = 0;

        foreach ($articles as $article) {
            // Create random number of comments (0-5) for each article
            $numComments = rand(0, 5);
            
            for ($i = 0; $i < $numComments; $i++) {
                $commenter = $commenters[array_rand($commenters)];
                $text = $commentTexts[array_rand($commentTexts)];
                $status = $statuses[array_rand($statuses)];
                
                // Make sure comment date is after article release date
                $articleDate = Carbon::parse($article->tgl_rilis);
                $commentDate = $articleDate->copy()->addDays(rand(1, 30));
                
                // Ensure we don't have future dates
                $commentDate = min($commentDate, Carbon::now());
                
                Komentar::create([
                    'id_artikel' => $article->id_artikel,
                    'nama_pengomentar' => $commenter,
                    'isi_komentar' => $text,
                    'status' => $status,
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ]);
                
                $commentCount++;
            }
        }

        $this->command->info("Created {$commentCount} comments successfully!");
    }
} 