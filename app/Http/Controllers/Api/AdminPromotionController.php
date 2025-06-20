<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Promotions",
 *     description="API Endpoints for Admin Promotion Management"
 * )
 */
class AdminPromotionController extends Controller
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
     * Display a listing of the promotions with pagination, filtering and sorting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/promotions",
     *     summary="Get all promotions",
     *     description="Returns a paginated, filtered and sorted list of promotions",
     *     operationId="adminGetPromotions",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by promotion status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"aktif", "nonaktif", "berakhir"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for promotion title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter promotions starting after this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter promotions ending before this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", enum={"id_promo", "judul_promo", "tgl_start", "tgl_end", "status", "nilai_diskon"}, default="tgl_start")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_promo", type="integer", example=1),
     *                         @OA\Property(property="id_user", type="integer", example=1),
     *                         @OA\Property(property="judul_promo", type="string", example="Flash Sale"),
     *                         @OA\Property(property="tgl_start", type="string", format="date", example="2023-01-01"),
     *                         @OA\Property(property="tgl_end", type="string", format="date", example="2023-01-31"),
     *                         @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                         @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                         @OA\Property(property="status", type="string", example="aktif"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Promotion::with('products');
            
            // Filtering
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where('judul_promo', 'LIKE', "%{$searchTerm}%");
            }
            
            // Date range filtering
            if ($request->has('start_date') && $request->start_date) {
                $query->where('tgl_start', '>=', $request->start_date);
            }
            
            if ($request->has('end_date') && $request->end_date) {
                $query->where('tgl_end', '<=', $request->end_date);
            }
            
            // Sorting
            $sortBy = $request->sort_by ?? 'tgl_start';
            $sortDirection = $request->sort_direction ?? 'desc';
            $allowedSortFields = ['id_promo', 'judul_promo', 'tgl_start', 'tgl_end', 'status', 'nilai_diskon'];
            
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortDirection);
            } else {
                $query->orderBy('tgl_start', 'desc');
            }
            
            // Pagination
            $perPage = $request->per_page ?? 10;
            $promotions = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $promotions
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching promotions: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch promotions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *     path="/admin/promotions",
     *     summary="Create a new promotion",
     *     description="Creates a new promotion and optionally associates products with it",
     *     operationId="adminCreatePromotion",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"judul_promo", "tgl_start", "tgl_end", "tipe_diskon", "nilai_diskon", "status"},
     *             @OA\Property(property="judul_promo", type="string", example="Flash Sale", description="Promotion title"),
     *             @OA\Property(property="tgl_start", type="string", format="date", example="2023-01-01", description="Start date"),
     *             @OA\Property(property="tgl_end", type="string", format="date", example="2023-01-31", description="End date"),
     *             @OA\Property(property="tipe_diskon", type="string", enum={"persen", "nominal"}, example="persen", description="Discount type"),
     *             @OA\Property(property="nilai_diskon", type="integer", example=20, description="Discount value"),
     *             @OA\Property(property="status", type="string", enum={"aktif", "nonaktif", "berakhir"}, example="aktif", description="Promotion status"),
     *             @OA\Property(property="products", type="array", description="Product IDs to associate with promotion",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Promotion created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Promotion created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_promo", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="judul_promo", type="string", example="Flash Sale"),
     *                 @OA\Property(property="tgl_start", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tgl_end", type="string", format="date", example="2023-01-31"),
     *                 @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                 @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                 @OA\Property(property="status", type="string", example="aktif"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="products", type="array", @OA\Items(type="object"))
     *             )
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
            'judul_promo' => 'required|string|max:120',
            'tgl_start' => 'required|date',
            'tgl_end' => 'required|date|after_or_equal:tgl_start',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif,berakhir',
            'products' => 'nullable|array',
            'products.*' => 'exists:produk_kpri,id_produk'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Create new promotion
            $promotion = new Promotion();
            $promotion->id_user = Auth::id();
            $promotion->judul_promo = $request->judul_promo;
            $promotion->tgl_start = $request->tgl_start;
            $promotion->tgl_end = $request->tgl_end;
            $promotion->tipe_diskon = $request->tipe_diskon;
            $promotion->nilai_diskon = $request->nilai_diskon;
            $promotion->status = $request->status;
            $promotion->save();
            
            // Attach products if provided
            if ($request->has('products') && is_array($request->products) && count($request->products) > 0) {
                $promotion->products()->attach($request->products);
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Promotion created successfully',
                'data' => $promotion->load('products')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating promotion: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create promotion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified promotion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/promotions/{id}",
     *     summary="Get promotion by ID",
     *     description="Returns a specific promotion by ID with its associated products",
     *     operationId="adminGetPromotion",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_promo", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="judul_promo", type="string", example="Flash Sale"),
     *                 @OA\Property(property="tgl_start", type="string", format="date", example="2023-01-01"),
     *                 @OA\Property(property="tgl_end", type="string", format="date", example="2023-01-31"),
     *                 @OA\Property(property="tipe_diskon", type="string", example="persen"),
     *                 @OA\Property(property="nilai_diskon", type="integer", example=20),
     *                 @OA\Property(property="status", type="string", example="aktif"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="products", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id_produk", type="integer", example=1),
     *                         @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                         @OA\Property(property="harga_produk", type="number", example=100000),
     *                         @OA\Property(property="pivot", type="object",
     *                             @OA\Property(property="id_promo", type="integer", example=1),
     *                             @OA\Property(property="id_produk", type="integer", example=1)
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        try {
            $promotion = Promotion::with('products')->find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $promotion
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching promotion details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch promotion details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Put(
     *     path="/admin/promotions/{id}",
     *     summary="Update a promotion",
     *     description="Updates an existing promotion and optionally syncs its products",
     *     operationId="adminUpdatePromotion",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="judul_promo", type="string", example="Updated Flash Sale", description="Promotion title"),
     *             @OA\Property(property="tgl_start", type="string", format="date", example="2023-02-01", description="Start date"),
     *             @OA\Property(property="tgl_end", type="string", format="date", example="2023-02-28", description="End date"),
     *             @OA\Property(property="tipe_diskon", type="string", enum={"persen", "nominal"}, example="persen", description="Discount type"),
     *             @OA\Property(property="nilai_diskon", type="integer", example=25, description="Discount value"),
     *             @OA\Property(property="status", type="string", enum={"aktif", "nonaktif", "berakhir"}, example="aktif", description="Promotion status"),
     *             @OA\Property(property="products", type="array", description="Product IDs to associate with promotion (replaces existing)",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Promotion updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Promotion updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);
        
        if (!$promotion) {
            return response()->json([
                'status' => 'error',
                'message' => 'Promotion not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul_promo' => 'sometimes|required|string|max:120',
            'tgl_start' => 'sometimes|required|date',
            'tgl_end' => 'sometimes|required|date|after_or_equal:tgl_start',
            'tipe_diskon' => 'sometimes|required|in:persen,nominal',
            'nilai_diskon' => 'sometimes|required|integer|min:1',
            'status' => 'sometimes|required|in:aktif,nonaktif,berakhir',
            'products' => 'nullable|array',
            'products.*' => 'exists:produk_kpri,id_produk'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Update promotion fields if provided
            if ($request->has('judul_promo')) {
                $promotion->judul_promo = $request->judul_promo;
            }
            
            if ($request->has('tgl_start')) {
                $promotion->tgl_start = $request->tgl_start;
            }
            
            if ($request->has('tgl_end')) {
                $promotion->tgl_end = $request->tgl_end;
            }
            
            if ($request->has('tipe_diskon')) {
                $promotion->tipe_diskon = $request->tipe_diskon;
            }
            
            if ($request->has('nilai_diskon')) {
                $promotion->nilai_diskon = $request->nilai_diskon;
            }
            
            if ($request->has('status')) {
                $promotion->status = $request->status;
            }
            
            $promotion->save();
            
            // Sync products if provided
            if ($request->has('products')) {
                $promotion->products()->sync($request->products);
            }
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Promotion updated successfully',
                'data' => $promotion->fresh()->load('products')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating promotion: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update promotion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified promotion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Delete(
     *     path="/admin/promotions/{id}",
     *     summary="Delete a promotion",
     *     description="Deletes a promotion and detaches all associated products",
     *     operationId="adminDeletePromotion",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Promotion deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Promotion deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        try {
            $promotion = Promotion::find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            // Detach related products
            $promotion->products()->detach();
            
            // Delete the promotion
            $promotion->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Promotion deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting promotion: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete promotion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all products that can be associated with promotions.
     *
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/promotions/available-products",
     *     summary="Get available products",
     *     description="Returns a list of all products that can be associated with promotions",
     *     operationId="adminGetAvailableProducts",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_produk", type="integer", example=1),
     *                     @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="harga_produk", type="number", example=100000),
     *                     @OA\Property(property="gambar_produk", type="string", example="products/product_123.jpg"),
     *                     @OA\Property(property="category", type="object",
     *                         @OA\Property(property="id_kategori", type="integer", example=1),
     *                         @OA\Property(property="nama_kategori", type="string", example="Category Name")
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
    public function getAvailableProducts()
    {
        try {
            $products = Product::with('category')->select('id_produk', 'nama_produk', 'id_kategori', 'harga_produk', 'gambar_produk')->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching available products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch available products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products associated with a specific promotion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Get(
     *     path="/admin/promotions/{id}/products",
     *     summary="Get promotion products",
     *     description="Returns a list of products associated with a specific promotion",
     *     operationId="adminGetPromotionProducts",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_produk", type="integer", example=1),
     *                     @OA\Property(property="nama_produk", type="string", example="Product Name"),
     *                     @OA\Property(property="harga_produk", type="number", example=100000),
     *                     @OA\Property(property="pivot", type="object",
     *                         @OA\Property(property="id_promo", type="integer", example=1),
     *                         @OA\Property(property="id_produk", type="integer", example=1)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getPromotionProducts($id)
    {
        try {
            $promotion = Promotion::with('products')->find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $promotion->products
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching promotion products: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch promotion products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add products to a promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Post(
     *     path="/admin/promotions/{id}/products",
     *     summary="Add products to promotion",
     *     description="Adds products to an existing promotion",
     *     operationId="adminAddPromotionProducts",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(property="products", type="array", description="Product IDs to add to promotion",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Products added to promotion successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function addProducts(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*' => 'exists:produk_kpri,id_produk'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $promotion = Promotion::find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            $promotion->products()->attach($request->products);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Products added to promotion successfully',
                'data' => $promotion->fresh()->load('products')
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding products to promotion: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add products to promotion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove products from a promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Delete(
     *     path="/admin/promotions/{id}/products",
     *     summary="Remove products from promotion",
     *     description="Removes products from an existing promotion",
     *     operationId="adminRemovePromotionProducts",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(property="products", type="array", description="Product IDs to remove from promotion",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Products removed from promotion successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function removeProducts(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*' => 'exists:produk_kpri,id_produk'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $promotion = Promotion::find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            $promotion->products()->detach($request->products);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Products removed from promotion successfully',
                'data' => $promotion->fresh()->load('products')
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing products from promotion: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove products from promotion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of a specific promotion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @OA\Patch(
     *     path="/admin/promotions/{id}/status",
     *     summary="Update promotion status",
     *     description="Updates the status of a specific promotion",
     *     operationId="adminUpdatePromotionStatus",
     *     tags={"Admin Promotions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Promotion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"aktif", "nonaktif", "berakhir"}, example="aktif", description="New promotion status")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Status updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Promotion not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aktif,nonaktif,berakhir'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $promotion = Promotion::find($id);
            
            if (!$promotion) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Promotion not found'
                ], 404);
            }
            
            $promotion->status = $request->status;
            $promotion->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Status promo berhasil diubah',
                'data' => $promotion->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating promotion status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update promotion status: ' . $e->getMessage()
            ], 500);
        }
    }
} 