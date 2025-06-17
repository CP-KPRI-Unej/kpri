<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\PushNotification;
use App\Services\NotificationService;
use Carbon\Carbon;

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
     */
    public function update(Request $request, $id)
    {
        Log::info('Notification update request received', ['id' => $id, 'request' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_url' => 'nullable|string|max:255',
            'scheduled_at' => 'nullable|date',
            'send_now' => 'nullable|boolean',
            'reschedule' => 'nullable|boolean'
        ]);
        
        if ($validator->fails()) {
            Log::warning('Notification update validation failed', ['id' => $id, 'errors' => $validator->errors()->toArray()]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
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
            
            // Allow rescheduling of sent notifications
            if ($notification->is_sent && $reschedule) {
                $notification->is_sent = false;
            } else if ($notification->is_sent && !$reschedule) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot update a notification that has already been sent. Use reschedule option to send it again.'
                ], 400);
            }
            
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
            } else if ($request->has('icon_url')) {
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
            } else if ($request->has('image_url')) {
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
                'message' => $sendNow ? 'Notification sent successfully' : ($reschedule ? 'Notification rescheduled successfully' : 'Notification updated successfully')
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