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

class ArtikelController extends Controller
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $artikels = Artikel::with(['status', 'komentar'])
            ->orderBy('tgl_rilis', 'desc')
            ->get();

        // Transform data to include counts
        $transformedArtikels = $artikels->map(function ($artikel) {
            $pendingComments = $artikel->komentar->where('status', 'pending')->count();

            return [
                'id_artikel' => $artikel->id_artikel,
                'nama_artikel' => $artikel->nama_artikel,
                'deskripsi_artikel' => $artikel->deskripsi_artikel,
                'id_status' => $artikel->id_status,
                'status' => [
                    'id_status' => $artikel->status->id_status,
                    'nama_status' => $artikel->status->nama_status
                ],
                'tgl_rilis' => $artikel->tgl_rilis,
                'tags_artikel' => $artikel->tags_artikel,
                'total_comments' => $artikel->komentar->count(),
                'pending_comments' => $pendingComments,
                'has_pending_comments' => $pendingComments > 0
            ];
        });

        return response()->json($transformedArtikels);
    }

    /**
     * Get comments for a specific article
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
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
     */
    public function updateCommentStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
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
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'nama_artikel' => 'required|string|max:120',
            'deskripsi_artikel' => 'required|string',
            'id_status' => 'required|exists:status,id_status',
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
            $artikel->id_status = $request->id_status;
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
                'artikel' => $artikel->load('images', 'status')
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
     */
    public function getStatuses()
    {
        $statuses = \App\Models\Status::all();
        return response()->json($statuses);
    }

    /**
     * Get article by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticle($id)
    {
        $artikel = Artikel::with(['status', 'images'])->findOrFail($id);
        return response()->json($artikel);
    }

    /**
     * Update an existing article.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
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
            'id_status' => 'required|exists:status,id_status',
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
            $artikel->id_status = $request->id_status;
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
                'artikel' => $artikel->load('images', 'status')
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
