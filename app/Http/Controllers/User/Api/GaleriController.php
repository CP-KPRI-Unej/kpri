<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Gallery",
 *     description="API Endpoints for gallery photos"
 * )
 */
class GaleriController extends Controller
{
    /**
     * Display a listing of gallery photos.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/gallery",
     *     tags={"Gallery"},
     *     summary="Get list of gallery photos",
     *     description="Returns list of all published gallery photos",
     *     operationId="getGalleryList",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="Rapat Anggota Tahunan"),
     *                     @OA\Property(property="gambar", type="string", example="storage/galeri/rapat-anggota.jpg"),
     *                     @OA\Property(property="tgl_upload", type="string", format="date", example="2023-05-20")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        // Get only published/active gallery photos
        // Assuming status ID 1 is for published/active items
        $galeri = GaleriFoto::where('id_status', 1)
            ->orderBy('tgl_upload', 'desc')
            ->get();
        
        $data = $galeri->map(function ($foto) {
            return [
                'id' => $foto->id_galeri,
                'nama' => $foto->nama_galeri,
                'gambar' => asset('storage/' . $foto->gambar_galeri),
                'tgl_upload' => $foto->tgl_upload->format('Y-m-d')
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Display the specified gallery photo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/gallery/{id}",
     *     tags={"Gallery"},
     *     summary="Get specific gallery photo details",
     *     description="Returns detailed information about a specific gallery photo",
     *     operationId="getGalleryDetail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Gallery Photo ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="Rapat Anggota Tahunan"),
     *                 @OA\Property(property="gambar", type="string", example="storage/galeri/rapat-anggota.jpg"),
     *                 @OA\Property(property="tgl_upload", type="string", format="date", example="2023-05-20")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Gallery photo not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Foto galeri tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        // Get only published/active gallery photos
        $foto = GaleriFoto::where('id_status', 1)
            ->where('id_galeri', $id)
            ->first();
        
        if (!$foto) {
            return response()->json([
                'success' => false,
                'message' => 'Foto galeri tidak ditemukan'
            ], 404);
        }
        
        $data = [
            'id' => $foto->id_galeri,
            'nama' => $foto->nama_galeri,
            'gambar' => asset('storage/' . $foto->gambar_galeri),
            'tgl_upload' => $foto->tgl_upload->format('Y-m-d')
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
} 