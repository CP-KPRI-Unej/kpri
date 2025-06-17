<?php

namespace App\Services;

use App\Models\PushNotification;
use App\Models\PushSubscriptionGuest;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class NotificationService
{
    /**
     * Send a notification
     * 
     * @param int $notificationId
     * @return bool
     */
    public function send($notificationId)
    {
        try {
            $notification = PushNotification::find($notificationId);
            
            if (!$notification) {
                Log::error("Notification not found: {$notificationId}");
                return false;
            }
            
            // Mark as sent
            $notification->is_sent = true;
            $notification->save();
            
            // Get all subscriptions
            $subscriptions = $this->getSubscriptions();
            
            if (count($subscriptions) === 0) {
                Log::info("No active subscriptions found");
                return true;
            }
            
            // Send to each subscription
            $successCount = 0;
            foreach ($subscriptions as $subscription) {
                if ($this->sendToSubscription($notification, $subscription)) {
                    $successCount++;
                }
            }
            
            Log::info("Notification sent successfully to {$successCount} out of " . count($subscriptions) . " subscriptions");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send notification: {$e->getMessage()}");
            return false;
        }
    }
    
    /**
     * Get all active subscriptions
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSubscriptions()
    {
        // Get all guest subscriptions
        return PushSubscriptionGuest::all();
    }
    
    /**
     * Send notification to a specific subscription
     * 
     * @param PushNotification $notification
     * @param PushSubscriptionGuest $subscription
     * @return bool
     */
    private function sendToSubscription($notification, $subscription)
    {
        try {
            // Prepare the subscription object for web-push
            $subscriptionObject = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => $subscription->keys
            ]);
            
            // Prepare the notification payload
            $payload = json_encode([
                'title' => $notification->title,
                'body' => $notification->message,
                'icon' => $notification->icon,
                'image' => $notification->image,
                'data' => [
                    'url' => $notification->target_url
                ]
            ]);
            
            // Get VAPID keys from environment
            $auth = [
                'VAPID' => [
                    'subject' => env('VAPID_SUBJECT'),
                    'publicKey' => env('VAPID_PUBLIC_KEY'),
                    'privateKey' => env('VAPID_PRIVATE_KEY'),
                ]
            ];
            
            // Create WebPush instance
            $webPush = new WebPush($auth);
            
            // Send the notification
            $report = $webPush->sendOneNotification($subscriptionObject, $payload);
            
            if ($report->isSuccess()) {
                Log::info("Push notification sent successfully to {$subscription->endpoint}");
                return true;
            } else {
                Log::warning("Push notification failed for {$subscription->endpoint}: {$report->getReason()}");
                
                // If the subscription is expired or invalid, remove it
                if ($report->isSubscriptionExpired()) {
                    Log::info("Removing expired subscription: {$subscription->endpoint}");
                    $subscription->delete();
                }
                
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send notification to subscription: {$e->getMessage()}");
            return false;
        }
    }
    
    /**
     * Process due notifications
     * 
     * @return int Number of notifications processed
     */
    public function processDueNotifications()
    {
        $count = 0;
        
        try {
            // Get all due notifications
            $dueNotifications = PushNotification::due()->get();
            
            foreach ($dueNotifications as $notification) {
                if ($this->send($notification->id)) {
                    $count++;
                }
            }
            
            Log::info("Processed {$count} due notifications");
            return $count;
        } catch (\Exception $e) {
            Log::error("Failed to process due notifications: {$e->getMessage()}");
            return $count;
        }
    }
} 