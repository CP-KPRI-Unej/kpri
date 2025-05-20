<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorStat extends Model
{
    use HasFactory;

    protected $table = 'visitor_stats';
    protected $fillable = ['ip_address', 'user_agent', 'page_visited'];
    
    public $timestamps = false;
    
    const CREATED_AT = 'visited_at';
    const UPDATED_AT = null;
    
    // Scope for today's visits
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }
    
    // Scope for this month's visits
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
                    ->whereYear('visited_at', now()->year);
    }
    
    // Scope for visits by year grouped by month
    public function scopeMonthlyVisitsInYear($query, $year = null)
    {
        $year = $year ?? now()->year;
        return $query->selectRaw('MONTH(visited_at) as month, COUNT(*) as count')
                    ->whereYear('visited_at', $year)
                    ->groupBy('month')
                    ->orderBy('month');
    }
} 