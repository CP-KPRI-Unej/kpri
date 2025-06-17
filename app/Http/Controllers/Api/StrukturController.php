<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturKepengurusan;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StrukturController extends Controller
{
    /**
     * Display a listing of the struktur kepengurusan.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all jabatan
        $jabatan = Jabatan::all();

        // Get all pengurus with their respective jabatan
        $strukturByJabatan = [];

        foreach ($jabatan as $jab) {
            $pengurus = StrukturKepengurusan::where('id_jabatan', $jab->id_jabatan)
                ->orderBy('nama_pengurus')
                ->get();

            $strukturByJabatan[$jab->nama_jabatan] = $pengurus;
        }

        return response()->json([
            'success' => true,
            'data' => $strukturByJabatan
        ]);
    }

    /**
     * Get all available jabatan.
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $pengurus = StrukturKepengurusan::with('jabatan')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pengurus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
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
            'nama_pengurus' => $request->nama_pengurus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pengurus berhasil diperbarui',
            'data' => $pengurus
        ]);
    }

    /**
     * Remove the specified resource from storage.
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
