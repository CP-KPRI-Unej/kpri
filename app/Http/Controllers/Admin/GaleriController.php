<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriFoto;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GaleriController extends Controller
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
     * Display a listing of the gallery items.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $galeris = GaleriFoto::with(['user', 'status'])->orderBy('tgl_upload', 'desc')->get();
        
        return view('admin.galeri.index', compact('galeris'));
    }

    /**
     * Show the form for creating a new gallery item.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $statuses = Status::all();
        
        return view('admin.galeri.create', compact('statuses'));
    }

    /**
     * Store a newly created gallery item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_galeri' => 'required|string|max:30',
            'id_status' => 'required|exists:status,id_status',
            'gambar_galeri' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload image
        $gambarPath = null;
        if ($request->hasFile('gambar_galeri')) {
            $gambarPath = $request->file('gambar_galeri')->store('galeri', 'public');
        }

        // Create gallery item
        $galeri = new GaleriFoto();
        $galeri->nama_galeri = $request->nama_galeri;
        $galeri->id_status = $request->id_status;
        $galeri->id_user = Auth::id();
        $galeri->gambar_galeri = $gambarPath;
        $galeri->tgl_upload = Carbon::now();
        $galeri->save();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    /**
     * Show the form for editing the specified gallery item.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $galeri = GaleriFoto::findOrFail($id);
        $statuses = Status::all();
        
        return view('admin.galeri.edit', compact('galeri', 'statuses'));
    }

    /**
     * Update the specified gallery item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $galeri = GaleriFoto::findOrFail($id);
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_galeri' => 'required|string|max:30',
            'id_status' => 'required|exists:status,id_status',
            'gambar_galeri' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update gallery item
        $galeri->nama_galeri = $request->nama_galeri;
        $galeri->id_status = $request->id_status;

        // Handle image update
        if ($request->hasFile('gambar_galeri')) {
            // Delete old image
            if ($galeri->gambar_galeri && Storage::disk('public')->exists($galeri->gambar_galeri)) {
                Storage::disk('public')->delete($galeri->gambar_galeri);
            }
            
            // Upload new image
            $galeri->gambar_galeri = $request->file('gambar_galeri')->store('galeri', 'public');
        }

        $galeri->save();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified gallery item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $galeri = GaleriFoto::findOrFail($id);
        
        // Delete image from storage
        if ($galeri->gambar_galeri && Storage::disk('public')->exists($galeri->gambar_galeri)) {
            Storage::disk('public')->delete($galeri->gambar_galeri);
        }
        
        // Delete the gallery item
        $galeri->delete();
        
        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
} 