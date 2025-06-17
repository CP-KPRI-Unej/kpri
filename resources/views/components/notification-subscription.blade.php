<div class="notification-subscription" x-data="notificationSubscription()">
    <template x-if="supported">
        <div>
            <template x-if="!subscribed">
                <button @click="subscribe" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm font-medium transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Aktifkan Notifikasi
                </button>
            </template>
            <template x-if="subscribed">
                <button @click="unsubscribe" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Nonaktifkan Notifikasi
                </button>
            </template>
        </div>
    </template>
</div>

<script>
    function notificationSubscription() {
        return {
            supported: false,
            subscribed: false,
            
            async init() {
                if (!window.pushNotificationHandler) {
                    console.error('Push notification handler not found');
                    return;
                }
                
                const initialized = await window.pushNotificationHandler.init();
                this.supported = initialized;
                
                if (initialized) {
                    this.subscribed = await window.pushNotificationHandler.checkSubscription();
                }
            },
            
            async subscribe() {
                if (!window.pushNotificationHandler) return;
                
                try {
                    const result = await window.pushNotificationHandler.subscribe();
                    if (result) {
                        this.subscribed = true;
                        this.showToast('Notifikasi berhasil diaktifkan');
                    }
                } catch (error) {
                    console.error('Failed to subscribe:', error);
                    this.showToast('Gagal mengaktifkan notifikasi', true);
                }
            },
            
            async unsubscribe() {
                if (!window.pushNotificationHandler) return;
                
                try {
                    const result = await window.pushNotificationHandler.unsubscribe();
                    if (result) {
                        this.subscribed = false;
                        this.showToast('Notifikasi berhasil dinonaktifkan');
                    }
                } catch (error) {
                    console.error('Failed to unsubscribe:', error);
                    this.showToast('Gagal menonaktifkan notifikasi', true);
                }
            },
            
            showToast(message, isError = false) {
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 ${isError ? 'bg-red-500' : 'bg-green-500'} text-white px-4 py-2 rounded-md shadow-lg z-50 flex items-center`;
                
                const icon = document.createElement('span');
                icon.className = 'mr-2';
                icon.innerHTML = isError 
                    ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                
                toast.appendChild(icon);
                toast.appendChild(document.createTextNode(message));
                
                // Add to document
                document.body.appendChild(toast);
                
                // Remove after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }
    }
</script> 