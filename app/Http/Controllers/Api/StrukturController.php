<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturKepengurusan;
use App\Models\Jabatan;
use App\Models\PeriodeKepengurusan;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/struktur",
     *     summary="Get organization structure",
     *     description="Returns the organization structure grouped by position for a specific period or active period",
     *     operationId="getOrganizationStructure",
     *     tags={"Organization Structure"},
     *     @OA\Parameter(
     *         name="id_periode",
     *         in="query",
     *         description="Period ID (optional, defaults to active period)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="periode",
     *                 type="object",
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028"),
     *                 @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31")
     *             ),
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
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request)
    {
        // Get periode_id from request or use the active one
        $periodeId = $request->input('id_periode');
        
        if (!$periodeId) {
            // Get active periode if not specified
            $activePeriode = PeriodeKepengurusan::where('status', 'aktif')->first();
            $periodeId = $activePeriode ? $activePeriode->id_periode : null;
        }
        
        // Get all pengurus with their respective jabatan and periode
        $query = StrukturKepengurusan::with(['jabatan', 'periode'])
            ->join('jabatan', 'struktur_kepengurusan.id_jabatan', '=', 'jabatan.id_jabatan');
            
        // Filter by periode if provided
        if ($periodeId) {
            $query->where('id_periode', $periodeId);
        }
        
        $struktur = $query->orderBy('jabatan.nama_jabatan')
            ->orderBy('struktur_kepengurusan.nama_pengurus')
            ->get([
                'struktur_kepengurusan.id_pengurus',
                'struktur_kepengurusan.nama_pengurus',
                'struktur_kepengurusan.id_periode',
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

        // Get active periode info for response
        $periodeInfo = null;
        if ($periodeId) {
            $periodeInfo = PeriodeKepengurusan::find($periodeId);
        }

        return response()->json([
            'success' => true,
            'periode' => $periodeInfo,
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
     *                 ),
     *                 @OA\Property(
     *                     property="periode",
     *                     type="object",
     *                     @OA\Property(property="id_periode", type="integer", example=1),
     *                     @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028")
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
        $pengurus = StrukturKepengurusan::with(['jabatan', 'periode'])
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
                ],
                'periode' => [
                    'id_periode' => $pengurus->periode->id_periode,
                    'nama_periode' => $pengurus->periode->nama_periode,
                    'tanggal_mulai' => $pengurus->periode->tanggal_mulai,
                    'tanggal_selesai' => $pengurus->periode->tanggal_selesai
                ]
            ]
        ]);
    }

    /**
     * Get all available periods.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/struktur-periode",
     *     summary="Get organization periods",
     *     description="Returns all organization periods",
     *     operationId="getOrganizationPeriods",
     *     tags={"Organization Structure"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_periode", type="integer", example=1),
     *                     @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028"),
     *                     @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                     @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31"),
     *                     @OA\Property(property="status", type="string", example="aktif")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getPeriode()
    {
        $periode = PeriodeKepengurusan::orderBy('tanggal_mulai', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $periode
        ]);
    }
} 