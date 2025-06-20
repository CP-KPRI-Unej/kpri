<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\PushNotification;
use App\Services\NotificationService;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Admin Notifications",
 *     description="API Endpoints for Push Notification Management"
 * )
 */
class AdminNotificationController extends Controller
{
    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\NotificationService  $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get all notifications with pagination
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/notifications",
     *     summary="Get all notifications",
     *     description="Returns a paginated list of push notifications with filtering and sorting",
     *     operationId="adminGetNotifications",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(type="string", default="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="sort_dir",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"sent", "scheduled", "all"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="New Feature Announcement"),
     *                         @OA\Property(property="message", type="string", example="We have launched a new feature..."),
     *                         @OA\Property(property="icon", type="string", example="/storage/uploads/notifications/icon.png"),
     *                         @OA\Property(property="image", type="string", example="/storage/uploads/notifications/image.jpg"),
     *                         @OA\Property(property="target_url", type="string", example="/new-feature"),
     *                         @OA\Property(property="is_sent", type="boolean", example=true),
     *                         @OA\Property(property="scheduled_at", type="string", format="date-time"),
     *                         @OA\Property(property="user_kpri_id", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="user", type="object")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="last_page_url", type="string"),
     *                 @OA\Property(property="next_page_url", type="string"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="prev_page_url", type="string"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string", example="Notifications retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $search = $request->input('search', '');
            $status = $request->input('status'); // sent, scheduled, all
            
            $query = PushNotification::with('user');
                
            // Apply search filter
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }
            
            // Apply status filter
            if ($status === 'sent') {
                $query->sent();
            } elseif ($status === 'scheduled') {
                $query->scheduled();
            }
            
            // Apply sorting
            $query->orderBy($sortBy, $sortDir);
            
