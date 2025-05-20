<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Articles",
 *     description="API Endpoints for Articles"
 * )
 */
class ArtikelController extends Controller
{
    /**
     * Display a listing of the articles.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/articles",
     *     summary="Get all published articles",
     *     description="Returns list of published articles",
     *     operationId="getArticlesList",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Article Title"),
     *                     @OA\Property(property="excerpt", type="string", example="This is a short excerpt of the article..."),
     *                     @OA\Property(property="release_date", type="string", format="date", example="2023-12-31"),
     *                     @OA\Property(property="tags", type="string", example="news,update"),
     *                     @OA\Property(property="thumbnail", type="string", example="https://example.com/images/article1.jpg"),
     *                     @OA\Property(property="author", type="string", example="John Doe")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        $artikels = Artikel::with(['status', 'images', 'user'])
            ->where('id_status', 2) // Only show published articles
            ->orderBy('tgl_rilis', 'desc')
            ->get()
            ->map(function ($artikel) {
                $images = $artikel->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => url(Storage::url($image->gambar))
                    ];
                });
                
                return [
                    'id' => $artikel->id_artikel,
                    'title' => $artikel->nama_artikel,
                    'excerpt' => substr(strip_tags($artikel->deskripsi_artikel), 0, 150) . '...',
                    'release_date' => $artikel->tgl_rilis,
                    'tags' => $artikel->tags_artikel,
                    'thumbnail' => $images->isNotEmpty() ? $images->first()['url'] : null,
                    'author' => $artikel->user->nama_user ?? 'Admin',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $artikels
        ]);
    }
} 