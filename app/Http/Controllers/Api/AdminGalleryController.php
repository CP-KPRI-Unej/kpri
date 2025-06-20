<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Gallery",
 *     description="API Endpoints for Gallery management"
 * )
 */
class AdminGalleryController extends Controller
{
    /**
     * Display a listing of the gallery items.
     *
     * @OA\Get(
     *     path="/admin/gallery",
     *     summary="Get all gallery items",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id_galeri", type="integer", example=1),
     *                     @OA\Property(property="nama_galeri", type="string", example="Gallery Name"),
     *                     @OA\Property(property="gambar_galeri", type="string", example="gallery/image.jpg"),
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="tgl_upload", type="string", format="date", example="2023-01-01"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id_user", type="integer", example=1),
     *                         @OA\Property(property="nama_user", type="string", example="Admin User")
     *                     ),
     *                     @OA\Property(property="status", type="object",
     *                         @OA\Property(property="id_status", type="integer", example=1),
     *                         @OA\Property(property="nama_status", type="string", example="Active")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Gallery items retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve gallery items")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $galleries = Gallery::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->orderBy('id_galeri', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $galleries,
                'message' => 'Gallery items retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve gallery items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created gallery item in storage.
     *
     * @OA\Post(
     *     path="/admin/gallery",
     *     summary="Create a new gallery item",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nama_galeri", type="string", example="New Gallery", description="Gallery name"),
     *                 @OA\Property(property="id_status", type="integer", example=1, description="Status ID"),
     *                 @OA\Property(property="gambar_galeri", type="string", format="binary", description="Gallery image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_galeri", type="integer", example=1),
     *                 @OA\Property(property="nama_galeri", type="string", example="New Gallery"),
     *                 @OA\Property(property="gambar_galeri", type="string", example="gallery/image.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="tgl_upload", type="string", format="date", example="2023-01-01")
     *             ),
     *             @OA\Property(property="message", type="string", example="Gallery item created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to create gallery item")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_galeri' => 'required|string|max:30',
            'id_status' => 'required|exists:status,id_status',
            'gambar_galeri' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload image
            $image = $request->file('gambar_galeri');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/gallery', $imageName);
            
            // Create gallery item
            $gallery = new Gallery();
            $gallery->nama_galeri = $request->nama_galeri;
            $gallery->id_status = $request->id_status;
            $gallery->id_user = Auth::id();
            $gallery->gambar_galeri = str_replace('public/', '', $imagePath);
            $gallery->tgl_upload = now()->toDateString();
            $gallery->save();

            return response()->json([
                'status' => 'success',
                'data' => $gallery,
                'message' => 'Gallery item created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create gallery item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified gallery item.
     *
     * @OA\Get(
     *     path="/admin/gallery/{id}",
     *     summary="Get gallery item by ID",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Gallery ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_galeri", type="integer", example=1),
     *                 @OA\Property(property="nama_galeri", type="string", example="Gallery Name"),
     *                 @OA\Property(property="gambar_galeri", type="string", example="gallery/image.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="tgl_upload", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="nama_user", type="string", example="Admin User")
     *                 ),
     *                 @OA\Property(property="status", type="object",
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="nama_status", type="string", example="Active")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Gallery item retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Gallery item not found")
     *         )
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $gallery = Gallery::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $gallery,
                'message' => 'Gallery item retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery item not found'
            ], 404);
        }
    }

    /**
     * Update the specified gallery item in storage.
     * 
     * @OA\Post(
     *     path="/admin/gallery/{id}",
     *     summary="Update gallery item",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Gallery ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Method spoofing"),
     *                 @OA\Property(property="nama_galeri", type="string", example="Updated Gallery", description="Gallery name"),
     *                 @OA\Property(property="id_status", type="integer", example=1, description="Status ID"),
     *                 @OA\Property(property="gambar_galeri", type="string", format="binary", description="Gallery image (optional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_galeri", type="integer", example=1),
     *                 @OA\Property(property="nama_galeri", type="string", example="Updated Gallery"),
     *                 @OA\Property(property="gambar_galeri", type="string", example="gallery/image.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="tgl_upload", type="string", format="date", example="2023-01-01")
     *             ),
     *             @OA\Property(property="message", type="string", example="Gallery item updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Gallery item not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update gallery item")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_galeri' => 'required|string|max:30',
            'id_status' => 'required|exists:status,id_status',
            'gambar_galeri' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gallery = Gallery::findOrFail($id);

            // Update image if provided
            if ($request->hasFile('gambar_galeri')) {
                // Delete old image
                if ($gallery->gambar_galeri) {
                    Storage::delete('public/' . $gallery->gambar_galeri);
                }

                // Upload new image
                $image = $request->file('gambar_galeri');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('public/gallery', $imageName);
                $gallery->gambar_galeri = str_replace('public/', '', $imagePath);
            }

            // Update other fields
            $gallery->nama_galeri = $request->nama_galeri;
            $gallery->id_status = $request->id_status;
            $gallery->save();

            return response()->json([
                'status' => 'success',
                'data' => $gallery,
                'message' => 'Gallery item updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update gallery item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified gallery item from storage.
     * 
     * @OA\Delete(
     *     path="/admin/gallery/{id}",
     *     summary="Delete gallery item",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Gallery ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Gallery item deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Gallery item not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to delete gallery item")
     *         )
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            
            // Delete image file
            if ($gallery->gambar_galeri) {
                Storage::delete('public/' . $gallery->gambar_galeri);
            }
            
            // Delete gallery record
            $gallery->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Gallery item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete gallery item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statuses for dropdown.
     * 
     * @OA\Get(
     *     path="/admin/gallery/statuses",
     *     summary="Get all status options for gallery",
     *     tags={"Admin Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="nama_status", type="string", example="Active")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Statuses retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve statuses")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatuses()
    {
        try {
            $statuses = \App\Models\Status::all(['id_status', 'nama_status']);
            
            return response()->json([
                'status' => 'success',
                'data' => $statuses,
                'message' => 'Statuses retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve statuses: ' . $e->getMessage()
            ], 500);
        }
    }
} 
 
 
 
 
 
 
 
 
 
 
 