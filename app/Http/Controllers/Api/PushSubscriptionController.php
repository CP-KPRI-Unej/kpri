<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscriptionGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Push Notifications",
 *     description="API Endpoints for managing web push notification subscriptions"
 * )
 */
class PushSubscriptionController extends Controller
{
    /**
     * Get the VAPID public key
     *
     * @OA\Get(
     *     path="/api/push/key",
     *     summary="Get VAPID public key",
     *     description="Retrieves the VAPID public key needed for push notification subscription",
     *     tags={"Push Notifications"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="vapidPublicKey", type="string", example="BNbKwE3_nEf95bWh-RiVYAmPFQTULThKo8IpGzpJQe1hXse2HZlrPwJiFMwZhLzvkgFolMFVGGqPh5SRPX3W3Zk")
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicKey()
    {
        return response()->json([
            'success' => true,
            'vapidPublicKey' => env('VAPID_PUBLIC_KEY')
        ]);
    }

    /**
     * Store a new push subscription
     *
     * @OA\Post(
     *     path="/api/push/subscribe",
     *     summary="Subscribe to push notifications",
     *     description="Stores a new push notification subscription or updates an existing one",
     *     tags={"Push Notifications"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"endpoint", "keys"},
     *             @OA\Property(property="endpoint", type="string", example="https://fcm.googleapis.com/fcm/send/f1LsYWaEh_8:APA91bE...", description="Push subscription endpoint URL"),
     *             @OA\Property(
     *                 property="keys",
     *                 type="object",
     *                 required={"auth", "p256dh"},
     *                 @OA\Property(property="auth", type="string", example="5VVaGl3BNxRdL0yN-TtKqQ", description="Auth key"),
     *                 @OA\Property(property="p256dh", type="string", example="BKHcfZqjgqfH2GWAHJVtE1_zFoW-WIr8QwVTZKgKXQgJQFHD_NyS-38fsDJTKCQhPysi0ocUPB_7QGtqmNPvuJo", description="P256DH key")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Subscription saved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="endpoint",
     *                     type="array",
     *                     @OA\Items(type="string", example="The endpoint field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to save subscription")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'endpoint' => 'required|string|max:500',
                'keys.auth' => 'required|string',
                'keys.p256dh' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if subscription already exists
            $subscription = PushSubscriptionGuest::where('endpoint', $request->endpoint)->first();
            
            if ($subscription) {
                // Update existing subscription
                $subscription->keys = $request->keys;
                $subscription->user_agent = $request->header('User-Agent');
                $subscription->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription updated successfully'
                ]);
            }
            
            // Create new subscription
            PushSubscriptionGuest::create([
                'endpoint' => $request->endpoint,
                'keys' => $request->keys,
                'user_agent' => $request->header('User-Agent')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save push subscription: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save subscription'
            ], 500);
        }
    }

    /**
     * Delete a push subscription
     *
     * @OA\Delete(
     *     path="/api/push/unsubscribe",
     *     summary="Unsubscribe from push notifications",
     *     description="Deletes an existing push notification subscription",
     *     tags={"Push Notifications"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"endpoint"},
     *             @OA\Property(property="endpoint", type="string", example="https://fcm.googleapis.com/fcm/send/f1LsYWaEh_8:APA91bE...", description="Push subscription endpoint URL")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Subscription deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Subscription not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="endpoint",
     *                     type="array",
     *                     @OA\Items(type="string", example="The endpoint field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete subscription")
     *         )
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'endpoint' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find and delete the subscription
            $deleted = PushSubscriptionGuest::where('endpoint', $request->endpoint)->delete();
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete push subscription: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscription'
            ], 500);
        }
    }
} 