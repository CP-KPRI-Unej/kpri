# Push Notification System

This document explains how to use the push notification system in the KPRI application.

## Overview

The push notification system allows sending notifications to users even when they are not actively using the application. It uses the Web Push API and service workers to deliver notifications to subscribed users.

## Setup

1. Generate VAPID keys:

```bash
php artisan notifications:generate-keys
```

2. Add the generated keys to your `.env` file:

```
VAPID_PUBLIC_KEY="your_public_key"
VAPID_PRIVATE_KEY="your_private_key"
VAPID_SUBJECT="mailto:your_email@example.com"
```

3. Make sure the service worker file (`public/sw.js`) is accessible from the root of your website.

4. Ensure the push notification script (`public/js/push-notifications.js`) is included in your layout.

## Database Tables

The system uses two main tables:

1. `push_notifications` - Stores notification content and scheduling information
2. `push_subscriptions_guest` - Stores user subscription information for guests

## Creating Notifications

Notifications can be created through the admin panel or programmatically:

```php
use App\Models\PushNotification;

$notification = new PushNotification();
$notification->title = 'Notification Title';
$notification->message = 'Notification Message';
$notification->icon = '/path/to/icon.png'; // Optional
$notification->image = '/path/to/image.jpg'; // Optional
$notification->target_url = '/target-page'; // Optional URL to open when clicked
$notification->scheduled_at = now()->addHours(2); // Schedule for later
$notification->user_kpri_id = auth()->user()->id_user;
$notification->save();
```

## Sending Notifications

Notifications can be sent immediately or scheduled for later:

### Sending Immediately

```php
use App\Services\NotificationService;

$notificationService = app(NotificationService::class);
$notificationService->send($notification->id);
```

### Scheduled Notifications

Scheduled notifications are processed automatically by the scheduler. Make sure your scheduler is running:

```bash
php artisan schedule:run
```

Or manually process due notifications:

```bash
php artisan notifications:process
```

## User Subscription

Users can subscribe to notifications using the provided component:

```blade
@include('components.notification-subscription')
```

This component provides buttons for users to subscribe/unsubscribe from notifications.

## API Endpoints

The system provides the following API endpoints:

- `GET /api/push/key` - Get the VAPID public key
- `POST /api/push/subscribe` - Subscribe to push notifications
- `POST /api/push/unsubscribe` - Unsubscribe from push notifications
- `GET /api/push/notifications/recent` - Get recent notifications

## Testing

To test the system, create a notification and send it:

```php
$notification = PushNotification::create([
    'title' => 'Test Notification',
    'message' => 'This is a test notification',
    'scheduled_at' => now(),
]);

$notificationService = app(NotificationService::class);
$notificationService->send($notification->id);
```

## Troubleshooting

- Make sure the service worker is registered correctly
- Check browser console for errors
- Ensure VAPID keys are correctly set in the `.env` file
- Verify that the user has granted notification permissions
- Check that the subscription is saved correctly in the database 