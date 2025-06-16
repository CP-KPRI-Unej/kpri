<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoKpri;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Promos",
 *     description="API Endpoints for promotions"
 * )
 */
class PromoController extends Controller
{
    /**
     * Display a listing of active promotions.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/promos",
     *     tags={"Promos"},
     *     summary="Get list of active promotions",
     *     description="Returns list of all active promotions with their discounted products",
     *     operationId="getPromosList",
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
     *                     @OA\Property(property="judul", type="string", example="Promo Spesial Lebaran"),
     *                     @OA\Property(property="tgl_start", type="string", format="date", example="2023-06-01"),
     *                     @OA\Property(property="tgl_end", type="string", format="date", example="2023-06-30"),
     *                     @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                     @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                     @OA\Property(
     *                         property="produks",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="nama", type="string", example="Beras Premium 5kg"),
     *                             @OA\Property(property="gambar", type="string", example="storage/products/beras.jpg"),
     *                             @OA\Property(property="kategori_id", type="integer", example=1),
     *                             @OA\Property(property="harga_asli", type="integer", example=65000),
     *                             @OA\Property(property="harga_diskon", type="integer", example=52000)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $promos = PromoKpri::with(['produks' => function($query) {
                $query->select('produk_kpri.id_produk', 'nama_produk', 'gambar_produk', 'harga_produk', 'id_kategori');
            }])
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->get()
            ->map(function($promo) {
                return [
                    'id' => $promo->id_promo,
                    'judul' => $promo->judul_promo,
                    'tgl_start' => $promo->tgl_start,
                    'tgl_end' => $promo->tgl_end,
                    'tipe_diskon' => $promo->tipe_diskon,
                    'nilai_diskon' => $promo->nilai_diskon,
                    'produks' => $promo->produks->map(function($produk) use ($promo) {
                        $harga_asli = $produk->harga_produk;
                        $harga_diskon = $this->hitungDiskon($harga_asli, $promo->tipe_diskon, $promo->nilai_diskon);
                        
                        return [
                            'id' => $produk->id_produk,
                            'nama' => $produk->nama_produk,
                            'gambar' => $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : null,
                            'kategori_id' => $produk->id_kategori,
                            'harga_asli' => $harga_asli,
                            'harga_diskon' => $harga_diskon,
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $promos
        ]);
    }

    /**
     * Display the specified promotion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/promos/{id}",
     *     tags={"Promos"},
     *     summary="Get specific promotion details",
     *     description="Returns detailed information about a specific promotion and its products",
     *     operationId="getPromoDetail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
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
     *                 @OA\Property(property="judul", type="string", example="Promo Spesial Lebaran"),
     *                 @OA\Property(property="tgl_start", type="string", format="date", example="2023-06-01"),
     *                 @OA\Property(property="tgl_end", type="string", format="date", example="2023-06-30"),
     *                 @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                 @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                 @OA\Property(
     *                     property="produks",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="nama", type="string", example="Beras Premium 5kg"),
     *                         @OA\Property(property="gambar", type="string", example="storage/products/beras.jpg"),
     *                         @OA\Property(property="kategori_id", type="integer", example=1),
     *                         @OA\Property(property="harga_asli", type="integer", example=65000),
     *                         @OA\Property(property="harga_diskon", type="integer", example=52000),
     *                         @OA\Property(property="stok", type="integer", example=50),
     *                         @OA\Property(property="deskripsi", type="string", example="Beras premium kualitas terbaik")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Promotion not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Promo tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $promo = PromoKpri::with(['produks' => function($query) {
                $query->select('produk_kpri.id_produk', 'nama_produk', 'gambar_produk', 'harga_produk', 'id_kategori', 'stok_produk', 'deskripsi_produk');
            }])
            ->where('id_promo', $id)
            ->where('status', 'aktif')
            ->where('tgl_start', '<=', Carbon::today())
            ->where('tgl_end', '>=', Carbon::today())
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan'
            ], 404);
        }

        $result = [
            'id' => $promo->id_promo,
            'judul' => $promo->judul_promo,
            'tgl_start' => $promo->tgl_start,
            'tgl_end' => $promo->tgl_end,
            'tipe_diskon' => $promo->tipe_diskon,
            'nilai_diskon' => $promo->nilai_diskon,
            'produks' => $promo->produks->map(function($produk) use ($promo) {
                $harga_asli = $produk->harga_produk;
                $harga_diskon = $this->hitungDiskon($harga_asli, $promo->tipe_diskon, $promo->nilai_diskon);
                
                return [
                    'id' => $produk->id_produk,
                    'nama' => $produk->nama_produk,
                    'gambar' => $produk->gambar_produk ? asset('storage/' . $produk->gambar_produk) : null,
                    'kategori_id' => $produk->id_kategori,
                    'harga_asli' => $harga_asli,
                    'harga_diskon' => $harga_diskon,
                    'stok' => $produk->stok_produk,
                    'deskripsi' => $produk->deskripsi_produk,
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
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