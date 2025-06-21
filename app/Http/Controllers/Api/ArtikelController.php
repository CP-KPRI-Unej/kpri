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
        $artikels = Artikel::with(['images', 'user'])
            ->where('status', 'published') // Use status column with 'published' value instead of id_status = 2
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
    
    /**
     * Display a listing of articles filtered by tag.
     *
     * @param  string  $tag
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/articles/tag/{tag}",
     *     summary="Get articles by tag",
     *     description="Returns list of published articles filtered by tag",
     *     operationId="getArticlesByTag",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="tag",
     *         in="path",
     *         description="Tag to filter by",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
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
    public function getByTag($tag)
    {
        $artikels = Artikel::with(['images', 'user'])
            ->where('status', 'published')
            ->where(function($query) use ($tag) {
                // Use LIKE to find articles with the tag in the tags_artikel field
                $query->where('tags_artikel', 'LIKE', "%$tag%");
            })
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
    
    /**
     * Get all available tags from articles.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/articles/tags",
     *     summary="Get all article tags",
     *     description="Returns a list of all unique tags used in articles",
     *     operationId="getArticleTags",
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
     *                 @OA\Items(type="string", example="news")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getAllTags()
    {
        $articles = Artikel::where('status', 'published')
            ->whereNotNull('tags_artikel')
            ->get(['tags_artikel']);
            
        $allTags = [];
        
        foreach ($articles as $article) {
            $tags = explode(',', $article->tags_artikel);
            foreach ($tags as $tag) {
                $trimmedTag = trim($tag);
                if (!empty($trimmedTag) && !in_array($trimmedTag, $allTags)) {
                    $allTags[] = $trimmedTag;
                }
            }
        }
        
        sort($allTags);
        
        return response()->json([
            'status' => 'success',
            'data' => $allTags
        ]);
    }
} 