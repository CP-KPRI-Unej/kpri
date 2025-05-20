<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriProdukController extends Controller
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
     * Display a listing of the categories.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kategoris = KategoriProduk::withCount('produks')->get();
        
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:30|unique:kategori_produk,kategori',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create category
        $kategori = new KategoriProduk();
        $kategori->kategori = $request->kategori;
        $kategori->save();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $kategori = KategoriProduk::findOrFail($id);
        
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $kategori = KategoriProduk::findOrFail($id);
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:30|unique:kategori_produk,kategori,'.$id.',id_kategori',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update category
        $kategori->kategori = $request->kategori;
        $kategori->save();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $kategori = KategoriProduk::withCount('produks')->findOrFail($id);
        
        // Check if category has products
        if ($kategori->produks_count > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh '.$kategori->produks_count.' produk.');
        }
        
        // Delete the category
        $kategori->delete();
        
        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
} 