<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Komentar;
use App\Models\ArtikelImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Articles",
 *     description="API Endpoints for Admin Article Management"
 * )
 */
class AdminArtikelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:kpri admin');
    }

    /**
     * Get all articles with their comments count and pending comments count
     * with pagination support
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/articles",
     *     summary="Get all articles with pagination",
     *     description="Returns paginated list of articles with comment counts",
     *     operationId="adminGetArticles",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="sort_field",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", default="tgl_rilis")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_artikel", type="integer", example=1),
     *                     @OA\Property(property="nama_artikel", type="string", example="Article Title"),
     *                     @OA\Property(property="deskripsi_artikel", type="string", example="Article content..."),
     *                     @OA\Property(property="status", type="string", example="published"),
     *                     @OA\Property(property="tgl_rilis", type="string", format="date", example="2023-12-31"),
     *                     @OA\Property(property="tags_artikel", type="string", example="news,update"),
     *                     @OA\Property(property="total_comments", type="integer", example=5),
     *                     @OA\Property(property="pending_comments", type="integer", example=2),
     *                     @OA\Property(property="has_pending_comments", type="boolean", example=true),
     *                     @OA\Property(property="user_name", type="string", example="Admin User")
     *                 )
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=25),
     *                 @OA\Property(property="total_pages", type="integer", example=3),
     *                 @OA\Property(property="sort_field", type="string", example="tgl_rilis"),
     *                 @OA\Property(property="sort_direction", type="string", example="desc"),
     *                 @OA\Property(property="search", type="string", example="news")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        // Get pagination parameters
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'tgl_rilis');
        $sortDirection = $request->input('sort_direction', 'desc');
        $search = $request->input('search', '');
        
        // Start query builder
        $query = Artikel::with(['komentar', 'user']);
        
        // Apply search if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_artikel', 'like', "%{$search}%")
                  ->orWhere('deskripsi_artikel', 'like', "%{$search}%")
                  ->orWhere('tags_artikel', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Get total count before pagination
        $totalCount = $query->count();
        
        // Apply pagination
        $artikels = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();

        // Transform data to include counts
        $transformedArtikels = $artikels->map(function ($artikel) {
            $pendingComments = $artikel->komentar->where('status', 'pending')->count();

            return [
                'id_artikel' => $artikel->id_artikel,
                'nama_artikel' => $artikel->nama_artikel,
                'deskripsi_artikel' => $artikel->deskripsi_artikel,
                'status' => $artikel->status,
                'tgl_rilis' => $artikel->tgl_rilis,
                'tags_artikel' => $artikel->tags_artikel,
                'total_comments' => $artikel->komentar->count(),
                'pending_comments' => $pendingComments,
                'has_pending_comments' => $pendingComments > 0,
                'user_name' => $artikel->user ? $artikel->user->nama_user : 'Unknown'
            ];
        });

        // Return paginated response
        return response()->json([
            'data' => $transformedArtikels,
            'meta' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $totalCount,
                'total_pages' => ceil($totalCount / $perPage),
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
                'search' => $search
            ]
        ]);
    }

    /**
     * Get comments for a specific article
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/articles/{id}/comments",
     *     summary="Get comments for an article",
     *     description="Returns all comments for a specific article",
     *     operationId="adminGetArticleComments",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
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
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="id_artikel", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="komentar", type="string", example="Great article!"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Article not found")
     * )
     */
    public function getComments($id)
    {
        $artikel = Artikel::findOrFail($id);
        $comments = Komentar::where('id_artikel', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Update the status of a comment
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Put(
     *     path="/admin/comments/{id}/status",
     *     summary="Update comment status",
     *     description="Updates the status of a specific comment",
     *     operationId="adminUpdateCommentStatus",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Comment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"approved", "rejected", "pending"}, example="approved")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment status updated successfully"),
     *             @OA\Property(property="comment", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Comment not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateCommentStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $comment = Komentar::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment status updated successfully',
            'comment' => $comment
        ]);
    }

    /**
     * Delete a comment
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Delete(
     *     path="/admin/comments/{id}",
     *     summary="Delete a comment",
     *     description="Deletes a specific comment",
     *     operationId="adminDeleteComment",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Comment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Comment not found")
     * )
     */
    public function deleteComment($id)
    {
        $comment = Komentar::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }

    /**
     * Delete an article
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Delete(
     *     path="/admin/articles/{id}",
     *     summary="Delete an article",
     *     description="Deletes a specific article and all its associated images and comments",
     *     operationId="adminDeleteArticle",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Article not found")
     * )
     */
    public function deleteArticle($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Delete associated images first
        foreach ($artikel->images as $image) {
            // Delete the image file from storage
            if (Storage::exists('public/' . $image->gambar)) {
                Storage::delete('public/' . $image->gambar);
            }
            $image->delete();
        }

        // Delete associated comments
        foreach ($artikel->komentar as $comment) {
            $comment->delete();
        }

        // Delete the article
        $artikel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }

    /**
     * Store a newly created article in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/articles",
     *     summary="Create a new article",
     *     description="Creates a new article with images",
     *     operationId="adminCreateArticle",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nama_artikel", "deskripsi_artikel", "status", "tgl_rilis", "gambar"},
     *                 @OA\Property(property="nama_artikel", type="string", maxLength=120, example="New Article Title"),
     *                 @OA\Property(property="deskripsi_artikel", type="string", example="Article content with HTML formatting"),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}, example="published"),
     *                 @OA\Property(property="tgl_rilis", type="string", format="date", example="2023-12-31"),
     *                 @OA\Property(property="tags_artikel", type="string", maxLength=255, example="news,update"),
     *                 @OA\Property(property="gambar", type="array", 
     *                     @OA\Items(type="string", format="binary"),
     *                     description="Article images (max 3)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article created successfully"),
     *             @OA\Property(property="artikel", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'nama_artikel' => 'required|string|max:120',
            'deskripsi_artikel' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'tgl_rilis' => 'required|date',
            'tags_artikel' => 'nullable|string|max:255',
            'gambar' => 'required|array|min:1|max:3',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create the article
            $artikel = new Artikel();
            $artikel->status = $request->status;
            $artikel->id_user = auth()->user()->id_user;
            $artikel->nama_artikel = $request->nama_artikel;
            $artikel->deskripsi_artikel = $request->deskripsi_artikel;
            $artikel->tgl_rilis = $request->tgl_rilis;
            $artikel->tags_artikel = $request->tags_artikel;
            $artikel->save();

            // Handle image uploads
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $imageFile) {
                    $path = $imageFile->store('artikel_images', 'public');

                    $artikel->images()->create([
                        'gambar' => $path
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'artikel' => $artikel->load('images')
            ], 201);

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all article statuses.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/articles/statuses",
     *     summary="Get article statuses",
     *     description="Returns a list of all available article statuses",
     *     operationId="adminGetArticleStatuses",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id_status", type="string", example="draft"),
     *                 @OA\Property(property="nama_status", type="string", example="Draft")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function getStatuses()
    {
        // Return available article statuses as enum values
        $statuses = [
            ['id_status' => 'draft', 'nama_status' => 'Draft'],
            ['id_status' => 'published', 'nama_status' => 'Published'],
            ['id_status' => 'archived', 'nama_status' => 'Archived']
        ];
        
        return response()->json($statuses);
    }

    /**
     * Get article by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/articles/{id}",
     *     summary="Get article by ID",
     *     description="Returns a specific article by ID with its images",
     *     operationId="adminGetArticle",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="id_artikel", type="integer", example=1),
     *             @OA\Property(property="nama_artikel", type="string", example="Article Title"),
     *             @OA\Property(property="deskripsi_artikel", type="string", example="Article content..."),
     *             @OA\Property(property="status", type="string", example="published"),
     *             @OA\Property(property="tgl_rilis", type="string", format="date", example="2023-12-31"),
     *             @OA\Property(property="tags_artikel", type="string", example="news,update"),
     *             @OA\Property(property="images", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="gambar", type="string", example="artikel_images/image1.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Article not found")
     * )
     */
    public function getArticle($id)
    {
        $artikel = Artikel::with(['images'])->findOrFail($id);
        return response()->json($artikel);
    }

    /**
     * Update an existing article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/articles/{id}",
     *     summary="Update an article",
     *     description="Updates an existing article with images",
     *     operationId="adminUpdateArticle",
     *     tags={"Admin Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="HTTP method override",
     *         required=true,
     *         @OA\Schema(type="string", default="PUT")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"nama_artikel", "deskripsi_artikel", "status", "tgl_rilis"},
     *                 @OA\Property(property="nama_artikel", type="string", maxLength=120, example="Updated Article Title"),
     *                 @OA\Property(property="deskripsi_artikel", type="string", example="Updated article content"),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published", "archived"}, example="published"),
     *                 @OA\Property(property="tgl_rilis", type="string", format="date", example="2023-12-31"),
     *                 @OA\Property(property="tags_artikel", type="string", maxLength=255, example="news,update"),
     *                 @OA\Property(property="gambar[]", type="array", 
     *                     @OA\Items(type="string", format="binary"),
     *                     description="New article images to add"
     *                 ),
     *                 @OA\Property(property="delete_images[]", type="array", 
     *                     @OA\Items(type="integer"),
     *                     description="IDs of images to delete"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Article updated successfully"),
     *             @OA\Property(property="artikel", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateArticle(Request $request, $id)
    {
        // For file uploads, the frontend may use POST with _method=PUT
        if ($request->isMethod('post') && $request->has('_method') && $request->input('_method') === 'PUT') {
            // Convert to PUT request
            $request->offsetUnset('_method');
        }

        Log::info('Update Article Request', [
            'id' => $id,
            'method' => $request->method(),
            'has_files' => $request->hasFile('gambar'),
            'delete_images' => $request->has('delete_images') ? $request->input('delete_images') : 'none',
        ]);

        $request->validate([
            'nama_artikel' => 'required|string|max:120',
            'deskripsi_artikel' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'tgl_rilis' => 'required|date',
            'tags_artikel' => 'nullable|string|max:255',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'nullable|integer|exists:artikel_images,id'
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Get article
            $artikel = Artikel::findOrFail($id);

            // Update article data
            $artikel->status = $request->status;
            $artikel->nama_artikel = $request->nama_artikel;
            $artikel->deskripsi_artikel = $request->deskripsi_artikel;
            $artikel->tgl_rilis = $request->tgl_rilis;
            $artikel->tags_artikel = $request->tags_artikel;
            $artikel->save();

            // Handle image deletions if specified
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $imageId) {
                    $image = ArtikelImage::find($imageId);
                    if ($image && $image->id_artikel == $artikel->id_artikel) {
                        // Delete the image file from storage
                        if (Storage::exists('public/' . $image->gambar)) {
                            Storage::delete('public/' . $image->gambar);
                        }
                        $image->delete();
                    }
                }
            }

            // Handle new image uploads
            if ($request->hasFile('gambar')) {
                // Check if the total number of images would exceed 3
                $currentImagesCount = $artikel->images()->count();
                $newImagesCount = count($request->file('gambar'));

                Log::info('Image upload info', [
                    'current_count' => $currentImagesCount,
                    'new_count' => $newImagesCount,
                    'total' => $currentImagesCount + $newImagesCount
                ]);

                if ($currentImagesCount + $newImagesCount > 3) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Artikel tidak dapat memiliki lebih dari 3 gambar',
                        'errors' => ['gambar' => ['Maksimal 3 gambar diperbolehkan']]
                    ], 422);
                }

                foreach ($request->file('gambar') as $imageFile) {
                    $path = $imageFile->store('artikel_images', 'public');

                    $artikel->images()->create([
                        'gambar' => $path
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully',
                'artikel' => $artikel->load('images')
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            Log::error('Article update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
