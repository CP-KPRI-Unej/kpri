<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            StatusSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            JabatanSeeder::class,
            PeriodeKepengurusanSeeder::class,
            StrukturKepengurusanSeeder::class,
            ArtikelSeeder::class,
            KomentarSeeder::class,
            HalamanSeeder::class,
             KategoriProdukSeeder::class,
            ProdukKpriSeeder::class,
            PromoKpriSeeder::class,
            ProdukPromoSeeder::class,
        ]);
    }
}
class KategoriProdukSeeder extends Seeder
{
    public function run()
    {
        DB::table('kategori_produk')->insert([
            ['kategori' => 'Beras'],
            ['kategori' => 'Minyak Goreng'],
            ['kategori' => 'Gula'],
            ['kategori' => 'Tepung'],
            ['kategori' => 'Bumbu Dapur'],
            ['kategori' => 'Makanan Ringan'],
            ['kategori' => 'Minuman'],
            ['kategori' => 'Perlengkapan Rumah'],
            ['kategori' => 'Alat Tulis'],
            ['kategori' => 'Elektronik'],
        ]);
    }
}

class ProdukKpriSeeder extends Seeder
{
    public function run()
    {
        DB::table('produk_kpri')->insert([
            // Beras (id_kategori = 1)
            [
                'gambar_produk' => 'beras_premium.jpg',
                'id_kategori' => 1,
                'nama_produk' => 'Beras Premium 5kg',
                'harga_produk' => 75000,
                'stok_produk' => 50,
                'deskripsi_produk' => 'Beras premium kualitas terbaik, pulen dan wangi'
            ],
            [
                'gambar_produk' => 'beras_lokal.jpg',
                'id_kategori' => 1,
                'nama_produk' => 'Beras Lokal 10kg',
                'harga_produk' => 120000,
                'stok_produk' => 30,
                'deskripsi_produk' => 'Beras lokal berkualitas tinggi'
            ],
            
            // Minyak Goreng (id_kategori = 2)
            [
                'gambar_produk' => 'minyak_tropical.jpg',
                'id_kategori' => 2,
                'nama_produk' => 'Minyak Goreng Tropical 2L',
                'harga_produk' => 32000,
                'stok_produk' => 100,
                'deskripsi_produk' => 'Minyak goreng jernih dan tidak mudah berbusa'
            ],
            [
                'gambar_produk' => 'minyak_bimoli.jpg',
                'id_kategori' => 2,
                'nama_produk' => 'Minyak Goreng Bimoli 1L',
                'harga_produk' => 18000,
                'stok_produk' => 75,
                'deskripsi_produk' => 'Minyak goreng berkualitas untuk masakan sehari-hari'
            ],
            
            // Gula (id_kategori = 3)
            [
                'gambar_produk' => 'gula_pasir.jpg',
                'id_kategori' => 3,
                'nama_produk' => 'Gula Pasir 1kg',
                'harga_produk' => 15000,
                'stok_produk' => 80,
                'deskripsi_produk' => 'Gula pasir putih bersih dan manis'
            ],
            [
                'gambar_produk' => 'gula_merah.jpg',
                'id_kategori' => 3,
                'nama_produk' => 'Gula Merah 500gr',
                'harga_produk' => 12000,
                'stok_produk' => 40,
                'deskripsi_produk' => 'Gula merah asli dari aren'
            ],
            
            // Tepung (id_kategori = 4)
            [
                'gambar_produk' => 'tepung_terigu.jpg',
                'id_kategori' => 4,
                'nama_produk' => 'Tepung Terigu Segitiga Biru 1kg',
                'harga_produk' => 14000,
                'stok_produk' => 60,
                'deskripsi_produk' => 'Tepung terigu protein sedang untuk berbagai keperluan'
            ],
            [
                'gambar_produk' => 'tepung_beras.jpg',
                'id_kategori' => 4,
                'nama_produk' => 'Tepung Beras 500gr',
                'harga_produk' => 8000,
                'stok_produk' => 45,
                'deskripsi_produk' => 'Tepung beras untuk kue dan makanan tradisional'
            ],
            
            // Bumbu Dapur (id_kategori = 5)
            [
                'gambar_produk' => 'bumbu_racik.jpg',
                'id_kategori' => 5,
                'nama_produk' => 'Bumbu Racik Nasi Goreng',
                'harga_produk' => 3500,
                'stok_produk' => 120,
                'deskripsi_produk' => 'Bumbu racik instan untuk nasi goreng'
            ],
            [
                'gambar_produk' => 'kecap_manis.jpg',
                'id_kategori' => 5,
                'nama_produk' => 'Kecap Manis ABC 600ml',
                'harga_produk' => 18000,
                'stok_produk' => 90,
                'deskripsi_produk' => 'Kecap manis premium untuk masakan Indonesia'
            ],
            
            // Makanan Ringan (id_kategori = 6)
            [
                'gambar_produk' => 'keripik_singkong.jpg',
                'id_kategori' => 6,
                'nama_produk' => 'Keripik Singkong Pedas 100gr',
                'harga_produk' => 8000,
                'stok_produk' => 150,
                'deskripsi_produk' => 'Keripik singkong renyah dengan bumbu pedas'
            ],
            [
                'gambar_produk' => 'biskuit_marie.jpg',
                'id_kategori' => 6,
                'nama_produk' => 'Biskuit Marie Regal 300gr',
                'harga_produk' => 12000,
                'stok_produk' => 85,
                'deskripsi_produk' => 'Biskuit marie klasik untuk teman teh'
            ],
            
            // Minuman (id_kategori = 7)
            [
                'gambar_produk' => 'teh_kotak.jpg',
                'id_kategori' => 7,
                'nama_produk' => 'Teh Kotak Jasmine 200ml',
                'harga_produk' => 4000,
                'stok_produk' => 200,
                'deskripsi_produk' => 'Teh jasmine segar dalam kemasan praktis'
            ],
            [
                'gambar_produk' => 'kopi_kapal_api.jpg',
                'id_kategori' => 7,
                'nama_produk' => 'Kopi Kapal Api Mix 20 sachet',
                'harga_produk' => 25000,
                'stok_produk' => 70,
                'deskripsi_produk' => 'Kopi instan dengan gula dan krimer'
            ],
            
            // Perlengkapan Rumah (id_kategori = 8)
            [
                'gambar_produk' => 'sabun_cuci_piring.jpg',
                'id_kategori' => 8,
                'nama_produk' => 'Sabun Cuci Piring Sunlight 750ml',
                'harga_produk' => 15000,
                'stok_produk' => 95,
                'deskripsi_produk' => 'Sabun cuci piring jeruk nipis'
            ],
            [
                'gambar_produk' => 'deterjen_rinso.jpg',
                'id_kategori' => 8,
                'nama_produk' => 'Deterjen Rinso 1kg',
                'harga_produk' => 22000,
                'stok_produk' => 65,
                'deskripsi_produk' => 'Deterjen untuk mencuci pakaian'
            ],
        ]);
    }
}

