<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitorStat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get total visitors
        $totalVisitors = VisitorStat::count();
        
        // Get today's visitors
        $todayVisitors = VisitorStat::today()->count();
        
        // Get this month's visitors
        $monthVisitors = VisitorStat::thisMonth()->count();
        
        // Get monthly visits for current year
        $monthlyVisits = VisitorStat::monthlyVisitsInYear()->get();
        
        // Get recent visitors
        $recentVisitors = VisitorStat::orderBy('visited_at', 'desc')
                                     ->take(10)
                                     ->get()
                                     ->map(function($visitor) {
                                         return [
                                             'ip_address' => $this->anonymizeIp($visitor->ip_address),
                                             'page_visited' => $visitor->page_visited === '/' ? 'Linktree Homepage' : $visitor->page_visited,
                                             'visited_at' => $visitor->visited_at,
                                             'time_ago' => Carbon::parse($visitor->visited_at)->diffForHumans()
                                         ];
                                     });
        
        // Convert to an array with all months (1-12)
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = 0;
        }
        
        foreach ($monthlyVisits as $visit) {
            $chartData[$visit->month] = $visit->count;
        }
        
        return view('admin.dashboard', compact(
            'totalVisitors', 
            'todayVisitors', 
            'monthVisitors', 
            'chartData',
            'recentVisitors'
        ));
    }
    
    /**
     * Anonymize IP address for privacy
     *
     * @param string $ip
     * @return string
     */
    private function anonymizeIp($ip)
    {
        if (empty($ip)) {
            return 'Unknown';
        }
        
        // For IPv4
        if (strpos($ip, '.') !== false) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                // Keep first two octets, mask the rest
                return $parts[0] . '.' . $parts[1] . '.x.x';
            }
        }
        
        // For IPv6 or other formats, show only partial
        return substr($ip, 0, 8) . '...';
    }
} 