
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
