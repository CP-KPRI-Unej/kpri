@extends('admin.layouts.app')

@section('title', 'Tambah Item Download')

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
    .pdf-icon { color: #dc3545; }
    .doc-icon { color: #0d6efd; }
    .xls-icon { color: #198754; }
    .ppt-icon { color: #fd7e14; }
    .zip-icon { color: #6c757d; }
    .default-icon { color: #6b7280; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Tambah Item Download</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan file baru untuk di-download oleh pengguna</p>
        </div>
        <a href="{{ route('admin.download.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
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

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-6">
                <div class="mb-6">
                    <label for="nama_item" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama File</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white" 
                           id="nama_item" name="nama_item" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="nama_item_error"></p>
                </div>

                <div class="mb-6">
                    <div class="relative dropzone" id="dropzoneArea">
                        <input type="file" class="file-input" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                        <div class="dropzone-content">
                            <i class="bi bi-cloud-arrow-up upload-icon text-3xl"></i>
                            <p class="text-gray-600 dark:text-gray-400">Upload File / Drop item disini</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Maks. 10MB)</p>
                        </div>
                        <div class="file-info bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <div class="flex items-center">
                                <i class="bi bi-file-earmark file-icon mr-2" id="fileIcon"></i>
                                <div>
                                    <p class="text-sm font-medium" id="fileName">filename.pdf</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" id="fileSize">0 KB</p>
                                </div>
                                <button type="button" class="ml-auto text-gray-400 hover:text-red-500" id="removeFile">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="file_error"></p>
                </div>
                
                <div class="mb-6">
                    <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white" 
                            id="id_status" name="id_status" required>
                        <option value="">Pilih Status</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="id_status_error"></p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.download.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="submit" id="submitButton" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan</button>
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
        
        const dropzone = document.getElementById('dropzoneArea');
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const fileInfo = document.querySelector('.file-info');
        const removeFileBtn = document.getElementById('removeFile');
        const uploadForm = document.getElementById('uploadForm');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight dropzone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        // Remove highlight when item is dragged away or dropped
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);
        
        // Handle file input change
        fileInput.addEventListener('change', handleFiles, false);
        
        // Handle remove file button
        removeFileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.value = '';
            fileInfo.classList.remove('active');
        });
        
        // Handle form submission
        uploadForm.addEventListener('submit', handleSubmit);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropzone.classList.add('dragover');
        }

        function unhighlight() {
            dropzone.classList.remove('dragover');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                handleFiles();
            }
        }

        function handleFiles() {
            if (fileInput.files.length) {
                const file = fileInput.files[0];
                updateFileInfo(file);
                showFileInfo();
            }
        }

        function updateFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Update icon based on file type
            const extension = file.name.split('.').pop().toLowerCase();
            let iconClass = 'default-icon';
            let iconName = 'file-earmark';
            
            if (extension === 'pdf') {
                iconClass = 'pdf-icon';
                iconName = 'file-earmark-pdf';
            } else if (['doc', 'docx'].includes(extension)) {
                iconClass = 'doc-icon';
                iconName = 'file-earmark-word';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconClass = 'xls-icon';
                iconName = 'file-earmark-excel';
            } else if (['ppt', 'pptx'].includes(extension)) {
                iconClass = 'ppt-icon';
                iconName = 'file-earmark-ppt';
            } else if (['zip', 'rar'].includes(extension)) {
                iconClass = 'zip-icon';
                iconName = 'file-earmark-zip';
            }
            
            fileIcon.className = `bi bi-${iconName} ${iconClass} mr-2`;
        }

        function showFileInfo() {
            fileInfo.classList.add('active');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function handleSubmit(e) {
            e.preventDefault();
            
            // Reset error messages
            clearErrors();
            
            // Validate form
            if (!validateForm()) {
                return;
            }
            
            // Disable submit button and show loading state
            const submitButton = document.getElementById('submitButton');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Uploading...
            `;
            
            // Create FormData and append form values
            const formData = new FormData();
            formData.append('nama_item', document.getElementById('nama_item').value);
            formData.append('id_status', document.getElementById('id_status').value);
            
            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }
            
            // Submit form via API
            axios.post('/api/admin/downloads', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'Item download berhasil ditambahkan!');
                    
                    // Reset form
                    uploadForm.reset();
                    fileInfo.classList.remove('active');
                    
                    // Redirect to index page after 2 seconds
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.download.index') }}";
                    }, 2000);
                }
            })
            .catch(error => {
                if (error.response && error.response.data && error.response.data.errors) {
                    // Display validation errors
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}_error`);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    // Display general error
                    showAlert('error', 'Terjadi kesalahan: ' + (error.response?.data?.message || error.message));
                }
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        }
        
        function validateForm() {
            let isValid = true;
            
            // Validate name
            const namaItem = document.getElementById('nama_item').value;
            if (!namaItem) {
                showFieldError('nama_item', 'Nama file harus diisi');
                isValid = false;
            }
            
            // Validate file
            if (fileInput.files.length === 0) {
                showFieldError('file', 'File harus dipilih');
                isValid = false;
            }
            
            return isValid;
        }
        
        function showFieldError(field, message) {
            const errorElement = document.getElementById(`${field}_error`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }
        
        function clearErrors() {
            const errorElements = document.querySelectorAll('[id$="_error"]');
            errorElements.forEach(element => {
                element.textContent = '';
                element.classList.add('hidden');
            });
        }
        
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
        
        function hideAlert(elementId) {
            document.getElementById(elementId).classList.add('hidden');
        }

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
            axios.get('/api/admin/status')
                .then(response => {
                    if (response.data.status === 'success') {
                        populateStatusDropdown(response.data.data);
                    } else {
                        showError('Failed to load status options');
                    }
                })
                .catch(error => {
                    console.error('Error fetching statuses:', error);
                    showError('Error loading status options: ' + (error.response?.data?.message || error.message));
                });
        }
        
        // Populate status dropdown
        function populateStatusDropdown(statuses) {
            const dropdown = document.getElementById('id_status');
            
            // Clear existing options except the first one
            while (dropdown.options.length > 1) {
                dropdown.remove(1);
            }
            
            // Add status options
            statuses.forEach(status => {
                const option = document.createElement('option');
                option.value = status.id_status;
                option.textContent = status.nama_status;
                dropdown.appendChild(option);
            });
            
            // Set default value to Active (usually id_status = 1)
            const activeOption = Array.from(dropdown.options).find(option => 
                option.textContent.toLowerCase() === 'active'
            );
            if (activeOption) {
                dropdown.value = activeOption.value;
            }
        }
        
        function showError(message) {
            // Display error message to user
            const errorElement = document.getElementById('id_status_error');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }
    });
</script>
@endpush 