            $notifications = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $notifications,
                'message' => 'Notifications retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve notifications: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get a specific notification
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/notifications/{id}",
     *     summary="Get notification by ID",
     *     description="Returns a specific notification by ID",
     *     operationId="adminGetNotification",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Notification retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        try {
            $notification = PushNotification::with('user')->find($id);
                
            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $notification,
                'message' => 'Notification retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create a new notification
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/notifications",
     *     summary="Create a new notification",
     *     description="Creates a new push notification, can be sent immediately or scheduled",
     *     operationId="adminCreateNotification",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "message"},
     *                 @OA\Property(property="title", type="string", example="New Feature Announcement"),
     *                 @OA\Property(property="message", type="string", example="We have launched a new feature..."),
     *                 @OA\Property(property="icon", type="string", format="binary", description="Icon image (optional)"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Main image (optional)"),
     *                 @OA\Property(property="icon_url", type="string", example="https://example.com/icon.png"),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="target_url", type="string", example="/new-feature"),
     *                 @OA\Property(property="scheduled_at", type="string", format="date-time", example="2023-12-31T12:00:00Z"),
     *                 @OA\Property(property="send_now", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notification created/sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1)
     *             ),
     *             @OA\Property(property="message", type="string", example="Notification sent successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request)
    {
        Log::info('Notification store request received', ['request' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_url' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'send_now' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            Log::warning('Notification validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $user = auth()->user();
            $sendNow = filter_var($request->input('send_now', false), FILTER_VALIDATE_BOOLEAN);
            
            $notification = new PushNotification();
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->target_url = $request->target_url;
            $notification->user_kpri_id = $user->id_user;
            
            // Handle icon upload
            if ($request->hasFile('icon')) {
                $iconFile = $request->file('icon');
                $iconName = time() . '_icon_' . $iconFile->getClientOriginalName();
                $iconPath = $iconFile->storeAs('public/uploads/notifications', $iconName);
                $notification->icon = '/storage/uploads/notifications/' . $iconName;
            } else if ($request->has('icon_url')) {
                $notification->icon = $request->icon_url;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $imageName = time() . '_image_' . $imageFile->getClientOriginalName();
                $imagePath = $imageFile->storeAs('public/uploads/notifications', $imageName);
                $notification->image = '/storage/uploads/notifications/' . $imageName;
            } else if ($request->has('image_url')) {
                $notification->image = $request->image_url;
            }
            
            // Handle scheduling
            if ($sendNow) {
                $notification->is_sent = true;
                $notification->scheduled_at = Carbon::now();
            } else {
                $notification->is_sent = false;
                $notification->scheduled_at = $request->scheduled_at ? Carbon::parse($request->scheduled_at) : null;
            }
            
            $notification->save();
            
            // If send_now is true, we would trigger the notification sending here
            if ($sendNow) {
                $this->notificationService->send($notification->id);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => ['id' => $notification->id],
                'message' => $sendNow ? 'Notification sent successfully' : 'Notification scheduled successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update a notification
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/notifications/{id}",
     *     summary="Update a notification",
     *     description="Updates an existing notification or reschedules it if already sent",
     *     operationId="adminUpdateNotification",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="HTTP method override",
     *         required=true,
     *         @OA\Schema(type="string", default="PUT")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Updated Feature Announcement"),
     *                 @OA\Property(property="message", type="string", example="We have improved our new feature..."),
     *                 @OA\Property(property="icon", type="string", format="binary"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="icon_url", type="string", example="https://example.com/icon.png"),
     *                 @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg"),
     *                 @OA\Property(property="target_url", type="string", example="/new-feature"),
     *                 @OA\Property(property="scheduled_at", type="string", format="date-time"),
     *                 @OA\Property(property="send_now", type="boolean", example=false),
     *                 @OA\Property(property="reschedule", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Notification updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cannot update a sent notification without reschedule option"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        Log::info('Notification update request received', ['id' => $id, 'request' => $request->all()]);
        
        // Check if notification exists
        $notification = PushNotification::find($id);
        
        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found'
            ], 404);
        }
        
        $sendNow = filter_var($request->input('send_now', false), FILTER_VALIDATE_BOOLEAN);
        $reschedule = filter_var($request->input('reschedule', false), FILTER_VALIDATE_BOOLEAN);
        
        // Handle sent notifications - can only be rescheduled, not edited
        if ($notification->is_sent && !$reschedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot update a notification that has already been sent. Use reschedule option to send it again.'
            ], 400);
        }
        
        // For rescheduling, only validate the scheduled_at field
        if ($reschedule) {
            $validator = Validator::make($request->all(), [
                'scheduled_at' => 'required|date'
            ]);
            
            if ($validator->fails()) {
                Log::warning('Notification reschedule validation failed', ['id' => $id, 'errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Only update scheduling information for reschedule
            // Preserve all other fields (title, message, images, etc.)
            $notification->is_sent = false;
            $notification->scheduled_at = Carbon::parse($request->scheduled_at);
            $notification->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Notification rescheduled successfully'
            ]);
        }
        
        // For unsent notifications, validate all fields
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_url' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'send_now' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            Log::warning('Notification update validation failed', ['id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->target_url = $request->target_url;
            
            // Handle icon upload
            if ($request->hasFile('icon')) {
                // Remove old icon file if exists
                if ($notification->icon && strpos($notification->icon, '/storage/uploads/notifications/') === 0) {
                    $oldIconPath = str_replace('/storage/', 'public/', $notification->icon);
                    if (file_exists(storage_path('app/' . $oldIconPath))) {
                        unlink(storage_path('app/' . $oldIconPath));
                    }
                }
                
                $iconFile = $request->file('icon');
                $iconName = time() . '_icon_' . $iconFile->getClientOriginalName();
                $iconPath = $iconFile->storeAs('public/uploads/notifications', $iconName);
                $notification->icon = '/storage/uploads/notifications/' . $iconName;
            } else if ($request->has('icon_url') && !empty($request->icon_url)) {
                $notification->icon = $request->icon_url;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Remove old image file if exists
                if ($notification->image && strpos($notification->image, '/storage/uploads/notifications/') === 0) {
                    $oldImagePath = str_replace('/storage/', 'public/', $notification->image);
                    if (file_exists(storage_path('app/' . $oldImagePath))) {
                        unlink(storage_path('app/' . $oldImagePath));
                    }
                }
                
                $imageFile = $request->file('image');
                $imageName = time() . '_image_' . $imageFile->getClientOriginalName();
                $imagePath = $imageFile->storeAs('public/uploads/notifications', $imageName);
                $notification->image = '/storage/uploads/notifications/' . $imageName;
            } else if ($request->has('image_url') && !empty($request->image_url)) {
                $notification->image = $request->image_url;
            }
            
            // Handle scheduling
            if ($sendNow) {
                $notification->is_sent = true;
                $notification->scheduled_at = Carbon::now();
            } else {
                $notification->scheduled_at = $request->scheduled_at ? Carbon::parse($request->scheduled_at) : null;
            }
            
            $notification->save();
            
            // If send_now is true, we would trigger the notification sending here
            if ($sendNow) {
                $this->notificationService->send($notification->id);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => $sendNow ? 'Notification sent successfully' : 'Notification updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a notification
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Delete(
     *     path="/admin/notifications/{id}",
     *     summary="Delete a notification",
     *     description="Deletes a notification by ID",
     *     operationId="adminDeleteNotification",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Notification deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        try {
            // Check if notification exists
            $notification = PushNotification::find($id);
            
            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }
            
            // Allow deletion of sent notifications
            $notification->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send a scheduled notification immediately
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/notifications/{id}/send-now",
     *     summary="Send notification immediately",
     *     description="Sends a notification immediately, regardless of its scheduled time",
     *     operationId="adminSendNotificationNow",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Notification sent successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Notification not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function sendNow($id)
    {
        try {
            // Check if notification exists and is not sent yet
            $notification = PushNotification::find($id);
            
            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }
            
            // Send the notification
            $success = $this->notificationService->send($notification->id);
            
            if (!$success) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send notification'
                ], 500);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Notification sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get notification statistics
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Get(
     *     path="/admin/notifications/stats",
     *     summary="Get notification statistics",
     *     description="Returns notification counts and recent/upcoming notifications",
     *     operationId="adminGetNotificationStats",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="sent", type="integer", example=80),
     *                 @OA\Property(property="scheduled", type="integer", example=20),
     *                 @OA\Property(property="recent", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="upcoming", type="array", @OA\Items(type="object"))
     *             ),
     *             @OA\Property(property="message", type="string", example="Notification statistics retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function stats()
    {
        try {
            $totalCount = PushNotification::count();
            $sentCount = PushNotification::sent()->count();
            $scheduledCount = PushNotification::scheduled()->count();
            
            $recentNotifications = PushNotification::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $upcomingNotifications = PushNotification::pending()
                ->orderBy('scheduled_at', 'asc')
                ->limit(5)
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'total' => $totalCount,
                    'sent' => $sentCount,
                    'scheduled' => $scheduledCount,
                    'recent' => $recentNotifications,
                    'upcoming' => $upcomingNotifications
                ],
                'message' => 'Notification statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve notification statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Process all due notifications
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\Post(
     *     path="/admin/notifications/process-due",
     *     summary="Process due notifications",
     *     description="Processes all scheduled notifications that are due to be sent",
     *     operationId="adminProcessDueNotifications",
     *     tags={"Admin Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Notifications processed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="count", type="integer", example=5)
     *             ),
     *             @OA\Property(property="message", type="string", example="5 notifications processed successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function processDue()
    {
        try {
            $count = $this->notificationService->processDueNotifications();
            
            return response()->json([
                'status' => 'success',
                'data' => ['count' => $count],
                'message' => "{$count} notifications processed successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process due notifications: ' . $e->getMessage()
            ], 500);
        }
    }
} 