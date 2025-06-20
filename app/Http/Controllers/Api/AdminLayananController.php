<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Admin Services",
 *     description="API Endpoints for Admin Service Management"
 * )
 */
class AdminLayananController extends Controller
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
     * Display a listing of services by service type.
     *
     * @param  int  $jenisLayananId
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/layanan/{jenisLayananId?}",
     *     summary="Get all services",
     *     description="Returns a list of all services, optionally filtered by service type",
     *     operationId="adminGetLayanan",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="jenisLayananId",
     *         in="path",
     *         description="Service type ID (optional)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_layanan", type="integer", example=1),
     *                     @OA\Property(property="id_jenis_layanan", type="integer", example=1),
     *                     @OA\Property(property="judul_layanan", type="string", example="Pinjaman Anggota"),
     *                     @OA\Property(property="deskripsi_layanan", type="string", example="Layanan pinjaman untuk anggota koperasi"),
     *                     @OA\Property(property="gambar", type="string", example="layanan/layanan_1234567890.jpg"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="jenis_layanan", type="object",
     *                         @OA\Property(property="id_jenis_layanan", type="integer", example=1),
     *                         @OA\Property(property="nama_jenis", type="string", example="Pinjaman"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index($jenisLayananId = null)
    {
        try {
            $query = Layanan::with('jenisLayanan');
            
            if ($jenisLayananId) {
                $query->where('id_jenis_layanan', $jenisLayananId);
            }
            
            $layanan = $query->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $layanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching layanan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *     path="/admin/layanan",
     *     summary="Create a new service",
     *     description="Creates a new service with optional image upload",
     *     operationId="adminCreateLayanan",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"id_jenis_layanan", "judul_layanan"},
     *                 @OA\Property(
     *                     property="id_jenis_layanan",
     *                     type="integer",
     *                     example=1,
     *                     description="Service type ID"
     *                 ),
     *                 @OA\Property(
     *                     property="judul_layanan",
     *                     type="string",
     *                     maxLength=120,
     *                     example="Pinjaman Anggota",
     *                     description="Service title"
     *                 ),
     *                 @OA\Property(
     *                     property="deskripsi_layanan",
     *                     type="string",
     *                     example="Layanan pinjaman untuk anggota koperasi",
     *                     description="Service description"
     *                 ),
     *                 @OA\Property(
     *                     property="gambar",
     *                     type="string",
     *                     format="binary",
     *                     description="Service image (JPEG, PNG, JPG, GIF max 2MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Layanan created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jenis_layanan' => 'required|exists:jenis_layanan,id_jenis_layanan',
            'judul_layanan' => 'required|string|max:120',
            'deskripsi_layanan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'id_jenis_layanan' => $request->id_jenis_layanan,
                'judul_layanan' => $request->judul_layanan,
            ];
            
            // Handle description if provided
            if ($request->has('deskripsi_layanan')) {
                $data['deskripsi_layanan'] = $request->deskripsi_layanan;
            }
            
            // Handle image upload if provided
            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $imageName = 'layanan_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('layanan', $imageName, 'public');
                $data['gambar'] = $path;
            }
            
            $layanan = Layanan::create($data);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Layanan created successfully',
                'data' => $layanan->load('jenisLayanan')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating layanan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/layanan/{id}",
     *     summary="Get service by ID",
     *     description="Returns a specific service by ID",
     *     operationId="adminGetLayananById",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Service not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        try {
            $layanan = Layanan::with('jenisLayanan')->find($id);
            
            if (!$layanan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Layanan not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $layanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching layanan details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch layanan details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *     path="/admin/layanan/{id}",
     *     summary="Update a service",
     *     description="Updates an existing service with optional image upload",
     *     operationId="adminUpdateLayanan",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="HTTP method override",
     *         required=true,
     *         @OA\Schema(type="string", default="PUT")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id_jenis_layanan",
     *                     type="integer",
     *                     example=1,
     *                     description="Service type ID"
     *                 ),
     *                 @OA\Property(
     *                     property="judul_layanan",
     *                     type="string",
     *                     maxLength=120,
     *                     example="Pinjaman Anggota Updated",
     *                     description="Service title"
     *                 ),
     *                 @OA\Property(
     *                     property="deskripsi_layanan",
     *                     type="string",
     *                     example="Updated description for layanan pinjaman",
     *                     description="Service description"
     *                 ),
     *                 @OA\Property(
     *                     property="gambar",
     *                     type="string",
     *                     format="binary",
     *                     description="Service image (JPEG, PNG, JPG, GIF max 2MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Layanan updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Service not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $layanan = Layanan::find($id);
        
        if (!$layanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Layanan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_jenis_layanan' => 'sometimes|required|exists:jenis_layanan,id_jenis_layanan',
            'judul_layanan' => 'sometimes|required|string|max:120',
            'deskripsi_layanan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->has('id_jenis_layanan')) {
                $layanan->id_jenis_layanan = $request->id_jenis_layanan;
            }
            
            if ($request->has('judul_layanan')) {
                $layanan->judul_layanan = $request->judul_layanan;
            }
            
            if ($request->has('deskripsi_layanan')) {
                $layanan->deskripsi_layanan = $request->deskripsi_layanan;
            }
            
            // Handle image upload if provided
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($layanan->gambar) {
                    Storage::disk('public')->delete($layanan->gambar);
                }
                
                $image = $request->file('gambar');
                $imageName = 'layanan_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('layanan', $imageName, 'public');
                $layanan->gambar = $path;
            }
            
            $layanan->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Layanan updated successfully',
                'data' => $layanan->fresh()->load('jenisLayanan')
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating layanan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Delete(
     *     path="/admin/layanan/{id}",
     *     summary="Delete a service",
     *     description="Deletes a specific service by ID",
     *     operationId="adminDeleteLayanan",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Layanan deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Service not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::find($id);
            
            if (!$layanan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Layanan not found'
                ], 404);
            }
            
            // Delete associated image if exists
            if ($layanan->gambar) {
                Storage::disk('public')->delete($layanan->gambar);
            }
            
            $layanan->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Layanan deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting layanan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all service types.
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/jenis-layanan",
     *     summary="Get all service types",
     *     description="Returns a list of all service types",
     *     operationId="adminGetJenisLayanan",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_jenis_layanan", type="integer", example=1),
     *                     @OA\Property(property="nama_jenis", type="string", example="Pinjaman"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getJenisLayanan()
    {
        try {
            $jenisLayanan = JenisLayanan::all();
            
            return response()->json([
                'status' => 'success',
                'data' => $jenisLayanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching jenis layanan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jenis layanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific service type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/jenis-layanan/{id}",
     *     summary="Get service type by ID",
     *     description="Returns a specific service type by ID with its services",
     *     operationId="adminGetJenisLayananById",
     *     tags={"Admin Services"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_jenis_layanan", type="integer", example=1),
     *                 @OA\Property(property="nama_jenis", type="string", example="Pinjaman"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="layanan", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_layanan", type="integer", example=1),
     *                         @OA\Property(property="id_jenis_layanan", type="integer", example=1),
     *                         @OA\Property(property="judul_layanan", type="string", example="Pinjaman Anggota"),
     *                         @OA\Property(property="deskripsi_layanan", type="string", example="Layanan pinjaman untuk anggota koperasi"),
     *                         @OA\Property(property="gambar", type="string", example="layanan/layanan_1234567890.jpg"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Service type not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getJenisLayananById($id)
    {
        try {
            $jenisLayanan = JenisLayanan::with('layanan')->find($id);
            
            if (!$jenisLayanan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jenis layanan not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $jenisLayanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching jenis layanan details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jenis layanan details: ' . $e->getMessage()
            ], 500);
        }
    }
} 