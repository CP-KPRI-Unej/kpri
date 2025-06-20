<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Dashboard Stats",
 *     description="API Endpoints for dashboard statistics and visitor analytics"
 * )
 */
class DashboardStatsController extends Controller
{
    /**
     * Get summary statistics for dashboard
     *
     * @OA\Get(
     *     path="/admin/dashboard/summary",
     *     summary="Get dashboard summary statistics",
     *     description="Retrieves summary statistics including total, today's, and monthly visitor counts",
     *     tags={"Dashboard Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="totalVisitors", type="integer", example=1250),
     *                 @OA\Property(property="todayVisitors", type="integer", example=42),
     *                 @OA\Property(property="monthVisitors", type="integer", example=356)
     *             ),
     *             @OA\Property(property="message", type="string", example="Dashboard summary statistics retrieved successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve dashboard statistics: Database connection error")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummaryStats()
    {
        try {
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            
            // Total visitors count
            $totalVisitors = DB::table('visitor_stats')->count();
            
            // Today's visitors count
            $todayVisitors = DB::table('visitor_stats')
                ->whereDate('visited_at', $today)
                ->count();
            
            // This month's visitors count
            $monthVisitors = DB::table('visitor_stats')
                ->whereDate('visited_at', '>=', $startOfMonth)
                ->count();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'totalVisitors' => $totalVisitors,
                    'todayVisitors' => $todayVisitors,
                    'monthVisitors' => $monthVisitors
                ],
                'message' => 'Dashboard summary statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve dashboard statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get recent visitors
     *
     * @OA\Get(
     *     path="/admin/dashboard/recent-visitors/{limit}",
     *     summary="Get recent visitors",
     *     description="Retrieves a list of recent visitors with their details",
     *     tags={"Dashboard Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="Number of recent visitors to retrieve (default: 10)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
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
     *                     @OA\Property(property="ip_address", type="string", example="192.168.1.1"),
     *                     @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (Windows NT 10.0; Win64; x64)..."),
     *                     @OA\Property(property="page_visited", type="string", example="/produk"),
     *                     @OA\Property(property="visited_at", type="string", format="date-time", example="2023-10-25 14:30:00"),
     *                     @OA\Property(property="time_ago", type="string", example="5 minutes ago")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Recent visitors retrieved successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve recent visitors: Database connection error")
     *         )
     *     )
     * )
     *
     * @param int $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentVisitors($limit = 10)
    {
        try {
            $visitors = DB::table('visitor_stats')
                ->select('ip_address', 'user_agent', 'page_visited', 'visited_at')
                ->orderBy('visited_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($visitor) {
                    $visitedAt = Carbon::parse($visitor->visited_at);
                    
                    return [
                        'ip_address' => $visitor->ip_address,
                        'user_agent' => $visitor->user_agent,
                        'page_visited' => $visitor->page_visited,
                        'visited_at' => $visitedAt->toDateTimeString(),
                        'time_ago' => $visitedAt->diffForHumans()
                    ];
                });
                
            return response()->json([
                'status' => 'success',
                'data' => $visitors,
                'message' => 'Recent visitors retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent visitors: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get chart data for monthly statistics in a given year
     *
     * @OA\Get(
     *     path="/admin/dashboard/monthly-chart/{year}",
     *     summary="Get monthly visitor chart data",
     *     description="Retrieves monthly visitor statistics for a specific year",
     *     tags={"Dashboard Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="year",
     *         in="path",
     *         description="Year to retrieve data for (defaults to current year if not provided)",
     *         required=false,
     *         @OA\Schema(type="integer", example=2023)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="year", type="integer", example=2023),
     *                 @OA\Property(
     *                     property="chartData",
     *                     type="object",
     *                     @OA\Property(property="1", type="integer", example=42),
     *                     @OA\Property(property="2", type="integer", example=56),
     *                     @OA\Property(property="3", type="integer", example=78),
     *                     @OA\Property(property="4", type="integer", example=103),
     *                     @OA\Property(property="5", type="integer", example=142),
     *                     @OA\Property(property="6", type="integer", example=198),
     *                     @OA\Property(property="7", type="integer", example=210),
     *                     @OA\Property(property="8", type="integer", example=167),
     *                     @OA\Property(property="9", type="integer", example=145),
     *                     @OA\Property(property="10", type="integer", example=156),
     *                     @OA\Property(property="11", type="integer", example=132),
     *                     @OA\Property(property="12", type="integer", example=178)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Monthly visitor chart data retrieved successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve monthly chart data: Database connection error")
     *         )
     *     )
     * )
     *
     * @param int|null $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyChartData($year = null)
    {
        try {
            $year = $year ?? Carbon::now()->year;
            
            $monthlyVisitors = DB::table('visitor_stats')
                ->selectRaw('MONTH(visited_at) as month, COUNT(*) as count')
                ->whereYear('visited_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
            
            // Fill in missing months with zero
            $chartData = [];
            for ($i = 1; $i <= 12; $i++) {
                $chartData[$i] = isset($monthlyVisitors[$i]) ? (int)$monthlyVisitors[$i] : 0;
            }
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'year' => (int) $year,
                    'chartData' => $chartData
                ],
                'message' => 'Monthly visitor chart data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve monthly chart data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get statistics for most visited pages
     *
     * @OA\Get(
     *     path="/admin/dashboard/top-pages/{limit}",
     *     summary="Get most visited pages",
     *     description="Retrieves statistics for the most frequently visited pages",
     *     tags={"Dashboard Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="path",
     *         description="Number of top pages to retrieve (default: 5)",
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
     *                     @OA\Property(property="page_visited", type="string", example="/produk"),
     *                     @OA\Property(property="visit_count", type="integer", example=342)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Top visited pages retrieved successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve top pages: Database connection error")
     *         )
     *     )
     * )
     *
     * @param int $limit
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopPages($limit = 5)
    {
        try {
            $topPages = DB::table('visitor_stats')
                ->select('page_visited', DB::raw('COUNT(*) as visit_count'))
                ->groupBy('page_visited')
                ->orderBy('visit_count', 'desc')
                ->limit($limit)
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => $topPages,
                'message' => 'Top visited pages retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve top pages: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get daily visitor trend for the past N days
     *
     * @OA\Get(
     *     path="/admin/dashboard/daily-trend/{days}",
     *     summary="Get daily visitor trend",
     *     description="Retrieves daily visitor statistics for the past N days",
     *     tags={"Dashboard Stats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="days",
     *         in="path",
     *         description="Number of past days to retrieve data for (default: 7)",
     *         required=false,
     *         @OA\Schema(type="integer", default=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\AdditionalProperties(
     *                     type="integer",
     *                     example=45
     *                 ),
     *                 example={
     *                     "2023-10-19": 34,
     *                     "2023-10-20": 42,
     *                     "2023-10-21": 56,
     *                     "2023-10-22": 48,
     *                     "2023-10-23": 51,
     *                     "2023-10-24": 62,
     *                     "2023-10-25": 45
     *                 }
     *             ),
     *             @OA\Property(property="message", type="string", example="Daily visitor trend retrieved successfully")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve daily visitor trend: Database connection error")
     *         )
     *     )
     * )
     *
     * @param int $days
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDailyTrend($days = 7)
    {
        try {
            $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
            
            $dailyVisitors = DB::table('visitor_stats')
                ->selectRaw('DATE(visited_at) as date, COUNT(*) as count')
                ->where('visited_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();
            
            // Fill in missing days with zero
            $trendData = [];
            for ($i = 0; $i < $days; $i++) {
                $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
                $trendData[$date] = $dailyVisitors[$date] ?? 0;
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $trendData,
                'message' => 'Daily visitor trend retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve daily visitor trend: ' . $e->getMessage()
            ], 500);
        }
    }
} 
 
 
 
 
 
 
 
 
 
 
 