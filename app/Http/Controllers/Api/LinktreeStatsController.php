<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LinktreeStatsController extends Controller
{
    /**
     * Get statistics for linktree visits
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