<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Product Categories",
 *     description="API Endpoints for Product Category Management"
 * )
 */
class AdminKategoriProdukController extends Controller
{
    /**
     * Display a listing of the product categories.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/categories",
     *     summary="Get all product categories",
     *     description="Returns a list of all product categories with product counts",
     *     operationId="adminGetCategories",
     *     tags={"Admin Product Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="kategori", type="string", example="Electronics"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="produks_count", type="integer", example=15)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        $categories = KategoriProduk::withCount('produks')
            ->orderBy('kategori')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/categories/{id}",
     *     summary="Get product category by ID",
     *     description="Returns a specific product category by ID with product count",
     *     operationId="adminGetCategory",
     *     tags={"Admin Product Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="string", example="Electronics"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="produks_count", type="integer", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        $category = KategoriProduk::withCount('produks')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Store a newly created category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/categories",
     *     summary="Create a new product category",
     *     description="Creates a new product category",
     *     operationId="adminCreateCategory",
     *     tags={"Admin Product Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kategori"},
     *             @OA\Property(property="kategori", type="string", maxLength=30, example="Home Appliances")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="string", example="Home Appliances"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
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
        // Validate request
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:30|unique:kategori_produk,kategori',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create new category
        $category = new KategoriProduk();
        $category->kategori = $request->kategori;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Update the specified category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Put(
     *     path="/admin/categories/{id}",
     *     summary="Update a product category",
     *     description="Updates an existing product category by ID",
     *     operationId="adminUpdateCategory",
     *     tags={"Admin Product Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"kategori"},
     *             @OA\Property(property="kategori", type="string", maxLength=30, example="Kitchen Appliances")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="string", example="Kitchen Appliances"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        // Find category
        $category = KategoriProduk::findOrFail($id);

        // Validate request
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:30|unique:kategori_produk,kategori,' . $id . ',id_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update category
        $category->kategori = $request->kategori;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Delete(
     *     path="/admin/categories/{id}",
     *     summary="Delete a product category",
     *     description="Deletes a product category if it doesn't have any products",
     *     operationId="adminDeleteCategory",
     *     tags={"Admin Product Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Category deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Cannot delete category that has products",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cannot delete category that has products")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        // Find category
        $category = KategoriProduk::withCount('produks')->findOrFail($id);

        // Check if category has products
        if ($category->produks_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that has products'
            ], 422);
        }

        // Delete category
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
