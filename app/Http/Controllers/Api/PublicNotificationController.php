<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Public Notifications",
 *     description="API Endpoints for Public Notification Access"
 * )
 */
class PublicNotificationController extends Controller
{
    /**
     * Get recent notifications
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/public/notifications/recent",
     *     summary="Get recent notifications",
     *     description="Returns a list of recent sent notifications",
     *     operationId="getRecentNotifications",
     *     tags={"Public Notifications"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of notifications to return",
     *         required=false,
     *         @OA\Schema(type="integer", default=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="New Feature Announcement"),
     *                     @OA\Property(property="message", type="string", example="We have launched a new feature..."),
     *                     @OA\Property(property="icon", type="string", example="/storage/uploads/notifications/icon.png"),
     *                     @OA\Property(property="image", type="string", example="/storage/uploads/notifications/image.jpg"),
     *                     @OA\Property(property="target_url", type="string", example="/new-feature"),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve notifications")
     *         )
     *     )
     * )
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