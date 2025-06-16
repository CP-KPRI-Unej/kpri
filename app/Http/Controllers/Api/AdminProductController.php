<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('id_kategori', $request->category);
        }
        
        // Search by name if provided
        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        
        // Sort products
        $sortField = $request->input('sort_by', 'nama_produk');
        $sortDirection = $request->input('sort_direction', 'asc');
        $allowedSortFields = ['nama_produk', 'harga_produk', 'stok_produk'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $products = $query->paginate($request->input('per_page', 15));
        
        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:120',
            'id_kategori' => 'required|exists:kategori_produk,id_kategori',
            'harga_produk' => 'required|integer|min:0',
            'stok_produk' => 'required|integer|min:0',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('gambar_produk')) {
            $file = $request->file('gambar_produk');
            $imagePath = $file->store('products', 'public');
        }

        // Create new product
        $product = new Product();
        $product->nama_produk = $request->nama_produk;
        $product->id_kategori = $request->id_kategori;
        $product->harga_produk = $request->harga_produk;
        $product->stok_produk = $request->stok_produk;
        $product->deskripsi_produk = $request->deskripsi_produk;
        $product->gambar_produk = $imagePath;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Update the specified product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'nullable|string|max:120',
            'id_kategori' => 'nullable|exists:kategori_produk,id_kategori',
            'harga_produk' => 'nullable|integer|min:0',
            'stok_produk' => 'nullable|integer|min:0',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if provided
        if ($request->hasFile('gambar_produk')) {
            // Delete old image if exists
            if ($product->gambar_produk && Storage::disk('public')->exists($product->gambar_produk)) {
                Storage::disk('public')->delete($product->gambar_produk);
            }
            
            // Store new image
            $file = $request->file('gambar_produk');
            $imagePath = $file->store('products', 'public');
            $product->gambar_produk = $imagePath;
        }

        // Update other fields if provided
        if ($request->has('nama_produk')) {
            $product->nama_produk = $request->nama_produk;
        }
        
        if ($request->has('id_kategori')) {
            $product->id_kategori = $request->id_kategori;
        }
        
        if ($request->has('harga_produk')) {
            $product->harga_produk = $request->harga_produk;
        }
        
        if ($request->has('stok_produk')) {
            $product->stok_produk = $request->stok_produk;
        }
        
        if ($request->has('deskripsi_produk')) {
            $product->deskripsi_produk = $request->deskripsi_produk;
        }

        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        // Delete image from storage
        if ($product->gambar_produk && Storage::disk('public')->exists($product->gambar_produk)) {
            Storage::disk('public')->delete($product->gambar_produk);
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Get all product categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = ProductCategory::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Add product to promotions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addToPromotions(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'promotions' => 'required|array',
            'promotions.*' => 'exists:promo_kpri,id_promo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product->promotions()->sync($request->promotions);

        return response()->json([
            'status' => 'success',
            'message' => 'Product promotions updated successfully',
            'data' => $product->load('promotions')
        ]);
    }

    /**
     * Get product promotions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPromotions($id)
    {
        $product = Product::with('promotions')->find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $product->promotions
        ]);
    }
} 