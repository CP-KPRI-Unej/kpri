@extends('admin.layouts.app')

@section('title', 'Tambah Foto Galeri')

@section('styles')
<style>
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }
    
    .upload-area:hover, .upload-area.dragover {
        border-color: #6366f1;
        background-color: #f3f4f6;
    }
    
    .dark .upload-area {
        background-color: #374151;
        border-color: #4b5563;
    }
    
    .dark .upload-area:hover, .dark .upload-area.dragover {
        border-color: #6366f1;
        background-color: #1f2937;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Tambah Foto Baru</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Upload foto baru ke galeri</p>
    </div>
    
    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
            </svg>
        </button>
    </div>
    
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden p-6 max-w-xl mx-auto">
        <form id="uploadForm">
            <div class="mb-5">
                <label for="nama_galeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama File</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" 
                       id="nama_galeri" name="nama_galeri" maxlength="30" required>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="nama_galeri-error"></p>
            </div>
            
            <div class="mb-5">
                <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="id_status" name="id_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" required>
                    <option value="">Pilih Status</option>
                </select>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="id_status-error"></p>
            </div>
            
            <div class="mb-5" id="uploadContainer">
                <div class="upload-area" id="dropZone">
                    <div class="flex flex-col items-center justify-center py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Upload File</span> / Drop Item disini</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</p>
                        
                        <input type="file" class="hidden" id="gambar_galeri" name="gambar_galeri" accept="image/*" required>
                    </div>
                </div>
                <div id="previewContainer" class="mt-4 text-center hidden">
                    <img id="imagePreview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow">
                    <button type="button" id="removeImage" class="mt-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <i class="bi bi-x-circle"></i> Hapus
                    </button>
                </div>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="gambar_galeri-error"></p>
            </div>
            
            <div class="flex justify-between mt-6">
                <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="submitBtn">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

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
            fetchStatuses();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('gambar_galeri');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeButton = document.getElementById('removeImage');
        const uploadForm = document.getElementById('uploadForm');
        
        // Form submission handler
        uploadForm.addEventListener('submit', handleFormSubmit);
        
        // Trigger file input when clicking on the drop zone
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });
        
        // Handle file selection
        fileInput.addEventListener('change', handleFileSelect);
        
        // Handle drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropZone.classList.add('dragover');
        }
        
        function unhighlight() {
            dropZone.classList.remove('dragover');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect();
        }
        
        function handleFileSelect() {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        
        // Remove selected image
        removeButton.addEventListener('click', function() {
            fileInput.value = '';
            previewContainer.classList.add('hidden');
            imagePreview.src = '#';
        });
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    fetchStatuses();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch status options for dropdown
    function fetchStatuses() {
        axios.get('/api/admin/gallery-statuses')
            .then(response => {
                if (response.data.status === 'success') {
                    populateStatusDropdown(response.data.data);
                } else {
                    showAlert('error', 'Failed to load status options');
                }
            })
            .catch(error => {
                console.error('Error fetching statuses:', error);
                showAlert('error', 'Error loading status options: ' + (error.response?.data?.message || error.message));
            });
    }
    
    // Populate status dropdown
    function populateStatusDropdown(statuses) {
        const dropdown = document.getElementById('id_status');
        
        // Clear existing options except the first one
        dropdown.innerHTML = '<option value="">Pilih Status</option>';
        
        // Add status options
        statuses.forEach(status => {
            const option = document.createElement('option');
            option.value = status.id_status;
            option.textContent = status.nama_status;
            dropdown.appendChild(option);
        });
    }
    
    // Handle form submission
    function handleFormSubmit(e) {
        e.preventDefault();
        
        // Clear previous error messages
        clearErrorMessages();
        
        // Get form data
        const formData = new FormData();
        formData.append('nama_galeri', document.getElementById('nama_galeri').value);
        formData.append('id_status', document.getElementById('id_status').value);
        
        const fileInput = document.getElementById('gambar_galeri');
        if (fileInput.files[0]) {
            formData.append('gambar_galeri', fileInput.files[0]);
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Uploading...';
        
        // Submit the form
        axios.post('/api/admin/gallery', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            if (response.data.status === 'success') {
                // Redirect to gallery index
                window.location.href = '/admin/galeri';
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                showAlert('error', 'Failed to upload gallery item');
            }
        })
        .catch(error => {
            console.error('Error uploading gallery item:', error);
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
            
            if (error.response && error.response.status === 422) {
                // Validation errors
                const errors = error.response.data.errors;
                
                for (const field in errors) {
                    const errorElement = document.getElementById(`${field}-error`);
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                    }
                }
                
                showAlert('error', 'Please fix the errors in the form');
            } else {
                showAlert('error', 'Error uploading gallery item: ' + (error.response?.data?.message || error.message));
            }
        });
    }
    
    // Clear error messages
    function clearErrorMessages() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(element => {
            element.textContent = '';
        });
    }
    
    // Show alert message
    function showAlert(type, message) {
        const alertElement = document.getElementById(`alert-${type}`);
        const messageElement = document.getElementById(`error-message`);
        
        messageElement.textContent = message;
        alertElement.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert(`alert-${type}`);
        }, 5000);
    }
    
    // Hide alert message
    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }
</script>
@endpush 