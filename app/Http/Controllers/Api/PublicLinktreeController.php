<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PublicLinktreeController extends Controller
{
    /**
     * Get the linktree data for public display
     * 
     * @param int|null $id
     * @return \Illuminate\Http\JsonResponse
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
