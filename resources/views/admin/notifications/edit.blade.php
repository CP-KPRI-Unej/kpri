@extends('admin.layouts.app')

@section('title', 'Edit Notifikasi')

@section('styles')
<style>
    .preview-container {
        border: 1px dashed #ccc;
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: #f9fafb;
    }
    .dark .preview-container {
        background-color: #1f2937;
        border-color: #4b5563;
    }
    .notification-preview {
        max-width: 400px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .notification-preview img.preview-image {
        width: 100%;
        height: auto;
        max-height: 200px;
        object-fit: cover;
    }
    .notification-preview img.preview-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .form-disabled {
        opacity: 0.6;
        pointer-events: none;
    }
    /* Input field styles with stroke */
    .input-stroke {
        border: 2px solid #e5e7eb;
        transition: border-color 0.2s ease;
    }
    .input-stroke:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
    }
    .dark .input-stroke {
        border-color: #4b5563;
    }
    .dark .input-stroke:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Notifikasi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ubah detail notifikasi di bawah ini.</p>
        </div>
        <a href="{{ route('admin.notification.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="loading-spinner" class="flex justify-center items-center py-10">
        <svg class="animate-spin h-10 w-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <div id="content-area" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form id="edit-notification-form" data-notification-id="{{ $id }}" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 input-stroke">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="title-error"></p>
                </div>
                
                <!-- Message -->
                <div class="md:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesan <span class="text-red-500">*</span></label>
                    <textarea name="message" id="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 input-stroke"></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="message-error"></p>
                </div>

                <!-- Target URL -->
                <div>
                    <label for="target_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Target URL (Opsional)</label>
                    <input type="url" name="target_url" id="target_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 input-stroke" placeholder="https://example.com">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="target_url-error"></p>
                </div>

                <!-- Icon Upload -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Icon (Opsional)</label>
                    <input type="file" name="icon" id="icon" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:file:bg-gray-700 dark:file:text-gray-300 input-stroke">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF, SVG. Maks: 2MB</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="icon-error"></p>
                    
                    <!-- Current icon display -->
                    <div id="current-icon-container" class="mt-2 hidden">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Icon saat ini:</p>
                        <div class="flex items-center mt-1">
                            <img id="current-icon" src="" alt="Current Icon" class="h-8 w-8 object-cover rounded-full">
                            <span id="current-icon-name" class="ml-2 text-xs text-gray-500 dark:text-gray-400"></span>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gambar (Opsional)</label>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 dark:file:bg-gray-700 dark:file:text-gray-300 input-stroke">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF, SVG. Maks: 2MB</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="image-error"></p>
                    
                    <!-- Current image display -->
                    <div id="current-image-container" class="mt-2 hidden">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Gambar saat ini:</p>
                        <div class="flex items-center mt-1">
                            <img id="current-image" src="" alt="Current Image" class="h-16 w-auto object-cover rounded">
                            <span id="current-image-name" class="ml-2 text-xs text-gray-500 dark:text-gray-400"></span>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="md:col-span-2 mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
                    <div class="preview-container">
                        <div class="notification-preview bg-white dark:bg-gray-700">
                            <div id="preview-image-container" class="hidden">
                                <img id="preview-image" src="" alt="Preview Image" class="preview-image">
                            </div>
                            <div class="p-4">
                                <div class="flex items-center">
                                    <div id="preview-icon-container" class="hidden">
                                        <img id="preview-icon" src="" alt="Icon" class="preview-icon">
                                    </div>
                                    <div>
                                        <h3 id="preview-title" class="font-bold text-gray-900 dark:text-white">Title Preview</h3>
                                        <p id="preview-message" class="text-sm text-gray-600 dark:text-gray-300 mt-1">Message preview will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Options -->
                <div class="md:col-span-2 border-t pt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="send_now" name="send_now" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="send_now" class="font-medium text-gray-700 dark:text-gray-300">Kirim Sekarang</label>
                            <p class="text-gray-500 dark:text-gray-400">Jika dicentang, notifikasi akan dikirim segera setelah disimpan.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start mt-4" id="reschedule-container" style="display: none;">
                        <div class="flex items-center h-5">
                            <input id="reschedule" name="reschedule" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="reschedule" class="font-medium text-gray-700 dark:text-gray-300">Jadwalkan Ulang</label>
                            <p class="text-gray-500 dark:text-gray-400">Aktifkan untuk mengirim ulang notifikasi ini dengan jadwal baru.</p>
                        </div>
                    </div>
                </div>

                <!-- Scheduled At -->
                <div id="schedule-container">
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jadwalkan Untuk <span class="text-red-500 scheduled-required">*</span></label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 input-stroke">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="scheduled_at-error"></p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.notification.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="submit" id="save-button" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
                    <span id="save-button-text">Update Notifikasi</span>
                    <svg id="loading-spinner" class="animate-spin -mr-1 ml-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
            <div id="form-error" class="mt-4 text-sm text-red-600"></div>
            <div id="notification-status" class="mt-4 text-sm font-medium"></div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('access_token');
    const form = document.getElementById('edit-notification-form');
    const notificationId = form.dataset.notificationId;
    const loadingSpinner = document.getElementById('loading-spinner');
    const contentArea = document.getElementById('content-area');

    if (!token || !notificationId) {
        window.location.href = '/admin/login';
        return;
    }

    const sendNowCheckbox = document.getElementById('send_now');
    const scheduleContainer = document.getElementById('schedule-container');
    const scheduledAtInput = document.getElementById('scheduled_at');
    const saveButton = document.getElementById('save-button');
    const saveButtonText = document.getElementById('save-button-text');
    const formSpinner = document.getElementById('loading-spinner');
    const formError = document.getElementById('form-error');
    const notificationStatus = document.getElementById('notification-status');
    
    // Preview elements
    const titleInput = document.getElementById('title');
    const messageInput = document.getElementById('message');
    const iconInput = document.getElementById('icon');
    const imageInput = document.getElementById('image');
    const previewTitle = document.getElementById('preview-title');
    const previewMessage = document.getElementById('preview-message');
    const previewIcon = document.getElementById('preview-icon');
    const previewIconContainer = document.getElementById('preview-icon-container');
    const previewImage = document.getElementById('preview-image');
    const previewImageContainer = document.getElementById('preview-image-container');
    
    // Current media elements
    const currentIconContainer = document.getElementById('current-icon-container');
    const currentIcon = document.getElementById('current-icon');
    const currentIconName = document.getElementById('current-icon-name');
    const currentImageContainer = document.getElementById('current-image-container');
    const currentImage = document.getElementById('current-image');
    const currentImageName = document.getElementById('current-image-name');

    function toggleSchedule(isSendNow) {
        if (isSendNow) {
            scheduleContainer.style.display = 'none';
            scheduledAtInput.required = false;
            document.querySelectorAll('.scheduled-required').forEach(el => el.style.display = 'none');
        } else {
            scheduleContainer.style.display = 'block';
            scheduledAtInput.required = true;
            document.querySelectorAll('.scheduled-required').forEach(el => el.style.display = 'inline');
        }
    }
    
    function updatePreview() {
        // Update title and message
        previewTitle.textContent = titleInput.value || 'Title Preview';
        previewMessage.textContent = messageInput.value || 'Message preview will appear here';
        
        // Update icon
        updateIconPreview();
        
        // Update image
        updateImagePreview();
    }
    
    function updateIconPreview() {
        // Check if file is selected
        if (iconInput.files && iconInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewIcon.src = e.target.result;
                previewIconContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(iconInput.files[0]);
        } 
        // If there's a current icon
        else if (currentIcon.src && currentIcon.src !== window.location.href) {
            // Keep using the current icon
            previewIconContainer.classList.remove('hidden');
        } else {
            previewIconContainer.classList.add('hidden');
        }
    }
    
    function updateImagePreview() {
        // Check if file is selected
        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImageContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(imageInput.files[0]);
        } 
        // If there's a current image
        else if (currentImage.src && currentImage.src !== window.location.href) {
            // Keep using the current image
            previewImageContainer.classList.remove('hidden');
        } else {
            previewImageContainer.classList.add('hidden');
        }
    }
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    function showAlert(message, type = 'success') {
        const alertId = type === 'success' ? 'alert-success' : 'alert-error';
        const alertEl = document.getElementById(alertId);
        const messageEl = alertId === 'alert-success' ? document.getElementById('success-message') : document.getElementById('error-message');
        
        if (alertEl && messageEl) {
            messageEl.textContent = message;
            alertEl.classList.remove('hidden');
            
            setTimeout(() => {
                alertEl.classList.add('hidden');
            }, 5000);
        }
    }
    
    function hideAlert(alertId) {
        const alertEl = document.getElementById(alertId);
        if (alertEl) {
            alertEl.classList.add('hidden');
        }
    }

    function populateForm(notification) {
        // Populate form fields
        titleInput.value = notification.title || '';
        messageInput.value = notification.message || '';
        document.getElementById('target_url').value = notification.target_url || '';
        
        // Set scheduled date if available
        if (notification.scheduled_at) {
            // Format to YYYY-MM-DDTHH:mm
            try {
                const date = new Date(notification.scheduled_at);
                if (!isNaN(date.getTime())) {
                    const formattedDate = date.toISOString().slice(0, 16);
                    scheduledAtInput.value = formattedDate;
                }
            } catch (e) {
                console.error('Date formatting error:', e);
            }
        }
        
        // Show current icon if exists
        if (notification.icon) {
            // Don't set the file input (can't set value for security reasons)
            // But show the current icon
            currentIcon.src = notification.icon;
            currentIconName.textContent = getFileNameFromPath(notification.icon);
            currentIconContainer.classList.remove('hidden');
            
            // Update preview
            previewIcon.src = notification.icon;
            previewIconContainer.classList.remove('hidden');
        }
        
        // Show current image if exists
        if (notification.image) {
            // Don't set the file input (can't set value for security reasons)
            // But show the current image
            currentImage.src = notification.image;
            currentImageName.textContent = getFileNameFromPath(notification.image);
            currentImageContainer.classList.remove('hidden');

        // Update preview
            previewImage.src = notification.image;
            previewImageContainer.classList.remove('hidden');
        }
        
        // Update the preview
        updatePreview();

        // Handle sent notification UI
        handleSentNotification(notification.is_sent);
    }
    
    function getFileNameFromPath(path) {
        if (!path) return '';
        return path.split('/').pop() || path;
    }

    function handleSentNotification(isSent) {
        // Check for reschedule parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        const reschedule = urlParams.get('reschedule');

        if (isSent) {
            // Show reschedule option and hide edit options
            document.getElementById('reschedule-container').style.display = 'flex';
            sendNowCheckbox.parentElement.parentElement.style.display = 'none'; // Hide send now option
            
            // Change button text to indicate reschedule action
            saveButtonText.textContent = "Reschedule Notification";
            
            // Disable form by default for sent notifications
            disableForm();
            
            // If reschedule parameter is present, check the reschedule checkbox
            if (reschedule === 'true') {
                document.getElementById('reschedule').checked = true;
                enableFormForReschedule(); // Only enable scheduling fields
                notificationStatus.textContent = "You are rescheduling a notification that was already sent. You can only change the schedule date.";
                notificationStatus.classList.add('text-blue-600', 'dark:text-blue-400');
            } else {
                notificationStatus.textContent = "This notification has already been sent. To send it again, use the reschedule option.";
                notificationStatus.classList.add('text-amber-600', 'dark:text-amber-400');
            }
        } else {
            // Hide reschedule option for unsent notifications
            document.getElementById('reschedule-container').style.display = 'none';
            
            // Set minimum date for scheduled_at to now
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            scheduledAtInput.min = `${year}-${month}-${day}T${hours}:${minutes}`;
            
            // Enable all form fields for editing
            enableForm();
            
            notificationStatus.textContent = "You can edit this notification before it's sent.";
            notificationStatus.classList.add('text-green-600', 'dark:text-green-400');
        }
        
        // Show content after loading
        loadingSpinner.style.display = 'none';
        contentArea.style.display = 'block';
    }

    // Fetch existing data
    fetch(`/api/admin/notifications/${notificationId}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                localStorage.removeItem('access_token');
                window.location.href = '/admin/login';
                return null;
            }
            throw new Error('Failed to fetch notification data');
        }
        return response.json();
    })
    .then(data => {
        if (data && data.data) {
            populateForm(data.data);
        } else {
            throw new Error('Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error fetching notification:', error);
        formError.textContent = 'Could not load notification data. Please try again.';
        saveButton.disabled = true;
        loadingSpinner.style.display = 'none';
        contentArea.style.display = 'block';
    });

    sendNowCheckbox.addEventListener('change', (e) => {
        toggleSchedule(e.target.checked);
    });
    
    // Preview update on input
    titleInput.addEventListener('input', updatePreview);
    messageInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('change', updateIconPreview);
    imageInput.addEventListener('change', updateImagePreview);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();
        
        // Basic client-side validation
        if (!titleInput.value.trim()) {
            document.getElementById('title-error').textContent = 'Title is required';
            document.getElementById('title-error').style.color = 'red';
            titleInput.focus();
            return;
        }
        
        if (!messageInput.value.trim()) {
            document.getElementById('message-error').textContent = 'Message is required';
            document.getElementById('message-error').style.color = 'red';
            messageInput.focus();
            return;
        }

        const formData = new FormData(form);
        formData.append('send_now', sendNowCheckbox.checked ? 1 : 0);
        formData.append('_method', 'PUT'); // Laravel method spoofing for PUT requests
        
        // Add reschedule flag if checkbox exists and is checked
        const rescheduleCheckbox = document.getElementById('reschedule');
        if (rescheduleCheckbox && rescheduleCheckbox.checked) {
            formData.append('reschedule', 1);
        }

        if (!sendNowCheckbox.checked && !formData.get('scheduled_at')) {
            document.getElementById('scheduled_at-error').textContent = 'The scheduled at field is required when not sending now.';
            document.getElementById('scheduled_at-error').style.color = 'red';
            scheduledAtInput.focus();
            return;
        }
        
        // Remove empty file inputs to avoid sending empty files
        if (!iconInput.files || !iconInput.files[0]) {
            formData.delete('icon');
        }
        
        if (!imageInput.files || !imageInput.files[0]) {
            formData.delete('image');
        }
        
        setLoading(true);

        fetch(`/api/admin/notifications/${notificationId}`, {
            method: 'POST', // Using POST with _method=PUT for FormData
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
                // No Content-Type header for FormData
            },
            body: formData
        })
        .then(response => {
            if (response.status === 422) {
                return response.json().then(data => {
                    handleValidationErrors(data.errors);
                    throw new Error('Validation failed');
                });
            }
            if (!response.ok) {
                return response.json().then(err => {
                    formError.textContent = err.message || 'An unexpected error occurred.';
                    throw new Error(err.message || 'An unexpected error occurred.');
                });
            }
            return response.json();
        })
        .then(data => {
            window.location.href = `{{ route('admin.notification.index') }}?success=${encodeURIComponent(data.message || 'Notification updated successfully')}`;
        })
        .catch(error => {
            console.error('Error:', error);
             if(error.message !== 'Validation failed') {
                formError.textContent = 'Failed to update notification. Please try again.';
            }
        })
        .finally(() => {
            setLoading(false);
        });
    });

    function handleValidationErrors(errors) {
        for (const field in errors) {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.color = 'red';
            }
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('p[id$="-error"]');
        errorElements.forEach(el => {
            el.textContent = '';
            el.style.color = '';
        });
        formError.textContent = '';
    }

    function setLoading(isLoading) {
        if (isLoading) {
            saveButton.disabled = true;
            saveButtonText.textContent = 'Memuat...';
            loadingSpinner.classList.remove('hidden');
        } else {
            if (!form.classList.contains('form-disabled')) {
                saveButton.disabled = false;
                saveButtonText.textContent = 'Update Notifikasi';
            }
            loadingSpinner.classList.add('hidden');
        }
    }

    function disableForm() {
        const formElements = form.querySelectorAll('input, textarea, button, select');
        formElements.forEach(el => {
            if (el.id !== 'save-button' && el.id !== 'reschedule') {
                el.disabled = true;
            }
        });
        
        form.classList.add('form-disabled');
        saveButton.disabled = true;
        
        // Hide form error if any
        formError.textContent = '';
    }
    
    function enableForm() {
        const formElements = form.querySelectorAll('input, textarea, button, select');
        formElements.forEach(el => {
            el.disabled = false;
        });
        
        form.classList.remove('form-disabled');
        saveButton.disabled = false;
    }
    
    function enableFormForReschedule() {
        // Keep most fields disabled
        const formElements = form.querySelectorAll('input, textarea, button, select');
        formElements.forEach(el => {
            el.disabled = true;
        });
        
        // Only enable scheduling fields
        scheduledAtInput.disabled = false;
        document.getElementById('reschedule').disabled = false;
        saveButton.disabled = false;
        
        // Hide file input fields to prevent accidental changes
        document.getElementById('icon').parentElement.style.display = 'none';
        document.getElementById('image').parentElement.style.display = 'none';
        
        form.classList.remove('form-disabled');
    }

    // Add event listener for reschedule checkbox
    const rescheduleCheckbox = document.getElementById('reschedule');
    if (rescheduleCheckbox) {
        rescheduleCheckbox.addEventListener('change', function() {
            if (this.checked) {
                enableFormForReschedule();
                notificationStatus.textContent = "You are rescheduling a notification that was already sent. You can only change the schedule date.";
                notificationStatus.classList.add('text-blue-600', 'dark:text-blue-400');
                notificationStatus.classList.remove('text-amber-600', 'dark:text-amber-400');
            } else {
                disableForm();
                notificationStatus.textContent = "This notification has already been sent. To send it again, use the reschedule option.";
                notificationStatus.classList.add('text-amber-600', 'dark:text-amber-400');
                notificationStatus.classList.remove('text-blue-600', 'dark:text-blue-400');
            }
        });
    }

    // Initial state
    toggleSchedule(sendNowCheckbox.checked);
});
</script>
@endpush 