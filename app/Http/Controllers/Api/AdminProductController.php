<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Products",
 *     description="API Endpoints for Product management"
 * )
 */
class AdminProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @OA\Get(
     *     path="/admin/products",
     *     summary="Get all products",
     *     description="Retrieve a list of products with optional filtering and sorting",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter products by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for product name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by (nama_produk, harga_produk, stok_produk)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"nama_produk", "harga_produk", "stok_produk"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_produk", type="integer", example=1),
     *                         @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                         @OA\Property(property="id_kategori", type="integer", example=1),
     *                         @OA\Property(property="harga_produk", type="integer", example=100000),
     *                         @OA\Property(property="stok_produk", type="integer", example=10),
     *                         @OA\Property(property="deskripsi_produk", type="string", example="Product description"),
     *                         @OA\Property(property="gambar_produk", type="string", nullable=true, example="products/image.jpg"),
     *                         @OA\Property(
     *                             property="category",
     *                             type="object",
     *                             @OA\Property(property="id_kategori", type="integer", example=1),
     *                             @OA\Property(property="nama_kategori", type="string", example="Category Name")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="last_page_url", type="string"),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
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
     * @OA\Post(
     *     path="/admin/products",
     *     summary="Create a new product",
     *     description="Store a newly created product in the database",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nama_produk", type="string", example="New Product", description="Product name"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1, description="Category ID"),
     *                 @OA\Property(property="harga_produk", type="integer", example=100000, description="Product price"),
     *                 @OA\Property(property="stok_produk", type="integer", example=10, description="Product stock"),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Product description", description="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", format="binary", description="Product image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_produk", type="integer", example=1),
     *                 @OA\Property(property="nama_produk", type="string", example="New Product"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="harga_produk", type="integer", example=100000),
     *                 @OA\Property(property="stok_produk", type="integer", example=10),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", example="products/image.jpg"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/admin/products/{id}",
     *     summary="Get product details",
     *     description="Get detailed information about a specific product",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_produk", type="integer", example=1),
     *                 @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="harga_produk", type="integer", example=100000),
     *                 @OA\Property(property="stok_produk", type="integer", example=10),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", example="products/image.jpg"),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Category Name")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/admin/products/{id}",
     *     summary="Update a product",
     *     description="Update product information",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PUT", description="Method spoofing"),
     *                 @OA\Property(property="nama_produk", type="string", example="Updated Product", description="Product name"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1, description="Category ID"),
     *                 @OA\Property(property="harga_produk", type="integer", example=120000, description="Product price"),
     *                 @OA\Property(property="stok_produk", type="integer", example=15, description="Product stock"),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Updated description", description="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", format="binary", description="Product image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_produk", type="integer", example=1),
     *                 @OA\Property(property="nama_produk", type="string", example="Updated Product"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="harga_produk", type="integer", example=120000),
     *                 @OA\Property(property="stok_produk", type="integer", example=15),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Updated description"),
     *                 @OA\Property(property="gambar_produk", type="string", example="products/updated-image.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     *
     * @OA\Put(
     *     path="/admin/products/{id}",
     *     summary="Update a product (PUT method)",
     *     description="Update product information using PUT",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="nama_produk", type="string", example="Updated Product", description="Product name"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1, description="Category ID"),
     *                 @OA\Property(property="harga_produk", type="integer", example=120000, description="Product price"),
     *                 @OA\Property(property="stok_produk", type="integer", example=15, description="Product stock"),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Updated description", description="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", format="binary", description="Product image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_produk", type="integer", example=1),
     *                 @OA\Property(property="nama_produk", type="string", example="Updated Product"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="harga_produk", type="integer", example=120000),
     *                 @OA\Property(property="stok_produk", type="integer", example=15),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Updated description"),
     *                 @OA\Property(property="gambar_produk", type="string", example="products/updated-image.jpg")
     *             )
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/admin/products/{id}",
     *     summary="Delete a product",
     *     description="Remove a product from the database",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/admin/product-categories",
     *     summary="Get all product categories",
     *     description="Retrieve a list of all product categories",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Category Name"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/admin/products/{id}/promotions",
     *     summary="Add product to promotions",
     *     description="Add a product to one or more promotions",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"promotions"},
     *             @OA\Property(
     *                 property="promotions",
     *                 type="array",
     *                 description="Array of promotion IDs",
     *                 @OA\Items(type="integer"),
     *                 example={1, 2, 3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product promotions updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_produk", type="integer", example=1),
     *                 @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="harga_produk", type="integer", example=100000),
     *                 @OA\Property(property="stok_produk", type="integer", example=10),
     *                 @OA\Property(property="deskripsi_produk", type="string", example="Product description"),
     *                 @OA\Property(property="gambar_produk", type="string", example="products/image.jpg"),
     *                 @OA\Property(
     *                     property="promotions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_promo", type="integer", example=1),
     *                         @OA\Property(property="nama_promo", type="string", example="Promotion Name"),
     *                         @OA\Property(property="deskripsi_promo", type="string", example="Promotion Description"),
     *                         @OA\Property(property="gambar_promo", type="string", example="promotions/promo.jpg"),
     *                         @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                         @OA\Property(property="tanggal_selesai", type="string", format="date", example="2023-01-31"),
     *                         @OA\Property(property="status", type="integer", example=1),
     *                         @OA\Property(property="pivot", type="object",
     *                             @OA\Property(property="id_produk", type="integer", example=1),
     *                             @OA\Property(property="id_promo", type="integer", example=1)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/admin/products/{id}/promotions",
     *     summary="Get product promotions",
     *     description="Get all promotions associated with a product",
     *     tags={"Admin Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_promo", type="integer", example=1),
     *                     @OA\Property(property="nama_promo", type="string", example="Promotion Name"),
     *                     @OA\Property(property="deskripsi_promo", type="string", example="Promotion Description"),
     *                     @OA\Property(property="gambar_promo", type="string", example="promotions/promo.jpg"),
     *                     @OA\Property(property="tanggal_mulai", type="string", format="date", example="2023-01-01"),
     *                     @OA\Property(property="tanggal_selesai", type="string", format="date", example="2023-01-31"),
     *                     @OA\Property(property="status", type="integer", example=1),
     *                     @OA\Property(property="pivot", type="object",
     *                         @OA\Property(property="id_produk", type="integer", example=1),
     *                         @OA\Property(property="id_promo", type="integer", example=1)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Product not found")
     *         )
     *     )
     * )
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