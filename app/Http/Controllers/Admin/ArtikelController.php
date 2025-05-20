<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\ArtikelImage;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
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
     * Display a listing of the articles.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $artikels = Artikel::with(['status', 'user', 'images', 'komentar'])->get();
        
        return view('admin.artikel.index', compact('artikels'));
    }

    /**
     * Show the form for creating a new article.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $statuses = Status::all();
        return view('admin.artikel.create', compact('statuses'));
    }

    /**
     * Store a newly created article in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_artikel' => 'required|string|max:120',
            'deskripsi_artikel' => 'required|string',
            'id_status' => 'required|exists:status,id_status',
            'tgl_rilis' => 'required|date',
            'tags_artikel' => 'nullable|string|max:255',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar' => 'required|array|min:1|max:3', // Minimum 1, maximum 3 images
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create article
        $artikel = new Artikel();
        $artikel->nama_artikel = $request->nama_artikel;
        $artikel->deskripsi_artikel = $request->deskripsi_artikel;
        $artikel->id_status = $request->id_status;
        $artikel->tgl_rilis = $request->tgl_rilis;
        $artikel->tags_artikel = $request->tags_artikel ?? '';
        $artikel->id_user = Auth::id();
        $artikel->save();

        // Handle image uploads
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $path = $image->store('artikel', 'public');
                
                ArtikelImage::create([
                    'id_artikel' => $artikel->id_artikel,
                    'gambar' => $path
                ]);
            }
        }

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified article.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $artikel = Artikel::with('images')->findOrFail($id);
        $statuses = Status::all();
        
        return view('admin.artikel.edit', compact('artikel', 'statuses'));
    }

    /**
     * Update the specified article in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama_artikel' => 'required|string|max:120',
            'deskripsi_artikel' => 'required|string',
            'id_status' => 'required|exists:status,id_status',
            'tgl_rilis' => 'required|date',
            'tags_artikel' => 'nullable|string|max:255',
            'gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update article
        $artikel->nama_artikel = $request->nama_artikel;
        $artikel->deskripsi_artikel = $request->deskripsi_artikel;
        $artikel->id_status = $request->id_status;
        $artikel->tgl_rilis = $request->tgl_rilis;
        $artikel->tags_artikel = $request->tags_artikel ?? '';
        $artikel->save();

        // Handle image uploads
        if ($request->hasFile('gambar')) {
            $currentImageCount = $artikel->images->count();
            $newImageCount = count($request->file('gambar'));
            
            // Check if total images would exceed max limit of 3
            if ($currentImageCount + $newImageCount > 3) {
                return redirect()->back()
                    ->withErrors(['gambar' => 'Maksimal 3 gambar diperbolehkan per artikel.'])
                    ->withInput();
            }
            
            foreach ($request->file('gambar') as $image) {
                $path = $image->store('artikel', 'public');
                
                ArtikelImage::create([
                    'id_artikel' => $artikel->id_artikel,
                    'gambar' => $path
                ]);
            }
        }

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ArtikelImage::find($imageId);
                if ($image && $image->id_artikel == $artikel->id_artikel) {
                    if (Storage::disk('public')->exists($image->gambar)) {
                        Storage::disk('public')->delete($image->gambar);
                    }
                    $image->delete();
                }
            }
        }

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified article from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $artikel = Artikel::with('images')->findOrFail($id);
        
        // Delete associated images from storage
        foreach ($artikel->images as $image) {
            if (Storage::disk('public')->exists($image->gambar)) {
                Storage::disk('public')->delete($image->gambar);
            }
        }
        
        // Delete the article (will cascade delete images due to foreign key constraint)
        $artikel->delete();
        
        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
} 