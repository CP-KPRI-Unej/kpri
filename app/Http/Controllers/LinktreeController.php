<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Linktree;
use App\Models\Link;
use App\Models\VisitorStat;

class LinktreeController extends Controller
{
    /**
     * Display the public linktree page
     */
    public function index(Request $request)
    {
        // Track visitor
        $this->trackVisitor($request);
        
        // Get the first linktree (we can enhance this to look up by a specific username/slug later)
        $linktree = Linktree::first();
        
        if (!$linktree) {
            // If no linktree exists, show a default message
            return view('linktree.not-found');
        }
        
        // Get all links for this linktree
        $links = Link::where('page_id', $linktree->id)
                    ->orderBy('position', 'asc')
                    ->get();
        
        return view('linktree.index', compact('linktree', 'links'));
    }
    
    /**
     * Track visitor statistics
     */
    private function trackVisitor(Request $request)
    {
        VisitorStat::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'page_visited' => '/'
        ]);
    }
} 