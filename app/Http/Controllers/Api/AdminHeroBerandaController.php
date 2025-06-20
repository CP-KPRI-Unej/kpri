<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBeranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Hero",
 *     description="API Endpoints for Hero Banner management"
 * )
 */
class AdminHeroBerandaController extends Controller
{
    /**
     * Display a listing of hero banners.
     *
     * @OA\Get(
     *     path="/admin/hero",
     *     summary="Get all hero banners",
     *     tags={"Admin Hero"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="id_hero", type="integer", example=1),
     *                     @OA\Property(property="judul", type="string", example="Welcome to KPRI"),
     *                     @OA\Property(property="deskripsi", type="string", example="Koperasi Pegawai Republik Indonesia"),
     *                     @OA\Property(property="url", type="string", example="https://kpri.com/about"),
     *                     @OA\Property(property="gambar", type="string", example="hero-banners/banner.jpg"),
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="id_user", type="integer", example=1),
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
     *             @OA\Property(property="message", type="string", example="Hero banners retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve hero banners")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $heroes = HeroBeranda::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->orderBy('id_hero', 'desc')
                ->get();

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
     * Store a newly created hero banner in storage.
     *
     * @OA\Post(
     *     path="/admin/hero",
     *     summary="Create a new hero banner",
     *     tags={"Admin Hero"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="judul", type="string", example="Welcome to KPRI", description="Banner title"),
     *                 @OA\Property(property="deskripsi", type="string", example="Koperasi Pegawai Republik Indonesia", description="Banner description"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/about", description="Button URL"),
     *                 @OA\Property(property="gambar", type="string", format="binary", description="Banner image"),
     *                 @OA\Property(property="id_status", type="integer", example=1, description="Status ID")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_hero", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="Welcome to KPRI"),
     *                 @OA\Property(property="deskripsi", type="string", example="Koperasi Pegawai Republik Indonesia"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/about"),
     *                 @OA\Property(property="gambar", type="string", example="hero-banners/banner.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1)
     *             ),
     *             @OA\Property(property="message", type="string", example="Hero banner created successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to create hero banner")
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
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
            'url' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_status' => 'required|exists:status,id_status',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle image upload
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/hero-banners', $imageName);

            $hero = new HeroBeranda();
            $hero->id_user = Auth::id();
            $hero->judul = $request->judul;
            $hero->deskripsi = $request->deskripsi;
            $hero->url = $request->url;
            $hero->gambar = 'hero-banners/' . $imageName;
            $hero->id_status = $request->id_status;
            $hero->save();

            return response()->json([
                'status' => 'success',
                'data' => $hero,
                'message' => 'Hero banner created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create hero banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified hero banner.
     *
     * @OA\Get(
     *     path="/admin/hero/{id}",
     *     summary="Get hero banner by ID",
     *     tags={"Admin Hero"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hero Banner ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_hero", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="Welcome to KPRI"),
     *                 @OA\Property(property="deskripsi", type="string", example="Koperasi Pegawai Republik Indonesia"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/about"),
     *                 @OA\Property(property="gambar", type="string", example="hero-banners/banner.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="nama_user", type="string", example="Admin User")
     *                 ),
     *                 @OA\Property(property="status", type="object",
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="nama_status", type="string", example="Active")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Hero banner retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Hero banner not found")
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
            $hero = HeroBeranda::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->findOrFail($id);

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

    /**
     * Update the specified hero banner in storage.
     *
     * @OA\Post(
     *     path="/admin/hero/{id}",
     *     summary="Update hero banner",
     *     tags={"Admin Hero"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hero Banner ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Method spoofing"),
     *                 @OA\Property(property="judul", type="string", example="Updated KPRI Banner", description="Banner title"),
     *                 @OA\Property(property="deskripsi", type="string", example="Updated description", description="Banner description"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/services", description="Button URL"),
     *                 @OA\Property(property="gambar", type="string", format="binary", description="Banner image (optional)"),
     *                 @OA\Property(property="id_status", type="integer", example=1, description="Status ID")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_hero", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="Updated KPRI Banner"),
     *                 @OA\Property(property="deskripsi", type="string", example="Updated description"),
     *                 @OA\Property(property="url", type="string", example="https://kpri.com/services"),
     *                 @OA\Property(property="gambar", type="string", example="hero-banners/banner.jpg"),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1)
     *             ),
     *             @OA\Property(property="message", type="string", example="Hero banner updated successfully")
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
     *             @OA\Property(property="message", type="string", example="Hero banner not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to update hero banner")
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
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
            'url' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_status' => 'required|exists:status,id_status',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $hero = HeroBeranda::findOrFail($id);
            
            // Update text fields
            $hero->judul = $request->judul;
            $hero->deskripsi = $request->deskripsi;
            $hero->url = $request->url;
            $hero->id_status = $request->id_status;
            
            // Handle image update if a new image is provided
            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($hero->gambar) {
                    Storage::delete('public/' . $hero->gambar);
                }
                
                // Upload new image
                $image = $request->file('gambar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/hero-banners', $imageName);
                $hero->gambar = 'hero-banners/' . $imageName;
            }
            
            $hero->save();

            return response()->json([
                'status' => 'success',
                'data' => $hero,
                'message' => 'Hero banner updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update hero banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified hero banner from storage.
     *
     * @OA\Delete(
     *     path="/admin/hero/{id}",
     *     summary="Delete hero banner",
     *     tags={"Admin Hero"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Hero Banner ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Hero banner deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Hero banner not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to delete hero banner")
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
            $hero = HeroBeranda::findOrFail($id);
            
            // Delete associated image
            if ($hero->gambar) {
                Storage::delete('public/' . $hero->gambar);
            }
            
            $hero->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Hero banner deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete hero banner: ' . $e->getMessage()
            ], 500);
        }
    }
} 