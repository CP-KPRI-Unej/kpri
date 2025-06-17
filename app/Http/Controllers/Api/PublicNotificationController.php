<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;

class PublicNotificationController extends Controller
{
    /**
     * Get recent notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecent(Request $request)
    {
        try {
            $limit = $request->input('limit', 5);
            
            $notifications = PushNotification::where('is_sent', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'message', 'icon', 'image', 'target_url', 'created_at']);
            
            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications'
            ], 500);
        }
    }
} 