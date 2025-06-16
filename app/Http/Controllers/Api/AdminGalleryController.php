<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminGalleryController extends Controller
{
    /**
     * Display a listing of the gallery items.
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
 
 
 
 
 
 
 
 
 
 
 