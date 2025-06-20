<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Admin Status",
 *     description="API Endpoints for Admin Status Management"
 * )
 */
class AdminStatusController extends Controller
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
     * Display a listing of all statuses.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/statuses",
     *     summary="Get all statuses",
     *     description="Returns a list of all available statuses",
     *     operationId="adminGetStatuses",
     *     tags={"Admin Status"},
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
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Status list retrieved successfully")
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
            $statuses = Status::all();

            return response()->json([
                'status' => 'success',
                'data' => $statuses,
                'message' => 'Status list retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve status list: ' . $e->getMessage()
            ], 500);
        }
    }
} 