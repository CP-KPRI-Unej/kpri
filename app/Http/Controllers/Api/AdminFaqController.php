<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin FAQ",
 *     description="API Endpoints for Admin FAQ Management"
 * )
 */
class AdminFaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/faqs",
     *     summary="Get all FAQs",
     *     description="Returns a list of all FAQs with user and status information",
     *     operationId="adminGetFaqs",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_faq", type="integer", example=1),
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="judul", type="string", example="How to reset my password?"),
     *                     @OA\Property(property="deskripsi", type="string", example="To reset your password, click on the 'Forgot Password' link..."),
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="user", type="object",
     *                         @OA\Property(property="id_user", type="integer", example=1),
     *                         @OA\Property(property="nama_user", type="string", example="Admin User")
     *                     ),
     *                     @OA\Property(property="status", type="object",
     *                         @OA\Property(property="id_status", type="integer", example=1),
     *                         @OA\Property(property="nama_status", type="string", example="Published")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="FAQs retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        try {
            $faqs = Faq::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->orderBy('id_faq', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $faqs,
                'message' => 'FAQs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve FAQs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all available statuses.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/faqs/statuses",
     *     summary="Get all statuses",
     *     description="Returns a list of all available statuses for FAQs",
     *     operationId="adminGetFaqStatuses",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="nama_status", type="string", example="Published"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Statuses retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getStatuses()
    {
        try {
            $statuses = Status::all();
            
            return response()->json([
                'status' => 'success',
                'data' => $statuses,
                'message' => 'Statuses retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve statuses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/faqs",
     *     summary="Create a new FAQ",
     *     description="Creates a new FAQ item",
     *     operationId="adminCreateFaq",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"judul", "deskripsi"},
     *             @OA\Property(property="judul", type="string", maxLength=120, example="How to reset my password?"),
     *             @OA\Property(property="deskripsi", type="string", example="To reset your password, click on the 'Forgot Password' link..."),
     *             @OA\Property(property="id_status", type="integer", example=1, description="Status ID (optional, defaults to 1)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="FAQ created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_faq", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="How to reset my password?"),
     *                 @OA\Property(property="deskripsi", type="string", example="To reset your password, click on the 'Forgot Password' link..."),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="FAQ created successfully")
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
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
            'id_status' => 'nullable|exists:status,id_status',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $faq = new Faq();
            $faq->id_user = Auth::id();
            $faq->judul = $request->judul;
            $faq->deskripsi = $request->deskripsi;
            $faq->id_status = $request->id_status ?? 1; // Default to 1 if not provided
            $faq->save();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create FAQ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified FAQ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/faqs/{id}",
     *     summary="Get FAQ by ID",
     *     description="Returns a specific FAQ by ID with user and status information",
     *     operationId="adminGetFaq",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="FAQ ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_faq", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="How to reset my password?"),
     *                 @OA\Property(property="deskripsi", type="string", example="To reset your password, click on the 'Forgot Password' link..."),
     *                 @OA\Property(property="id_status", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id_user", type="integer", example=1),
     *                     @OA\Property(property="nama_user", type="string", example="Admin User")
     *                 ),
     *                 @OA\Property(property="status", type="object",
     *                     @OA\Property(property="id_status", type="integer", example=1),
     *                     @OA\Property(property="nama_status", type="string", example="Published")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="FAQ retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="FAQ not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        try {
            $faq = Faq::with(['user:id_user,nama_user', 'status:id_status,nama_status'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ not found'
            ], 404);
        }
    }

    /**
     * Update the specified FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Put(
     *     path="/admin/faqs/{id}",
     *     summary="Update a FAQ",
     *     description="Updates an existing FAQ by ID",
     *     operationId="adminUpdateFaq",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="FAQ ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"judul", "deskripsi"},
     *             @OA\Property(property="judul", type="string", maxLength=120, example="Updated: How to reset my password?"),
     *             @OA\Property(property="deskripsi", type="string", example="Updated instructions for resetting password..."),
     *             @OA\Property(property="id_status", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FAQ updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_faq", type="integer", example=1),
     *                 @OA\Property(property="id_user", type="integer", example=1),
     *                 @OA\Property(property="judul", type="string", example="Updated: How to reset my password?"),
     *                 @OA\Property(property="deskripsi", type="string", example="Updated instructions for resetting password..."),
     *                 @OA\Property(property="id_status", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-12-31T12:00:00Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="FAQ updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="FAQ not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:120',
            'deskripsi' => 'required|string',
            'id_status' => 'nullable|exists:status,id_status',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $faq = Faq::findOrFail($id);
            
            $faq->judul = $request->judul;
            $faq->deskripsi = $request->deskripsi;
            if ($request->has('id_status')) {
                $faq->id_status = $request->id_status;
            }
            $faq->save();

            return response()->json([
                'status' => 'success',
                'data' => $faq,
                'message' => 'FAQ updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update FAQ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Delete(
     *     path="/admin/faqs/{id}",
     *     summary="Delete a FAQ",
     *     description="Deletes a specific FAQ by ID",
     *     operationId="adminDeleteFaq",
     *     tags={"Admin FAQ"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="FAQ ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="FAQ deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="FAQ deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="FAQ not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faq->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'FAQ deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete FAQ: ' . $e->getMessage()
            ], 500);
        }
    }
} 
 
 
 
 
 
 
 
 
 
 
 