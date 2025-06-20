<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturKepengurusan;
use App\Models\Jabatan;
use App\Models\PeriodeKepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Struktur",
 *     description="API Endpoints for managing organization structure and leadership periods"
 * )
 */
class AdminStrukturController extends Controller
{
    /**
     * Display a listing of the struktur kepengurusan.
     *
     * @OA\Get(
     *     path="/admin/struktur",
     *     summary="Get organization structure by period",
     *     description="Retrieves all organization members grouped by position for a specific period",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id_periode",
     *         in="query",
     *         description="Period ID to filter by (optional, defaults to active period)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="periode",
     *                 type="object",
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028"),
     *                 @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31"),
     *                 @OA\Property(property="status", type="string", example="aktif")
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id_pengurus", type="integer", example=1),
     *                         @OA\Property(property="id_jabatan", type="integer", example=1),
     *                         @OA\Property(property="id_periode", type="integer", example=1),
     *                         @OA\Property(property="nama_pengurus", type="string", example="John Doe")
     *                     )
     *                 ),
     *                 example={
     *                     "Ketua": {
     *                         {"id_pengurus": 1, "id_jabatan": 1, "id_periode": 1, "nama_pengurus": "John Doe"}
     *                     },
     *                     "Sekretaris": {
     *                         {"id_pengurus": 2, "id_jabatan": 2, "id_periode": 1, "nama_pengurus": "Jane Smith"}
     *                     }
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
        
        // Get all jabatan
        $jabatan = Jabatan::all();

        // Get all pengurus with their respective jabatan
        $strukturByJabatan = [];

        foreach ($jabatan as $jab) {
            $query = StrukturKepengurusan::where('id_jabatan', $jab->id_jabatan);
            
            // Filter by periode if provided
            if ($periodeId) {
                $query->where('id_periode', $periodeId);
            }
            
            $pengurus = $query->with('periode')
                ->orderBy('nama_pengurus')
                ->get();

            $strukturByJabatan[$jab->nama_jabatan] = $pengurus;
        }

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
     * Get all available jabatan.
     *
     * @OA\Get(
     *     path="/admin/struktur/jabatan",
     *     summary="Get all positions",
     *     description="Retrieves all available positions in the organization structure",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_jabatan", type="integer", example=1),
     *                     @OA\Property(property="nama_jabatan", type="string", example="Ketua")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJabatan()
    {
        $jabatan = Jabatan::all();

        return response()->json([
            'success' => true,
            'data' => $jabatan
        ]);
    }

    /**
     * Get all available periode.
     *
     * @OA\Get(
     *     path="/admin/struktur/periode",
     *     summary="Get all leadership periods",
     *     description="Retrieves all leadership periods ordered by start date descending",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
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
     *                     @OA\Property(property="status", type="string", example="aktif"),
     *                     @OA\Property(property="keterangan", type="string", example="Periode kepengurusan terbaru", nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPeriode()
    {
        $periode = PeriodeKepengurusan::orderBy('tanggal_mulai', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $periode
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/admin/struktur/{id}",
     *     summary="Get organization member details",
     *     description="Retrieves details of a specific organization member",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Organization member ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_pengurus", type="integer", example=1),
     *                 @OA\Property(property="id_jabatan", type="integer", example=1),
     *                 @OA\Property(property="id_periode", type="integer", example=1),
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
     *                     @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028"),
     *                     @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                     @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31"),
     *                     @OA\Property(property="status", type="string", example="aktif")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $pengurus = StrukturKepengurusan::with(['jabatan', 'periode'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pengurus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/admin/struktur",
     *     summary="Create new organization member",
     *     description="Creates a new member in the organization structure",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_jabatan", "id_periode", "nama_pengurus"},
     *             @OA\Property(property="id_jabatan", type="integer", example=1, description="Position ID"),
     *             @OA\Property(property="id_periode", type="integer", example=1, description="Period ID"),
     *             @OA\Property(property="nama_pengurus", type="string", example="John Doe", description="Member name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengurus berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_pengurus", type="integer", example=1),
     *                 @OA\Property(property="id_jabatan", type="integer", example=1),
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_pengurus", type="string", example="John Doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id_jabatan",
     *                     type="array",
     *                     @OA\Items(type="string", example="The id jabatan field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'id_periode' => 'required|exists:periode_kepengurusan,id_periode',
            'nama_pengurus' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create new pengurus
        $pengurus = StrukturKepengurusan::create([
            'id_jabatan' => $request->id_jabatan,
            'id_periode' => $request->id_periode,
            'nama_pengurus' => $request->nama_pengurus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengurus berhasil ditambahkan',
            'data' => $pengurus
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/admin/struktur/{id}",
     *     summary="Update organization member",
     *     description="Updates an existing member in the organization structure",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Organization member ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_jabatan", "id_periode", "nama_pengurus"},
     *             @OA\Property(property="id_jabatan", type="integer", example=1, description="Position ID"),
     *             @OA\Property(property="id_periode", type="integer", example=1, description="Period ID"),
     *             @OA\Property(property="nama_pengurus", type="string", example="John Doe Updated", description="Member name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengurus berhasil diperbarui"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_pengurus", type="integer", example=1),
     *                 @OA\Property(property="id_jabatan", type="integer", example=1),
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_pengurus", type="string", example="John Doe Updated")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id_jabatan",
     *                     type="array",
     *                     @OA\Items(type="string", example="The id jabatan field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
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
        // Find the pengurus
        $pengurus = StrukturKepengurusan::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'id_periode' => 'required|exists:periode_kepengurusan,id_periode',
            'nama_pengurus' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update pengurus
        $pengurus->update([
            'id_jabatan' => $request->id_jabatan,
            'id_periode' => $request->id_periode,
            'nama_pengurus' => $request->nama_pengurus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengurus berhasil diperbarui',
            'data' => $pengurus
        ]);
    }

    /**
     * Store a new periode kepengurusan.
     *
     * @OA\Post(
     *     path="/admin/struktur/periode",
     *     summary="Create new leadership period",
     *     description="Creates a new leadership period and optionally sets it as active",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_periode", "tanggal_mulai", "tanggal_selesai", "status"},
     *             @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028", description="Period name"),
     *             @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01", description="Start date"),
     *             @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31", description="End date"),
     *             @OA\Property(property="status", type="string", example="aktif", description="Status (aktif/nonaktif)"),
     *             @OA\Property(property="keterangan", type="string", example="Periode kepengurusan terbaru", description="Additional notes", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data periode berhasil ditambahkan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028"),
     *                 @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31"),
     *                 @OA\Property(property="status", type="string", example="aktif"),
     *                 @OA\Property(property="keterangan", type="string", example="Periode kepengurusan terbaru", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="nama_periode",
     *                     type="array",
     *                     @OA\Items(type="string", example="The nama periode field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePeriode(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // If setting a periode as active, deactivate all other periodes
        if ($request->status === 'aktif') {
            PeriodeKepengurusan::where('status', 'aktif')
                ->update(['status' => 'nonaktif']);
        }

        // Create new periode
        $periode = PeriodeKepengurusan::create([
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data periode berhasil ditambahkan',
            'data' => $periode
        ], 201);
    }

    /**
     * Update a periode kepengurusan.
     *
     * @OA\Put(
     *     path="/admin/struktur/periode/{id}",
     *     summary="Update leadership period",
     *     description="Updates an existing leadership period",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Period ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_periode", "tanggal_mulai", "tanggal_selesai", "status"},
     *             @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028 Updated", description="Period name"),
     *             @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01", description="Start date"),
     *             @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31", description="End date"),
     *             @OA\Property(property="status", type="string", example="aktif", description="Status (aktif/nonaktif)"),
     *             @OA\Property(property="keterangan", type="string", example="Periode kepengurusan terbaru", description="Additional notes", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data periode berhasil diperbarui"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_periode", type="integer", example=1),
     *                 @OA\Property(property="nama_periode", type="string", example="Periode 2023-2028 Updated"),
     *                 @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tanggal_selesai", type="string", format="date", example="2028-12-31"),
     *                 @OA\Property(property="status", type="string", example="aktif"),
     *                 @OA\Property(property="keterangan", type="string", example="Periode kepengurusan terbaru", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="nama_periode",
     *                     type="array",
     *                     @OA\Items(type="string", example="The nama periode field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePeriode(Request $request, $id)
    {
        // Find the periode
        $periode = PeriodeKepengurusan::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // If setting a periode as active, deactivate all other periodes
        if ($request->status === 'aktif' && $periode->status !== 'aktif') {
            PeriodeKepengurusan::where('status', 'aktif')
                ->update(['status' => 'nonaktif']);
        }

        // Update periode
        $periode->update([
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data periode berhasil diperbarui',
            'data' => $periode
        ]);
    }

    /**
     * Delete a periode kepengurusan.
     *
     * @OA\Delete(
     *     path="/admin/struktur/periode/{id}",
     *     summary="Delete leadership period",
     *     description="Deletes a leadership period if it has no associated structure members",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Period ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data periode berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Periode tidak dapat dihapus karena masih memiliki data struktur kepengurusan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPeriode($id)
    {
        // Find the periode
        $periode = PeriodeKepengurusan::findOrFail($id);

        // Check if periode has struktur pengurus associated with it
        $hasStructure = StrukturKepengurusan::where('id_periode', $id)->exists();
        if ($hasStructure) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak dapat dihapus karena masih memiliki data struktur kepengurusan'
            ], 422);
        }

        // Delete the periode
        $periode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data periode berhasil dihapus'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/admin/struktur/{id}",
     *     summary="Delete organization member",
     *     description="Deletes an organization member from the structure",
     *     tags={"Admin Struktur"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Organization member ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengurus berhasil dihapus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the pengurus
        $pengurus = StrukturKepengurusan::findOrFail($id);

        // Delete the pengurus
        $pengurus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pengurus berhasil dihapus'
        ]);
    }
}
