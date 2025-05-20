<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdukKpri;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
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
     * Display a listing of the products.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $produks = ProdukKpri::with('kategori')->get();
        
        return view('admin.produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $kategoris = KategoriProduk::all();
        
        return view('admin.produk.create', compact('kategoris'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:120',
            'id_kategori' => 'required|exists:kategori_produk,id_kategori',
            'harga_produk' => 'required|integer|min:0',
            'stok_produk' => 'required|integer|min:0',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create product
        $produk = new ProdukKpri();
        $produk->nama_produk = $request->nama_produk;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga_produk = $request->harga_produk;
        $produk->stok_produk = $request->stok_produk;
        $produk->deskripsi_produk = $request->deskripsi_produk;

        // Handle image upload
        if ($request->hasFile('gambar_produk')) {
            $path = $request->file('gambar_produk')->store('produk', 'public');
            $produk->gambar_produk = $path;
        }

        $produk->save();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $produk = ProdukKpri::findOrFail($id);
        $kategoris = KategoriProduk::all();
        
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:120',
            'id_kategori' => 'required|exists:kategori_produk,id_kategori',
            'harga_produk' => 'required|integer|min:0',
            'stok_produk' => 'required|integer|min:0',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update product
        $produk = ProdukKpri::findOrFail($id);
        $produk->nama_produk = $request->nama_produk;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga_produk = $request->harga_produk;
        $produk->stok_produk = $request->stok_produk;
        $produk->deskripsi_produk = $request->deskripsi_produk;

        // Handle image upload
        if ($request->hasFile('gambar_produk')) {
            // Delete old image if exists
            if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }
            
            $path = $request->file('gambar_produk')->store('produk', 'public');
            $produk->gambar_produk = $path;
        }

        $produk->save();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $produk = ProdukKpri::findOrFail($id);
        
        // Delete image from storage if exists
        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }
        
        // Delete the product
        $produk->delete();
        
        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
} 