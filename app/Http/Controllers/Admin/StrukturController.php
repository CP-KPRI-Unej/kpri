<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\StrukturKepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StrukturController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the organization structure.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get all pengurus grouped by jabatan for efficient display
        $struktur = StrukturKepengurusan::with('jabatan')
            ->join('jabatan', 'struktur_kepengurusan.id_jabatan', '=', 'jabatan.id_jabatan')
            ->orderBy('jabatan.nama_jabatan')
            ->orderBy('struktur_kepengurusan.nama_pengurus')
            ->get();

        // Group by jabatan for the view
        $strukturByJabatan = $struktur->groupBy('jabatan.nama_jabatan');
        
        return view('admin.struktur.index', compact('strukturByJabatan'));
    }

    /**
     * Show the form for creating a new pengurus.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        
        return view('admin.struktur.create', compact('jabatan'));
    }

    /**
     * Store a newly created pengurus in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama_pengurus' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create pengurus
        StrukturKepengurusan::create([
            'id_jabatan' => $request->id_jabatan,
            'nama_pengurus' => $request->nama_pengurus,
        ]);

        return redirect()->route('admin.struktur.index')
            ->with('success', 'Anggota pengurus berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified pengurus.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $pengurus = StrukturKepengurusan::findOrFail($id);
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        
        return view('admin.struktur.edit', compact('pengurus', 'jabatan'));
    }

    /**
     * Update the specified pengurus in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama_pengurus' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update pengurus
        $pengurus = StrukturKepengurusan::findOrFail($id);
        $pengurus->update([
            'id_jabatan' => $request->id_jabatan,
            'nama_pengurus' => $request->nama_pengurus,
        ]);

        return redirect()->route('admin.struktur.index')
            ->with('success', 'Anggota pengurus berhasil diperbarui.');
    }

    /**
     * Remove the specified pengurus from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Delete the pengurus
        $pengurus = StrukturKepengurusan::findOrFail($id);
        $pengurus->delete();
        
        return redirect()->route('admin.struktur.index')
            ->with('success', 'Anggota pengurus berhasil dihapus.');
    }
} 