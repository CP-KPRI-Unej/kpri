<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Komentar;
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
     *                         @OA\Property(property="date", type="string", example="01 Dec 2023 15:30"),
     *                         @OA\Property(
     *                             property="replies",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=2),
     *                                 @OA\Property(property="name", type="string", example="Reply Author"),
     *                                 @OA\Property(property="content", type="string", example="This is a reply to the comment"),
     *                                 @OA\Property(property="date", type="string", example="01 Dec 2023 16:30"),
     *                                 @OA\Property(property="parent_name", type="string", example="Commentator Name")
     *                             )
     *                         )
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
        $artikel = Artikel::with(['images', 'user'])->where('status', 'published')->findOrFail($id);

        // Get all approved comments for this article
        $comments = Komentar::where('id_artikel', $id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get article images
        $images = $artikel->images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => url(Storage::url($image->gambar))
            ];
        });

        // Format the article data
        $data = [
            'id' => $artikel->id_artikel,
            'title' => $artikel->nama_artikel,
            'content' => $artikel->deskripsi_artikel,
            'release_date' => $artikel->tgl_rilis,
            'tags' => $artikel->tags_artikel,
            'images' => $images,
            'author' => $artikel->user->nama_user ?? 'Admin',
            'comments' => $this->organizeCommentThreads($comments)
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
     *     description="Adds a new comment to a specific article or replies to an existing comment",
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
     *             @OA\Property(property="isi_komentar", type="string", maxLength=255, example="This is my comment on the article"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null, description="ID of parent comment if this is a reply")
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
        $artikel = Artikel::where('status', 'published')->findOrFail($id);

        $request->validate([
            'nama_pengomentar' => 'required|string|max:100',
            'isi_komentar' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:komentar,id_komentar'
        ]);

        // Create the comment or reply
        $comment = new Komentar();
        $comment->id_artikel = $id;
        $comment->nama_pengomentar = $request->nama_pengomentar;
        $comment->isi_komentar = $request->isi_komentar;
        $comment->status = 'pending';
        
        // If parent_id is provided, this is a reply
        if ($request->has('parent_id') && $request->parent_id) {
            // Verify parent comment exists and belongs to this article
            $parentComment = Komentar::where('id_komentar', $request->parent_id)
                ->where('id_artikel', $id)
                ->firstOrFail();
                
            $comment->parent_id = $request->parent_id;
        }
        
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar telah dikirim dan sedang menunggu persetujuan.'
        ]);
    }
    
    /**
     * Get comments for an article.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/articles/{id}/comments",
     *     summary="Get article comments",
     *     description="Returns all approved comments for an article organized in threads",
     *     operationId="getArticleComments",
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
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Commentator Name"),
     *                     @OA\Property(property="content", type="string", example="This is a comment on the article"),
     *                     @OA\Property(property="date", type="string", example="01 Dec 2023 15:30"),
     *                     @OA\Property(
     *                         property="replies",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Reply Author"),
     *                             @OA\Property(property="content", type="string", example="This is a reply to the comment"),
     *                             @OA\Property(property="date", type="string", example="01 Dec 2023 16:30"),
     *                             @OA\Property(property="parent_name", type="string", example="Commentator Name")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getComments($id)
    {
        // Verify article exists and is published
        $artikel = Artikel::where('status', 'published')->findOrFail($id);
        
        // Get all approved comments for this article
        $comments = Komentar::where('id_artikel', $id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $this->organizeCommentThreads($comments)
        ]);
    }
    
    /**
     * Get a specific comment with its replies.
     *
     * @param  int  $id
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/articles/{id}/comments/{commentId}",
     *     summary="Get a specific comment with replies",
     *     description="Returns a specific comment and all its replies",
     *     operationId="getSpecificComment",
     *     tags={"Article Details"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="commentId",
     *         in="path",
     *         description="Comment ID",
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
     *                 @OA\Property(property="name", type="string", example="Commentator Name"),
     *                 @OA\Property(property="content", type="string", example="This is a comment on the article"),
     *                 @OA\Property(property="date", type="string", example="01 Dec 2023 15:30"),
     *                 @OA\Property(
     *                     property="replies",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Reply Author"),
     *                         @OA\Property(property="content", type="string", example="This is a reply to the comment"),
     *                         @OA\Property(property="date", type="string", example="01 Dec 2023 16:30"),
     *                         @OA\Property(property="parent_name", type="string", example="Commentator Name")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getComment($id, $commentId)
    {
        // Verify article exists and is published
        $artikel = Artikel::where('status', 'published')->findOrFail($id);
        
        // Get the specific comment
        $comment = Komentar::where('id_artikel', $id)
            ->where('id_komentar', $commentId)
            ->where('status', 'approved')
            ->firstOrFail();
        
        // Get all replies to this comment
        $replies = Komentar::where('id_artikel', $id)
            ->where('parent_id', $commentId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($reply) {
                // Get parent commenter name
                $parentName = Komentar::where('id_komentar', $reply->parent_id)
                    ->value('nama_pengomentar');
                
                return [
                    'id' => $reply->id_komentar,
                    'name' => $reply->nama_pengomentar,
                    'content' => $reply->isi_komentar,
                    'date' => $reply->created_at->format('d M Y H:i'),
                    'parent_name' => $parentName
                ];
            });
        
        $data = [
            'id' => $comment->id_komentar,
            'name' => $comment->nama_pengomentar,
            'content' => $comment->isi_komentar,
            'date' => $comment->created_at->format('d M Y H:i'),
            'replies' => $replies
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    /**
     * Helper function to organize comments into threads.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $comments
     * @return array
     */
    private function organizeCommentThreads($comments)
    {
        // Create a map of all comments by ID for easy lookup
        $commentMap = [];
        foreach ($comments as $comment) {
            $commentMap[$comment->id_komentar] = $comment;
        }
        
        // Organize comments into threads (parent comments and their replies)
        $commentThreads = [];
        $repliesMap = [];

        // First, separate parent comments and replies
        foreach ($comments as $comment) {
            if ($comment->parent_id === null) {
                // This is a parent comment
                $commentThreads[$comment->id_komentar] = [
                    'id' => $comment->id_komentar,
                    'name' => $comment->nama_pengomentar,
                    'content' => $comment->isi_komentar,
                    'date' => $comment->created_at->format('d M Y H:i'),
                    'replies' => []
                ];
            } else {
                // This is a reply
                $parentName = isset($commentMap[$comment->parent_id]) ? 
                    $commentMap[$comment->parent_id]->nama_pengomentar : 'Unknown';
                
                $repliesMap[$comment->parent_id][] = [
                    'id' => $comment->id_komentar,
                    'name' => $comment->nama_pengomentar,
                    'content' => $comment->isi_komentar,
                    'date' => $comment->created_at->format('d M Y H:i'),
                    'parent_name' => $parentName
                ];
            }
        }

        // Add replies to their parent comments
        foreach ($repliesMap as $parentId => $replies) {
            if (isset($commentThreads[$parentId])) {
                $commentThreads[$parentId]['replies'] = $replies;
            }
        }

        return array_values($commentThreads); // Convert associative array to indexed array
    }
}
