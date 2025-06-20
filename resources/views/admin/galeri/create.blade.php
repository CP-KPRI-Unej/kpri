@extends('admin.layouts.app')

@section('title', 'Tambah Foto Galeri')

@section('styles')
<style>
    .dropzone {
        border: 2px dashed #e2e8f0;
        border-radius: 0.5rem;
        padding: 3rem 1rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .dropzone:hover {
        border-color: #f59e0b;
        background-color: #f8fafc;
    }
    .dropzone.dragover {
        border-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.05);
    }
    .upload-icon {
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .file-info {
        display: none;
        margin-top: 1rem;
    }
    .file-info.active {
        display: block;
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
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Tambah Foto Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Upload foto baru ke galeri</p>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
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
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-6">
                    <!-- Nama Foto -->
                    <div class="mb-6">
                        <label for="nama_galeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Foto <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_galeri" id="nama_galeri" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white input-stroke" maxlength="30" required>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="nama_galeri_error"></p>
                </div>
                
                    <!-- Gambar Galeri -->
                    <div class="mb-6">
                        <label for="gambar_galeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Galeri <span class="text-red-500">*</span></label>
                        <div class="relative dropzone" id="dropZone">
                            <input type="file" name="gambar_galeri" id="gambar_galeri" class="file-input" accept="image/*" onchange="previewImage(event)">
                            <div class="dropzone-content">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 upload-icon" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400">Upload Gambar / Drop gambar disini</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Format: JPG, JPEG, PNG, GIF (Maks. 2MB)</p>
                            </div>
                        </div>
                        <div id="previewContainer" class="mt-3 hidden">
                            <img id="imagePreview" src="#" alt="Preview" class="max-h-48 max-w-full rounded border border-gray-300 dark:border-gray-600">
                            <button type="button" class="mt-2 px-3 py-1 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300" onclick="removePreview()">
                                <i class="bi bi-x-circle mr-1"></i> Hapus
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="gambar_galeri_error"></p>
                    </div>
                
                <!-- Status -->
                <div class="mb-6">
                    <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="id_status" id="id_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white input-stroke" required>
                        <option value="">Pilih Status</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="id_status_error"></p>
                </div>
                    
                    <div class="pt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Foto akan ditampilkan di halaman galeri website. Pastikan gambar memiliki resolusi yang baik dan berkualitas.
                        </p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="button" id="submitBtn" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan Foto</button>
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
            fetchStatuses();
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
        document.getElementById('submitBtn').addEventListener('click', handleFormSubmit);
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
    
    // Setup dropzone functionality
    function setupDropzone() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('gambar_galeri');
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Highlight dropzone when item is dragged over it
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
        
        // Handle dropped files
        dropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                previewImage({ target: fileInput });
            }
        });
    }
    
    // Preview uploaded image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('previewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
    
    // Remove image preview
    function removePreview() {
        document.getElementById('gambar_galeri').value = '';
        document.getElementById('previewContainer').classList.add('hidden');
        document.getElementById('imagePreview').src = '#';
    }
    
    // Handle form submission
    function handleFormSubmit() {
        // Clear previous error messages
        clearErrors();
        
        // Get form data
        const formData = new FormData(document.getElementById('uploadForm'));
        
        // Client-side validation
        let isValid = true;
        
        if (!formData.get('nama_galeri')) {
            showInputError('nama_galeri', 'Nama foto harus diisi');
            isValid = false;
        }
        
        if (!formData.get('id_status')) {
            showInputError('id_status', 'Status harus dipilih');
            isValid = false;
        }
        
        if (!formData.get('gambar_galeri') || !document.getElementById('gambar_galeri').files.length) {
            showInputError('gambar_galeri', 'Foto harus diunggah');
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Submit form
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
        
        axios.post('/api/admin/gallery', formData)
        .then(response => {
            if (response.data.status === 'success') {
                showAlert('success', 'Foto berhasil disimpan');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.galeri.index") }}';
                }, 1500);
            } else {
                    throw new Error(response.data.message || 'Failed to save photo');
            }
        })
        .catch(error => {
            console.error('Error saving photo:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    handleValidationErrors(error.response.data.errors);
                } else {
                    showAlert('error', 'Error saving photo: ' + (error.response?.data?.message || error.message));
                }
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            });
    }
    
    // Show input error
    function showInputError(inputId, message) {
        const errorElement = document.getElementById(`${inputId}_error`);
                        if (errorElement) {
            errorElement.textContent = message;
                            errorElement.classList.remove('hidden');
                        }
                    }
    
    // Handle validation errors from backend
    function handleValidationErrors(errors) {
        for (const field in errors) {
            showInputError(field, errors[field][0]);
            }
    }
    
    // Clear all error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="_error"]');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    }
    
    // Show alert message
    function showAlert(type, message) {
        const alertId = type === 'success' ? 'alert-success' : 'alert-error';
        const messageId = type === 'success' ? 'success-message' : 'error-message';
        
        document.getElementById(messageId).textContent = message;
        document.getElementById(alertId).classList.remove('hidden');
        
        // Hide alert after 5 seconds
        setTimeout(() => {
            hideAlert(alertId);
        }, 5000);
    }
    
    // Hide alert message
    function hideAlert(alertId) {
        document.getElementById(alertId).classList.add('hidden');
    }
</script>
@endpush

@endsection 