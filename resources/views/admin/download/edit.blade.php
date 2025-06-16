@extends('admin.layouts.app')

@section('title', 'Edit Item Download')

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
        border-color: #4f46e5;
        background-color: #f8fafc;
    }
    .dropzone.dragover {
        border-color: #4f46e5;
        background-color: rgba(79, 70, 229, 0.05);
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
    .current-file {
        background-color: #f9fafb;
        border-radius: 0.375rem;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
    }
    .dark .current-file {
        background-color: #374151;
        border-color: #4b5563;
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
<div class="container max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
        <svg class="animate-spin h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <div id="content-area" class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden" style="display: none;">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Edit Item Download</h2>
                <a href="{{ route('admin.download.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                    Kembali ke daftar
                </a>
            </div>
        </div>
        
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" id="downloadItemId" value="{{ $id }}">
            <div class="p-6">
                <div class="mb-6">
                    <label for="nama_item" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama File</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                           id="nama_item" name="nama_item" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="nama_item_error"></p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File Saat Ini</label>
                    <div class="current-file flex items-center">
                        <i id="currentFileIcon" class="text-2xl mr-3"></i>
                        <div>
                            <a id="currentFileName" href="#" target="_blank" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"></a>
                            <p id="currentFileDate" class="text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload File Baru (Opsional)</label>
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
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengubah file.</p>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="file_error"></p>
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                            id="status" name="status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="status_error"></p>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right flex justify-end space-x-3">
                <a href="{{ route('admin.download.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" id="submitButton" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-800 hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update
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
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        const downloadItemId = document.getElementById('downloadItemId').value;
        const dropzone = document.getElementById('dropzoneArea');
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const fileInfo = document.querySelector('.file-info');
        const removeFileBtn = document.getElementById('removeFile');
        const uploadForm = document.getElementById('uploadForm');
        const loadingSpinner = document.getElementById('loading-spinner');
        const contentArea = document.getElementById('content-area');

        // Load download item data
        fetchDownloadItem(downloadItemId);

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

        function fetchDownloadItem(id) {
            axios.get(`/api/admin/downloads/${id}`)
                .then(response => {
                    if (response.data.status === 'success') {
                        const item = response.data.data;
                        populateForm(item);
                        loadingSpinner.style.display = 'none';
                        contentArea.style.display = 'block';
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat data: ' + (error.response?.data?.message || error.message));
                    loadingSpinner.style.display = 'none';
                });
        }

        function populateForm(item) {
            // Set form values
            document.getElementById('nama_item').value = item.nama_item;
            document.getElementById('status').value = item.status;
            
            // Set current file info
            const currentFileName = document.getElementById('currentFileName');
            const currentFileDate = document.getElementById('currentFileDate');
            const currentFileIcon = document.getElementById('currentFileIcon');
            
            const fileName = getFileName(item.path_file);
            currentFileName.textContent = fileName;
            currentFileName.href = `/storage/${item.path_file}`;
            
            // Format date
            const uploadDate = new Date(item.tgl_upload);
            const formattedDate = `Uploaded: ${uploadDate.getDate().toString().padStart(2, '0')} ${getMonthName(uploadDate.getMonth())} ${uploadDate.getFullYear()}`;
            currentFileDate.textContent = formattedDate;
            
            // Set icon based on file extension
            const extension = getFileExtension(item.path_file);
            let iconClass = 'default-icon';
            let icon = 'file-earmark';
            
            if (['pdf'].includes(extension)) {
                iconClass = 'pdf-icon';
                icon = 'file-earmark-pdf';
            } else if (['doc', 'docx'].includes(extension)) {
                iconClass = 'doc-icon';
                icon = 'file-earmark-word';
            } else if (['xls', 'xlsx'].includes(extension)) {
                iconClass = 'xls-icon';
                icon = 'file-earmark-excel';
            } else if (['ppt', 'pptx'].includes(extension)) {
                iconClass = 'ppt-icon';
                icon = 'file-earmark-ppt';
            } else if (['zip', 'rar'].includes(extension)) {
                iconClass = 'zip-icon';
                icon = 'file-earmark-zip';
            }
            
            currentFileIcon.className = `bi bi-${icon} ${iconClass} text-2xl mr-3`;
        }

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
                Updating...
            `;
            
            // Create FormData and append form values
            const formData = new FormData();
            formData.append('nama_item', document.getElementById('nama_item').value);
            formData.append('status', document.getElementById('status').value);
            
            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }
            
            // Submit form via API
            axios.post(`/api/admin/downloads/${downloadItemId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'Item download berhasil diperbarui!');
                    
                    // If a new file was uploaded, update the current file info
                    if (fileInput.files.length > 0) {
                        setTimeout(() => {
                            fetchDownloadItem(downloadItemId);
                        }, 1000);
                        fileInfo.classList.remove('active');
                        fileInput.value = '';
                    }
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
        
        function getFileExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }
        
        function getFileName(path) {
            return path.split('/').pop();
        }
        
        function getMonthName(monthIndex) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return months[monthIndex];
        }

        // Check authentication status
        function checkAuthentication() {
            axios.get('/api/auth/me')
                .then(response => {
                    // Store the token for future requests
                    const token = response.data.access_token;
                    if (token) {
                        localStorage.setItem('jwt_token', token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                })
                .catch(error => {
                    console.error('Authentication error:', error);
                    // Redirect to login if unauthenticated
                    if (error.response && error.response.status === 401) {
                        window.location.href = '/admin/login';
                    }
                });
        }
    });
</script>
@endpush 