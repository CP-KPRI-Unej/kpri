/**
 * Push Notification Handler for KPRI
 */

class PushNotificationHandler {
    constructor() {
        this.swRegistration = null;
        this.isSubscribed = false;
        this.vapidPublicKey = null;
    }

    /**
     * Initialize the push notification handler
     */
    async init() {
        try {
            // Check if service workers and push messaging are supported
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                console.warn('Push notifications are not supported in this browser');
                return false;
            }

            // Register service worker
            this.swRegistration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered successfully');

            // Get VAPID public key from server
            const response = await fetch('/api/push/key');
            const data = await response.json();
            
            if (!data.success || !data.vapidPublicKey) {
                console.error('Failed to get VAPID public key');
                return false;
            }
            
            this.vapidPublicKey = data.vapidPublicKey;

            // Check subscription status
            const subscription = await this.swRegistration.pushManager.getSubscription();
            this.isSubscribed = subscription !== null;
            
            return true;
        } catch (error) {
            console.error('Error initializing push notifications:', error);
            return false;
        }
    }

    /**
     * Subscribe to push notifications
     */
    async subscribe() {
        try {
            if (!this.swRegistration || !this.vapidPublicKey) {
                console.error('Service worker not registered or VAPID key not available');
                return false;
            }

            // Convert VAPID key to UInt8Array
            const applicationServerKey = this.urlB64ToUint8Array(this.vapidPublicKey);

            // Subscribe
            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            });

            console.log('User is subscribed:', subscription);

            // Send subscription to server
            const response = await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(subscription)
            });

            const result = await response.json();
            
            if (result.success) {
                this.isSubscribed = true;
                return true;
            } else {
                console.error('Failed to save subscription on server');
                return false;
            }
        } catch (error) {
            console.error('Error subscribing to push notifications:', error);
            return false;
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    async unsubscribe() {
        try {
            const subscription = await this.swRegistration.pushManager.getSubscription();
            
            if (!subscription) {
                console.log('User is not subscribed');
                return true;
            }

            // Send unsubscribe request to server
            const response = await fetch('/api/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint
                })
            });

            // Unsubscribe locally
            await subscription.unsubscribe();
            
            this.isSubscribed = false;
            console.log('User is unsubscribed');
            return true;
        } catch (error) {
            console.error('Error unsubscribing from push notifications:', error);
            return false;
        }
    }

    /**
     * Check if the user is subscribed to push notifications
     */
    async checkSubscription() {
        if (!this.swRegistration) {
            return false;
        }

        const subscription = await this.swRegistration.pushManager.getSubscription();
        this.isSubscribed = subscription !== null;
        return this.isSubscribed;
    }

    /**
     * Convert base64 string to Uint8Array
     * @param {string} base64String - Base64 encoded string
     * @returns {Uint8Array} - Uint8Array
     */
    urlB64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        
        return outputArray;
    }
}

// Create global instance
window.pushNotificationHandler = new PushNotificationHandler(); 