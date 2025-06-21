<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\HeroBeranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Hero Banners",
 *     description="API Endpoints for Hero Banner Management"
 * )
 */
class HeroBerandaController extends Controller
{
    /**
     * Display a listing of all active hero banners for the front page.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/hero-banners",
     *     summary="Get all active hero banners",
     *     description="Returns a list of all active hero banners for the front page",
     *     operationId="getHeroBanners",
     *     tags={"Hero Banners"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Welcome to KPRI"),
     *                     @OA\Property(property="description", type="string", example="Find our best products and services"),
     *                     @OA\Property(property="image_url", type="string", example="https://example.com/storage/hero/hero_123456.jpg"),
     *                     @OA\Property(property="url", type="string", example="https://example.com/promo")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Hero banners retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        try {
            // Get only active hero banners (id_status = 1)
            $heroes = HeroBeranda::with('status')
                ->where('id_status', 1)
                ->orderBy('id_hero', 'desc')
                ->get();

            $formattedHeroes = $heroes->map(function ($hero) {
                $imageUrl = null;
                if ($hero->gambar) {
                    $imageUrl = url(Storage::url($hero->gambar));
                }
                
                return [
                    'id' => $hero->id_hero,
                    'title' => $hero->judul,
                    'description' => $hero->deskripsi,
                    'image_url' => $imageUrl,
                    'url' => $hero->url
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedHeroes,
                'message' => 'Hero banners retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve hero banners: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified hero banner.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/hero-banners/{id}",
     *     summary="Get hero banner by ID",
     *     description="Returns a specific hero banner by ID",
     *     operationId="getHeroBannerById",
     *     tags={"Hero Banners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Hero banner ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Welcome to KPRI"),
     *                 @OA\Property(property="description", type="string", example="Find our best products and services"),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/storage/hero/hero_123456.jpg"),
     *                 @OA\Property(property="url", type="string", example="https://example.com/promo")
     *             ),
     *             @OA\Property(property="message", type="string", example="Hero banner retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Hero banner not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        try {
            $hero = HeroBeranda::where('id_status', 1)->findOrFail($id);

            $imageUrl = null;
            if ($hero->gambar) {
                $imageUrl = url(Storage::url($hero->gambar));
            }
            
            $formattedHero = [
                'id' => $hero->id_hero,
                'title' => $hero->judul,
                'description' => $hero->deskripsi,
                'image_url' => $imageUrl,
                'url' => $hero->url
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formattedHero,
                'message' => 'Hero banner retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hero banner not found'
            ], 404);
        }
    }
} 