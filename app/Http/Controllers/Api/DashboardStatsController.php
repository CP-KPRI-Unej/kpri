<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardStatsController extends Controller
{
    /**
     * Get summary statistics for dashboard
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
 
 
 
 
 
 
 
 
 
 
 