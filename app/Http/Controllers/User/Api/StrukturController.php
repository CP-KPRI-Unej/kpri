<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturKepengurusan;
use App\Models\Jabatan;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Organization Structure",
 *     description="API Endpoints for Organization Structure"
 * )
 */
class StrukturController extends Controller
{
    /**
     * Display a listing of the organization structure.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/struktur",
     *     summary="Get organization structure",
     *     description="Returns the organization structure grouped by position",
     *     operationId="getOrganizationStructure",
     *     tags={"Organization Structure"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id_pengurus", type="integer", example=1),
     *                         @OA\Property(property="nama_pengurus", type="string", example="John Doe")
     *                     )
     *                 ),
     *                 example={
     *                     "Ketua": {
     *                         {"id_pengurus": 1, "nama_pengurus": "John Doe"}
     *                     },
     *                     "Sekretaris": {
     *                         {"id_pengurus": 2, "nama_pengurus": "Jane Smith"}
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        // Get all pengurus with their respective jabatan
        $struktur = StrukturKepengurusan::with('jabatan')
            ->join('jabatan', 'struktur_kepengurusan.id_jabatan', '=', 'jabatan.id_jabatan')
            ->orderBy('jabatan.nama_jabatan')
            ->orderBy('struktur_kepengurusan.nama_pengurus')
            ->get([
                'struktur_kepengurusan.id_pengurus',
                'struktur_kepengurusan.nama_pengurus',
                'jabatan.id_jabatan',
                'jabatan.nama_jabatan'
            ]);

        // Group by jabatan
        $strukturByJabatan = $struktur->groupBy('nama_jabatan')
            ->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'id_pengurus' => $item->id_pengurus,
                        'nama_pengurus' => $item->nama_pengurus
                    ];
                });
            });

        return response()->json([
            'success' => true,
            'data' => $strukturByJabatan
        ]);
    }

    /**
     * Display the specified organization structure member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/struktur/{id}",
     *     summary="Get specific member details",
     *     description="Returns details of a specific organization member",
     *     operationId="getOrganizationMember",
     *     tags={"Organization Structure"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Member ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_pengurus", type="integer", example=1),
     *                 @OA\Property(property="nama_pengurus", type="string", example="John Doe"),
     *                 @OA\Property(
     *                     property="jabatan",
     *                     type="object",
     *                     @OA\Property(property="id_jabatan", type="integer", example=1),
     *                     @OA\Property(property="nama_jabatan", type="string", example="Ketua")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Member not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        $pengurus = StrukturKepengurusan::with('jabatan')
            ->where('id_pengurus', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id_pengurus' => $pengurus->id_pengurus,
                'nama_pengurus' => $pengurus->nama_pengurus,
                'jabatan' => [
                    'id_jabatan' => $pengurus->jabatan->id_jabatan,
                    'nama_jabatan' => $pengurus->jabatan->nama_jabatan
                ]
            ]
        ]);
    }
} 