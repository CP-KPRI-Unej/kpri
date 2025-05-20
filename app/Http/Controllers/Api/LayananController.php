<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Services",
 *     description="API Endpoints for services"
 * )
 */
class LayananController extends Controller
{
    /**
     * Display a listing of service types.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/service-types",
     *     tags={"Services"},
     *     summary="Get list of service types",
     *     description="Returns list of all service types",
     *     operationId="getServiceTypesList",
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
     *                     @OA\Property(property="nama", type="string", example="Simpan Pinjam"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getJenisLayanan()
    {
        $jenisLayanan = JenisLayanan::all();
        
        $data = $jenisLayanan->map(function ($jenis) {
            return [
                'id' => $jenis->id_jenis_layanan,
                'nama' => $jenis->nama_layanan,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Display the specified service type with its services.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/service-types/{id}",
     *     tags={"Services"},
     *     summary="Get specific service type with its services",
     *     description="Returns detailed information about a specific service type and all its services",
     *     operationId="getServiceTypeDetail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service Type ID",
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
     *                 @OA\Property(property="nama", type="string", example="Simpan Pinjam"),
     *                 @OA\Property(
     *                     property="layanan",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="judul", type="string", example="Pinjaman Reguler"),
     *                         @OA\Property(property="deskripsi", type="string", example="Layanan pinjaman dengan bunga rendah untuk anggota koperasi")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service type not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Jenis layanan tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function getJenisLayananById($id)
    {
        $jenisLayanan = JenisLayanan::with('layanans')->find($id);
        
        if (!$jenisLayanan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis layanan tidak ditemukan'
            ], 404);
        }
        
        $layananData = $jenisLayanan->layanans->map(function ($layanan) {
            return [
                'id' => $layanan->id_layanan,
                'judul' => $layanan->judul_layanan,
                'deskripsi' => $layanan->deskripsi_layanan
            ];
        });
        
        $data = [
            'id' => $jenisLayanan->id_jenis_layanan,
            'nama' => $jenisLayanan->nama_layanan,
            'layanan' => $layananData
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/services/{id}",
     *     tags={"Services"},
     *     summary="Get specific service details",
     *     description="Returns detailed information about a specific service",
     *     operationId="getServiceDetail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
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
     *                 @OA\Property(property="judul", type="string", example="Pinjaman Reguler"),
     *                 @OA\Property(property="deskripsi", type="string", example="Layanan pinjaman dengan bunga rendah untuk anggota koperasi"),
     *                 @OA\Property(
     *                     property="jenis_layanan",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="Simpan Pinjam")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Layanan tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function getLayananById($id)
    {
        $layanan = Layanan::with('jenisLayanan')->find($id);
        
        if (!$layanan) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan'
            ], 404);
        }
        
        $data = [
            'id' => $layanan->id_layanan,
            'judul' => $layanan->judul_layanan,
            'deskripsi' => $layanan->deskripsi_layanan,
            'jenis_layanan' => [
                'id' => $layanan->jenisLayanan->id_jenis_layanan,
                'nama' => $layanan->jenisLayanan->nama_layanan
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
} 