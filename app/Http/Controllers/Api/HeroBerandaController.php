<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\HeroBeranda;
use Illuminate\Http\Request;

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
     *     summary="Get all hero banners",
     *     description="Returns a list of all hero banners for the front page",
     *     operationId="getHeroBanners",
     *     tags={"Hero Banners"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_hero", type="integer", example=1),
     *                     @OA\Property(property="judul_hero", type="string", example="Welcome to KPRI"),
     *                     @OA\Property(property="deskripsi_hero", type="string", example="Find our best products and services"),
     *                     @OA\Property(property="gambar_hero", type="string", example="uploads/hero/hero_123456.jpg"),
     *                     @OA\Property(property="link_hero", type="string", example="https://example.com/promo"),
     *                     @OA\Property(property="status_hero", type="string", example="aktif"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
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
            $heroes = HeroBeranda::orderBy('id_hero', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $heroes,
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
     *                 @OA\Property(property="id_hero", type="integer", example=1),
     *                 @OA\Property(property="judul_hero", type="string", example="Welcome to KPRI"),
     *                 @OA\Property(property="deskripsi_hero", type="string", example="Find our best products and services"),
     *                 @OA\Property(property="gambar_hero", type="string", example="uploads/hero/hero_123456.jpg"),
     *                 @OA\Property(property="link_hero", type="string", example="https://example.com/promo"),
     *                 @OA\Property(property="status_hero", type="string", example="aktif"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
            $hero = HeroBeranda::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $hero,
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