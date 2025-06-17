// Service Worker for Push Notifications

self.addEventListener('push', function(event) {
    if (event.data) {
        try {
            const data = event.data.json();
            
            const options = {
                body: data.body || 'New notification',
                icon: data.icon || '/images/logo.png',
                badge: '/images/logo.png',
                data: {
                    url: data.data?.url || '/'
                },
                // Add action buttons
                actions: [
                    {
                        action: 'open',
                        title: 'Buka'
                    },
                    {
                        action: 'dismiss',
                        title: 'Tutup'
                    }
                ]
            };
            
            if (data.image) {
                options.image = data.image;
            }
            
            event.waitUntil(
                self.registration.showNotification(data.title || 'KPRI Notification', options)
            );
        } catch (error) {
            console.error('Error showing notification:', error);
        }
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    // Handle action button clicks
    if (event.action === 'open') {
        const url = event.notification.data.url || '/';
        event.waitUntil(
            clients.openWindow(url)
        );
        return;
    }
    
    if (event.action === 'dismiss') {
        // Just close the notification, which is already done above
        return;
    }
    
    // Default behavior when clicking on the notification body
    const url = event.notification.data.url || '/';
    
    event.waitUntil(
        clients.matchAll({type: 'window'}).then(function(clientList) {
            // Check if there's already a window open
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // Open a new window if none are open
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
}); 