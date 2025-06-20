<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Linktree Stats",
 *     description="API Endpoints for Linktree visitor statistics and analytics"
 * )
 */
class LinktreeStatsController extends Controller
{
    /**
     * Get statistics for linktree visits
     *
     * @OA\Get(
     *     path="/admin/linktree/stats",
     *     summary="Get Linktree visitor statistics",
     *     description="Retrieves statistics for Linktree visits including total, today's, monthly, and daily stats",
     *     tags={"Linktree Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="totalVisitors", type="integer", example=864),
     *                 @OA\Property(property="todayVisitors", type="integer", example=37),
     *                 @OA\Property(property="monthVisitors", type="integer", example=245),
     *                 @OA\Property(
     *                     property="dailyStats",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="date", type="string", format="date", example="2023-10-25"),
     *                         @OA\Property(property="count", type="integer", example=42)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Linktree statistics retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve linktree statistics: Database connection error")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            
            // Total visitors count for linktree
            $totalVisitors = DB::table('visitor_stats')
                ->where('page_visited', '/')
                ->count();
            
            // Today's visitors count
            $todayVisitors = DB::table('visitor_stats')
                ->where('page_visited', '/')
                ->whereDate('visited_at', $today)
                ->count();
            
            // This month's visitors count
            $monthVisitors = DB::table('visitor_stats')
                ->where('page_visited', '/')
                ->whereDate('visited_at', '>=', $startOfMonth)
                ->count();
                
            // Daily visitors for last 7 days
            $dailyStats = DB::table('visitor_stats')
                ->where('page_visited', '/')
                ->where('visited_at', '>=', Carbon::now()->subDays(7))
                ->selectRaw('DATE(visited_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'totalVisitors' => $totalVisitors,
                    'todayVisitors' => $todayVisitors,
                    'monthVisitors' => $monthVisitors,
                    'dailyStats' => $dailyStats
                ],
                'message' => 'Linktree statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve linktree statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get most common referrers
     *
     * @OA\Get(
     *     path="/admin/linktree/referrers/{limit}",
     *     summary="Get Linktree referrers",
     *     description="Retrieves the most common referrers for Linktree visits",
     *     tags={"Linktree Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="Number of referrers to retrieve (default: 5)",
     *         required=false,
     *         @OA\Schema(type="integer", default=5)
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
     *                     type="object",
     *                     @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Mobile/15E148 Safari/604.1"),
     *                     @OA\Property(property="count", type="integer", example=45)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Linktree referrers retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve linktree referrers: Database connection error")
     *         )
     *     )
     * )
     *
     * @param int $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReferrers($limit = 5)
    {
        try {
            // Extract referrer from user-agent if available
            $referrers = DB::table('visitor_stats')
                ->where('page_visited', '/')
                ->select('user_agent')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('user_agent')
                ->orderBy('count', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $referrers,
                'message' => 'Linktree referrers retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve linktree referrers: ' . $e->getMessage()
            ], 500);
        }
    }
} 