class PromoKpriSeeder extends Seeder
{
    public function run()
    {
        // Asumsi id_user = 1 (admin/user yang membuat promo)
        DB::table('promo_kpri')->insert([
            [
                'id_user' => 1,
                'judul_promo' => 'Diskon Ramadhan 15%',
                'tgl_start' => '2024-03-01',
                'tgl_end' => '2024-04-15',
                'tipe_diskon' => 'persen',
                'nilai_diskon' => 15,
                'status' => 'aktif'
            ],
            [
                'id_user' => 1,
                'judul_promo' => 'Hemat Belanja Rp 10.000',
                'tgl_start' => '2024-06-01',
                'tgl_end' => '2024-06-30',
                'tipe_diskon' => 'nominal',
                'nilai_diskon' => 10000,
                'status' => 'aktif'
            ],
            [
                'id_user' => 1,
                'judul_promo' => 'Flash Sale 25%',
                'tgl_start' => '2024-05-01',
                'tgl_end' => '2024-05-31',
                'tipe_diskon' => 'persen',
                'nilai_diskon' => 25,
                'status' => 'berakhir'
            ],
            [
                'id_user' => 1,
                'judul_promo' => 'Promo Akhir Bulan',
                'tgl_start' => '2024-07-25',
                'tgl_end' => '2024-07-31',
                'tipe_diskon' => 'persen',
                'nilai_diskon' => 20,
                'status' => 'nonaktif'
            ],
            [
                'id_user' => 1,
                'judul_promo' => 'Diskon Sembako Rp 5.000',
                'tgl_start' => '2024-06-15',
                'tgl_end' => '2024-07-15',
                'tipe_diskon' => 'nominal',
                'nilai_diskon' => 5000,
                'status' => 'aktif'
            ]
        ]);
    }
}

class ProdukPromoSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID produk yang ada di database
        $produkIds = DB::table('produk_kpri')->pluck('id_produk')->toArray();
        $promoIds = DB::table('promo_kpri')->pluck('id_promo')->toArray();
        
        // Pastikan ada produk dan promo di database
        if (empty($produkIds) || empty($promoIds)) {
            return;
        }
        
        // Buat relasi produk-promo dengan ID yang benar-benar ada
        $produkPromo = [];
        
        // Promo pertama - ambil 4 produk pertama
        if (isset($promoIds[0])) {
            for ($i = 0; $i < min(4, count($produkIds)); $i++) {
                $produkPromo[] = [
                    'id_produk' => $produkIds[$i],
                    'id_promo' => $promoIds[0]
                ];
            }
        }
        
        // Promo kedua - ambil 3 produk dari tengah
        if (isset($promoIds[1]) && count($produkIds) >= 7) {
            for ($i = 4; $i < min(7, count($produkIds)); $i++) {
                $produkPromo[] = [
                    'id_produk' => $produkIds[$i],
                    'id_promo' => $promoIds[1]
                ];
            }
        }
        
        // Promo ketiga - ambil 4 produk selanjutnya
        if (isset($promoIds[2]) && count($produkIds) >= 11) {
            for ($i = 7; $i < min(11, count($produkIds)); $i++) {
                $produkPromo[] = [
                    'id_produk' => $produkIds[$i],
                    'id_promo' => $promoIds[2]
                ];
            }
        }
        
        // Promo keempat - ambil 4 produk selanjutnya
        if (isset($promoIds[3]) && count($produkIds) >= 15) {
            for ($i = 11; $i < min(15, count($produkIds)); $i++) {
                $produkPromo[] = [
                    'id_produk' => $produkIds[$i],
                    'id_promo' => $promoIds[3]
                ];
            }
        }
        
        // Promo kelima - ambil 5 produk pertama lagi (bisa overlap)
        if (isset($promoIds[4])) {
            for ($i = 0; $i < min(5, count($produkIds)); $i++) {
                $produkPromo[] = [
                    'id_produk' => $produkIds[$i],
                    'id_promo' => $promoIds[4]
                ];
            }
        }
        
        // Hapus duplikat kombinasi id_produk dan id_promo
        $produkPromo = array_unique($produkPromo, SORT_REGULAR);
        
        // Insert ke database
        if (!empty($produkPromo)) {
            DB::table('produk_promo')->insert($produkPromo);
        }
    }
}