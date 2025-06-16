<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\PromoKpri;
use App\Models\ProdukKpri;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokoApiController extends Controller
{
    /**
     * Display a listing of products with filtering and sorting
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(Request $request)
    {
        // Build query for products
        $query = ProdukKpri::with('kategori');
        
        // Filter by category if provided
        if ($request->has('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }
        
        // Filter by search term if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi_produk', 'like', "%{$search}%");
            });
        }
        
        // Order products
        $orderBy = $request->order_by ?? 'nama_produk';
        $orderDir = $request->order_dir ?? 'asc';
        
        if (in_array($orderBy, ['nama_produk', 'harga_produk', 'id_produk'])) {
            $query->orderBy($orderBy, $orderDir);
        }
        
        // Paginate products 
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);
        
        // Get active promotions for price calculations
        $activePromos = PromoKpri::with('produks')
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->get();
        
        // Transform product data and include promotion information
        $transformedProducts = [];
        foreach ($products as $product) {
            $promo = $this->getActivePromoForProduct($product->id_produk, $activePromos);
            
            $data = [
                'id' => $product->id_produk,
                'nama' => $product->nama_produk,
                'gambar' => $product->gambar_produk ? asset('storage/' . $product->gambar_produk) : null,
                'harga' => $product->harga_produk,
                'kategori' => [
                    'id' => $product->kategori->id_kategori ?? null,
                    'nama' => $product->kategori->kategori ?? null
                ],
                'stok' => $product->stok_produk,
                'has_promo' => !!$promo
            ];
            
            if ($promo) {
                $data['harga_diskon'] = $this->hitungDiskon(
                    $product->harga_produk, 
                    $promo->tipe_diskon, 
                    $promo->nilai_diskon
                );
                $data['promo'] = [
                    'id' => $promo->id_promo,
                    'judul' => $promo->judul_promo,
                    'tipe_diskon' => $promo->tipe_diskon,
                    'nilai_diskon' => $promo->nilai_diskon
                ];
            }
            
            $transformedProducts[] = $data;
        }
        
        return response()->json([
            'success' => true,
            'data' => $transformedProducts,
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }
    
    /**
     * Display the specified product with promotion and related products
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct($id)
    {
        $product = ProdukKpri::with('kategori')
            ->where('id_produk', $id)
            ->first();
            
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        // Get active promo for this product
        $activePromo = PromoKpri::with('produks')
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->whereHas('produks', function($query) use ($id) {
                $query->where('produk_kpri.id_produk', $id);
            })
            ->first();
            
        $result = [
            'id' => $product->id_produk,
            'nama' => $product->nama_produk,
            'gambar' => $product->gambar_produk ? asset('storage/' . $product->gambar_produk) : null,
            'harga' => $product->harga_produk,
            'kategori' => [
                'id' => $product->kategori->id_kategori ?? null,
                'nama' => $product->kategori->kategori ?? null
            ],
            'stok' => $product->stok_produk,
            'deskripsi' => $product->deskripsi_produk,
            'has_promo' => !!$activePromo
        ];
        
        if ($activePromo) {
            $result['harga_diskon'] = $this->hitungDiskon(
                $product->harga_produk, 
                $activePromo->tipe_diskon, 
                $activePromo->nilai_diskon
            );
            $result['promo'] = [
                'id' => $activePromo->id_promo,
                'judul' => $activePromo->judul_promo,
                'tipe_diskon' => $activePromo->tipe_diskon,
                'nilai_diskon' => $activePromo->nilai_diskon,
                'tgl_end' => $activePromo->tgl_end
            ];
        }
        
        // Get related products (same category, limit to 4)
        $relatedProducts = ProdukKpri::where('id_kategori', $product->id_kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->take(4)
            ->get();
            
        // Get active promos for related products
        $activePromos = PromoKpri::with('produks')
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->get();
            
        // Transform related products
        $transformedRelated = [];
        foreach ($relatedProducts as $relatedProduct) {
            $promo = $this->getActivePromoForProduct($relatedProduct->id_produk, $activePromos);
            
            $data = [
                'id' => $relatedProduct->id_produk,
                'nama' => $relatedProduct->nama_produk,
                'gambar' => $relatedProduct->gambar_produk ? asset('storage/' . $relatedProduct->gambar_produk) : null,
                'harga' => $relatedProduct->harga_produk,
                'kategori' => [
                    'id' => $relatedProduct->kategori->id_kategori ?? null,
                    'nama' => $relatedProduct->kategori->kategori ?? null
                ],
                'stok' => $relatedProduct->stok_produk,
                'has_promo' => !!$promo
            ];
            
            if ($promo) {
                $data['harga_diskon'] = $this->hitungDiskon(
                    $relatedProduct->harga_produk, 
                    $promo->tipe_diskon, 
                    $promo->nilai_diskon
                );
                $data['promo'] = [
                    'id' => $promo->id_promo,
                    'judul' => $promo->judul_promo,
                    'tipe_diskon' => $promo->tipe_diskon,
                    'nilai_diskon' => $promo->nilai_diskon
                ];
            }
            
            $transformedRelated[] = $data;
        }
        
        $result['related_products'] = $transformedRelated;

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Get all product categories with counts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = KategoriProduk::withCount('produks')
            ->orderBy('kategori')
            ->get();
            
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id_kategori,
                'nama' => $category->kategori,
                'jumlah_produk' => $category->produks_count
            ];
        }
            
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Get active promotions with their products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPromotions()
    {
        $promos = PromoKpri::with(['produks' => function($query) {
                $query->select('produk_kpri.id_produk', 'nama_produk', 'gambar_produk', 'harga_produk', 'id_kategori');
            }])
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->get();
            
        $result = [];
        foreach ($promos as $promo) {
            $promoData = [
                'id' => $promo->id_promo,
                'judul' => $promo->judul_promo,
                'tgl_start' => $promo->tgl_start,
                'tgl_end' => $promo->tgl_end,
                'tipe_diskon' => $promo->tipe_diskon,
                'nilai_diskon' => $promo->nilai_diskon,
                'produks' => []
            ];
            
            foreach ($promo->produks as $produk) {
                $harga_asli = $produk->harga_produk;
                $harga_diskon = $this->hitungDiskon($harga_asli, $promo->tipe_diskon, $promo->nilai_diskon);
                
                $promoData['produks'][] = [
                    'id' => $produk->id_produk,
                    'nama' => $produk->nama_produk,
                    'gambar' => $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : null,
                    'kategori_id' => $produk->id_kategori,
                    'harga_asli' => $harga_asli,
                    'harga_diskon' => $harga_diskon,
                ];
            }
            
            $result[] = $promoData;
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Get active promo for a product.
     *
     * @param int $productId
     * @param \Illuminate\Database\Eloquent\Collection $activePromos
     * @return \App\Models\PromoKpri|null
     */
    private function getActivePromoForProduct($productId, $activePromos)
    {
        foreach ($activePromos as $promo) {
            $productIds = $promo->produks->pluck('id_produk')->toArray();
            if (in_array($productId, $productIds)) {
                return $promo;
            }
        }
        
        return null;
    }
    
    /**
     * Calculate discount price based on discount type and value.
     *
     * @param int $harga_asli
     * @param string $tipe_diskon
     * @param int $nilai_diskon
     * @return int
     */
    private function hitungDiskon($harga_asli, $tipe_diskon, $nilai_diskon)
    {
        if ($tipe_diskon === 'persen') {
            $diskon = $harga_asli * ($nilai_diskon / 100);
            return $harga_asli - $diskon;
        } else { // nominal
            return max(0, $harga_asli - $nilai_diskon);
        }
    }
} 