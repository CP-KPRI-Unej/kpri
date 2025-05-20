<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProdukKpri;
use App\Models\KategoriProduk;
use App\Models\PromoKpri;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for products"
 * )
 */
class ProdukController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Get list of products",
     *     description="Returns list of products with optional filtering, sorting and searching",
     *     operationId="getProductsList",
     *     @OA\Parameter(
     *         name="kategori_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for product name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Field to order by (nama_produk, harga_produk, id_produk)",
     *         required=false,
     *         @OA\Schema(type="string", default="nama_produk", enum={"nama_produk", "harga_produk", "id_produk"})
     *     ),
     *     @OA\Parameter(
     *         name="order_dir",
     *         in="query",
     *         description="Order direction (asc, desc)",
     *         required=false,
     *         @OA\Schema(type="string", default="asc", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="Beras Premium 5kg"),
     *                     @OA\Property(property="gambar", type="string", example="storage/products/beras.jpg"),
     *                     @OA\Property(property="harga", type="integer", example=65000),
     *                     @OA\Property(
     *                         property="kategori",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="nama", type="string", example="Sembako")
     *                     ),
     *                     @OA\Property(property="stok", type="integer", example=50),
     *                     @OA\Property(property="has_promo", type="boolean", example=true),
     *                     @OA\Property(property="harga_diskon", type="integer", example=52000),
     *                     @OA\Property(
     *                         property="promo",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="judul", type="string", example="Promo Spesial Lebaran"),
     *                         @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                         @OA\Property(property="nilai_diskon", type="integer", example=20)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
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
        
        // Get all products without pagination
        $produks = $query->get();
        
        // Get active promos for price calculations
        $activePromos = PromoKpri::with('produks')
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->get();
            
        // Transform product data and include promo information
        $produktData = [];
        foreach ($produks as $produk) {
            $promo = $this->getActivePromoForProduct($produk->id_produk, $activePromos);
            
            $data = [
                'id' => $produk->id_produk,
                'nama' => $produk->nama_produk,
                'gambar' => $produk->gambar_produk,
                'harga' => $produk->harga_produk,
                'kategori' => [
                    'id' => $produk->kategori->id_kategori ?? null,
                    'nama' => $produk->kategori->kategori ?? null
                ],
                'stok' => $produk->stok_produk,
                'has_promo' => !!$promo
            ];
            
            if ($promo) {
                $data['harga_diskon'] = $this->hitungDiskon(
                    $produk->harga_produk, 
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
            
            $produktData[] = $data;
        }
        
        return response()->json([
            'success' => true,
            'data' => $produktData
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Get specific product details",
     *     description="Returns detailed information about a specific product",
     *     operationId="getProductDetail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="Beras Premium 5kg"),
     *                 @OA\Property(property="gambar", type="string", example="storage/products/beras.jpg"),
     *                 @OA\Property(property="harga", type="integer", example=65000),
     *                 @OA\Property(
     *                     property="kategori",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="Sembako")
     *                 ),
     *                 @OA\Property(property="stok", type="integer", example=50),
     *                 @OA\Property(property="deskripsi", type="string", example="Beras premium kualitas terbaik"),
     *                 @OA\Property(property="has_promo", type="boolean", example=true),
     *                 @OA\Property(property="harga_diskon", type="integer", example=52000),
     *                 @OA\Property(
     *                     property="promo",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="judul", type="string", example="Promo Spesial Lebaran"),
     *                     @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                     @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                     @OA\Property(property="tgl_end", type="string", format="date", example="2023-06-30")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Produk tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $produk = ProdukKpri::with('kategori')
            ->where('id_produk', $id)
            ->first();

        if (!$produk) {
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
                $query->where('id_produk', $id);
            })
            ->first();
            
        $result = [
            'id' => $produk->id_produk,
            'nama' => $produk->nama_produk,
            'gambar' => $produk->gambar_produk,
            'harga' => $produk->harga_produk,
            'kategori' => [
                'id' => $produk->kategori->id_kategori ?? null,
                'nama' => $produk->kategori->kategori ?? null
            ],
            'stok' => $produk->stok_produk,
            'deskripsi' => $produk->deskripsi_produk,
            'has_promo' => !!$activePromo
        ];
        
        if ($activePromo) {
            $result['harga_diskon'] = $this->hitungDiskon(
                $produk->harga_produk, 
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

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
    /**
     * Get all product categories.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/product-categories",
     *     tags={"Products"},
     *     summary="Get all product categories",
     *     description="Returns all product categories with product counts",
     *     operationId="getProductCategories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="Sembako"),
     *                     @OA\Property(property="jumlah_produk", type="integer", example=12)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function categories()
    {
        $categories = KategoriProduk::withCount('produks')
            ->orderBy('kategori')
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id_kategori,
                    'nama' => $category->kategori,
                    'jumlah_produk' => $category->produks_count
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
    
    /**
     * Get active promo for a product.
     *
     * @param  int  $productId
     * @param  \Illuminate\Database\Eloquent\Collection  $activePromos
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
     * @param  int  $harga_asli
     * @param  string  $tipe_diskon
     * @param  int  $nilai_diskon
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