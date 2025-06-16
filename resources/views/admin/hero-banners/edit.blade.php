@extends('admin.layouts.app')

@section('title', 'Edit Hero Banner')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Hero Banner</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ubah informasi banner yang ditampilkan di halaman beranda</p>
        </div>
        <a href="{{ route('admin.hero-banners.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
    
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <div id="loading-indicator" class="text-center py-8">
            <svg class="animate-spin h-8 w-8 mx-auto text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Memuat data banner...</p>
        </div>
        
        <form id="bannerForm" enctype="multipart/form-data" class="hidden">
            <input type="hidden" id="banner_id" value="{{ $id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <!-- Judul Banner -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Banner <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="judul_error"></p>
                    </div>
                    
                    <!-- Deskripsi Banner -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Banner <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required></textarea>
                        <p class="text-red-500 text-xs mt-1 error-message" id="deskripsi_error"></p>
                    </div>
                    
                    <!-- URL Banner -->
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL Tujuan <span class="text-red-500">*</span></label>
                        <input type="url" name="url" id="url" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="https://example.com" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="url_error"></p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Gambar Banner -->
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Banner</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative" id="dropzone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Upload gambar baru</span>
                                        <input id="file-upload" name="gambar" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Saat Ini</label>
                            <div id="current-image-container" class="mt-2">
                                <img id="current-image" class="max-h-48 max-w-full rounded border border-gray-300 dark:border-gray-600">
                            </div>
                        </div>
                        
                        <div id="image-preview-container" class="mt-3 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Baru</label>
                            <img id="image-preview" class="max-h-48 max-w-full rounded border border-gray-300 dark:border-gray-600">
                        </div>
                        <p class="text-red-500 text-xs mt-1 error-message" id="gambar_error"></p>
                    </div>
                    
                    <div class="pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Banner akan ditampilkan di halaman beranda website. Pastikan gambar memiliki resolusi yang baik dan relevan dengan konten yang ditautkan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.hero-banners.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</a>
                <button type="button" id="submitBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Update Banner</button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            fetchBanner();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Setup dropzone functionality
        setupDropzone();
        
        // Add submit event listener
        document.getElementById('submitBtn').addEventListener('click', submitForm);
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    fetchBanner();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch banner data
    function fetchBanner() {
        const id = document.getElementById('banner_id').value;
        
        axios.get(`/api/admin/hero-banners/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    populateForm(response.data.data);
                    document.getElementById('loading-indicator').classList.add('hidden');
                    document.getElementById('bannerForm').classList.remove('hidden');
                } else {
                    showAlert('error', 'Gagal memuat data banner');
                }
            })
            .catch(error => {
                console.error('Error fetching banner:', error);
                
                if (error.response && error.response.status === 404) {
                    showAlert('error', 'Banner tidak ditemukan');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.hero-banners.index") }}';
                    }, 1500);
                } else if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                } else {
                    showAlert('error', 'Error fetching banner: ' + (error.response?.data?.message || error.message));
                }
            });
    }
    
    // Populate form with banner data
    function populateForm(banner) {
        document.getElementById('judul').value = banner.judul;
        document.getElementById('deskripsi').value = banner.deskripsi;
        document.getElementById('url').value = banner.url;
        
        // Set current image
        const currentImage = document.getElementById('current-image');
        currentImage.src = `/storage/${banner.gambar}`;
        currentImage.alt = banner.judul;
    }
    
    // Submit form
    function submitForm() {
        // Clear previous errors
        clearErrors();
        
        // Get form data
        const form = document.getElementById('bannerForm');
        const formData = new FormData(form);
        const id = document.getElementById('banner_id').value;
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
        
        axios.post(`/api/admin/hero-banners/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            if (response.data.status === 'success') {
                showAlert('success', 'Banner berhasil diperbarui');
                
                // Reset image preview if any
                document.getElementById('image-preview-container').classList.add('hidden');
                
                // Update current image
                const currentImage = document.getElementById('current-image');
                currentImage.src = `/storage/${response.data.data.gambar}`;
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '{{ route("admin.hero-banners.index") }}';
                }, 1500);
            } else {
                showAlert('error', 'Gagal memperbarui banner');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error updating banner:', error);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            // Check if unauthorized and redirect to login
            if (error.response && error.response.status === 401) {
                window.location.href = '/admin/login';
                return;
            }
            
            // Handle validation errors
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        const errorElement = document.getElementById(`${field}_error`);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    }
                }
                showAlert('error', 'Harap periksa kembali formulir yang Anda isi');
            } else {
                showAlert('error', 'Error updating banner: ' + (error.response?.data?.message || error.message));
            }
        });
    }
    
    // Preview image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        }
    }
    
    // Setup dropzone
    function setupDropzone() {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-upload');
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Highlight dropzone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropzone.classList.add('border-indigo-500');
            dropzone.classList.add('bg-indigo-50');
            dropzone.classList.add('dark:bg-indigo-900/20');
        }
        
        function unhighlight() {
            dropzone.classList.remove('border-indigo-500');
            dropzone.classList.remove('bg-indigo-50');
            dropzone.classList.remove('dark:bg-indigo-900/20');
        }
        
        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                previewImage({ target: { files: files } });
            }
        }
    }
    
    // Clear all error messages
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }
    
    // Show alert messages
    function showAlert(type, message) {
        const alertElement = document.getElementById(`alert-${type}`);
        const messageElement = document.getElementById(`${type}-message`);
        
        messageElement.textContent = message;
        alertElement.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert(`alert-${type}`);
        }, 5000);
    }
    
    // Hide alert messages
    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }
</script>
@endpush

@endsection 
 
 
 
 
 
 
 
 
 
 
 