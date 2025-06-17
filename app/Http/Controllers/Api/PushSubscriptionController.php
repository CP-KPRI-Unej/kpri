<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscriptionGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    /**
     * Get the VAPID public key
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