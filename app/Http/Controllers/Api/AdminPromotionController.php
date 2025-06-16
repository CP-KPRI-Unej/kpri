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
     */
    public function index(Request $request)
    {
        try {
            $query = Promotion::query();
            
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
} 