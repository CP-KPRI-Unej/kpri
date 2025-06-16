<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jenis_layanan' => 'required|exists:jenis_layanan,id_jenis_layanan',
            'judul_layanan' => 'required|string|max:120',
            'deskripsi_layanan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $layanan = Layanan::create([
                'id_jenis_layanan' => $request->id_jenis_layanan,
                'judul_layanan' => $request->judul_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
            ]);
            
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
            'deskripsi_layanan' => 'sometimes|required|string',
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