<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBeranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminHeroBerandaController extends Controller
{
    /**
     * Display a listing of hero banners.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $heroes = HeroBeranda::with('user:id_user,nama_user')
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $hero = HeroBeranda::with('user:id_user,nama_user')
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