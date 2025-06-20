<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Public Linktree",
 *     description="API Endpoints for Public Linktree Access"
 * )
 */
class PublicLinktreeController extends Controller
{
    /**
     * Get the linktree data for public display
     * 
     * @param int|null $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/public/linktree/{id?}",
     *     summary="Get linktree data",
     *     description="Returns linktree data with links for public display. If no ID is provided, returns the first linktree.",
     *     operationId="getPublicLinktree",
     *     tags={"Public Linktree"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Linktree ID (optional)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="linktree", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="KPRI Links"),
     *                     @OA\Property(property="description", type="string", example="Official links for KPRI"),
     *                     @OA\Property(property="logo", type="string", example="uploads/linktree/logo.png"),
     *                     @OA\Property(property="background_color", type="string", example="#ffffff"),
     *                     @OA\Property(property="text_color", type="string", example="#000000"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 ),
     *                 @OA\Property(property="links", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="page_id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Official Website"),
     *                         @OA\Property(property="url", type="string", example="https://kpri.example.com"),
     *                         @OA\Property(property="icon", type="string", example="fa-globe"),
     *                         @OA\Property(property="position", type="integer", example=1),
     *                         @OA\Property(property="button_color", type="string", example="#007bff"),
     *                         @OA\Property(property="text_color", type="string", example="#ffffff"),
     *                         @OA\Property(property="active", type="boolean", example=true),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Linktree data retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Linktree not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getLinktree($id = null)
    {
        try {
            // If no ID is provided, get the first linktree (assuming it's the main one)
            $query = DB::table('linktree');
            
            if ($id) {
                $linktree = $query->where('id', $id)->first();
            } else {
                $linktree = $query->first();
            }
            
            if (!$linktree) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Linktree not found'
                ], 404);
            }
            
            // Get links for this linktree
            $links = DB::table('links')
                ->where('page_id', $linktree->id)
                ->orderBy('position')
                ->get();
            
            // Record visit statistics
            $this->recordVisit(request());
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'linktree' => $linktree,
                    'links' => $links
                ],
                'message' => 'Linktree data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve linktree data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Record visitor statistics
     * 
     * @param Request $request
     * @return void
     */
    private function recordVisit(Request $request)
    {
        try {
            DB::table('visitor_stats')->insert([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'page_visited' => '/',
                'visited_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            Log::error('Failed to record visitor stats: ' . $e->getMessage());
        }
    }
} 
