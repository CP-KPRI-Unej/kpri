<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Article Details",
 *     description="API Endpoints for Article Details"
 * )
 */
class ArtikelDetailController extends Controller
{
    /**
     * Display the specified article with its details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/articles/{id}",
     *     summary="Get article details",
     *     description="Returns a specific article with all details including comments",
     *     operationId="getArticleDetail",
     *     tags={"Article Details"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Article Title"),
     *                 @OA\Property(property="content", type="string", example="Full content of the article..."),
     *                 @OA\Property(property="release_date", type="string", format="date", example="2023-12-31"),
     *                 @OA\Property(property="tags", type="string", example="news,update"),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="url", type="string", example="https://example.com/images/article1.jpg")
     *                     )
     *                 ),
     *                 @OA\Property(property="author", type="string", example="John Doe"),
     *                 @OA\Property(
     *                     property="comments",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Commentator Name"),
     *                         @OA\Property(property="content", type="string", example="This is a comment on the article"),
     *                         @OA\Property(property="date", type="string", example="01 Dec 2023 15:30")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        $artikel = Artikel::with(['status', 'images', 'user', 'komentar' => function($query) {
            $query->where('status', 'approved')->orderBy('created_at', 'desc');
        }])->where('id_status', 2) // Only show published articles
        ->findOrFail($id);
        
        $images = $artikel->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => url(Storage::url($image->gambar))
            ];
        });
        
        // Format comments
        $comments = $artikel->komentar->map(function ($komentar) {
            return [
                'id' => $komentar->id_komentar,
                'name' => $komentar->nama_pengomentar,
                'content' => $komentar->isi_komentar,
                'date' => $komentar->created_at->format('d M Y H:i')
            ];
        });
        
        $data = [
            'id' => $artikel->id_artikel,
            'title' => $artikel->nama_artikel,
            'content' => $artikel->deskripsi_artikel,
            'release_date' => $artikel->tgl_rilis,
            'tags' => $artikel->tags_artikel,
            'images' => $images,
            'author' => $artikel->user->nama_user ?? 'Admin',
            'comments' => $comments
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    /**
     * Store a newly created comment for an article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/articles/{id}/comments",
     *     summary="Add a comment to an article",
     *     description="Adds a new comment to a specific article",
     *     operationId="addArticleComment",
     *     tags={"Article Details"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_pengomentar", "isi_komentar"},
     *             @OA\Property(property="nama_pengomentar", type="string", maxLength=100, example="John Doe"),
     *             @OA\Property(property="isi_komentar", type="string", maxLength=255, example="This is my comment on the article")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment submitted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Komentar telah dikirim dan sedang menunggu persetujuan.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function storeComment(Request $request, $id)
    {
        $artikel = Artikel::where('id_status', 2)->findOrFail($id);
        
        $request->validate([
            'nama_pengomentar' => 'required|string|max:100',
            'isi_komentar' => 'required|string|max:255',
        ]);
        
        $artikel->komentar()->create([
            'nama_pengomentar' => $request->nama_pengomentar,
            'isi_komentar' => $request->isi_komentar,
            'status' => 'pending' 
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Komentar telah dikirim dan sedang menunggu persetujuan.'
        ]);
    }
} 