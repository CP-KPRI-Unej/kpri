@extends('admin.layouts.app')

@section('title', 'Tambah Produk Baru')

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
        border-color: #f97316;
        background-color: #f8fafc;
    }
    .dropzone.dragover {
        border-color: #f97316;
        background-color: rgba(249, 115, 22, 0.05);
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
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Tambah Produk Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan produk baru untuk ditampilkan di website</p>
        </div>
        <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
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
        <form id="productForm" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" 
                           id="nama_produk" name="nama_produk" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="nama_produk_error"></p>
                    </div>

                    <div>
                        <label for="deskripsi_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" 
                            id="deskripsi_produk" name="deskripsi_produk"></textarea>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="deskripsi_produk_error"></p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="id_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" 
                            id="id_kategori" name="id_kategori" required>
                        <option value="">Pilih Kategori</option>
                        <!-- Categories will be loaded here via API -->
                    </select>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="id_kategori_error"></p>
                </div>

                    <div>
                        <label for="harga_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" 
                           id="harga_produk" name="harga_produk" min="0" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="harga_produk_error"></p>
                </div>
                
                    <div>
                        <label for="stok_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" 
                           id="stok_produk" name="stok_produk" min="0" required>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="stok_produk_error"></p>
                </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Produk <span class="text-red-500">*</span></label>
                    <div class="relative dropzone" id="dropzoneArea">
                        <input type="file" class="file-input" id="gambar_produk" name="gambar_produk" accept="image/*">
                        <div class="dropzone-content">
                            <i class="bi bi-cloud-arrow-up upload-icon text-3xl"></i>
                            <p class="text-gray-600 dark:text-gray-400">Upload Gambar / Drop gambar disini</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                        </div>
                        <div class="file-info bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <div class="flex items-center">
                                <img id="imagePreview" class="w-10 h-10 object-cover rounded mr-2" />
            <div>
                                    <p class="text-sm font-medium" id="fileName">filename.jpg</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" id="fileSize">0 KB</p>
                                </div>
                                <button type="button" class="ml-auto text-gray-400 hover:text-red-500" id="removeFile">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="gambar_produk_error"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="submit" id="submitButton" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan Produk</button>
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
        
        const dropzone = document.getElementById('dropzoneArea');
        const fileInput = document.getElementById('gambar_produk');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileInfo = document.querySelector('.file-info');
        const removeFileBtn = document.getElementById('removeFile');
        const imagePreview = document.getElementById('imagePreview');
        const productForm = document.getElementById('productForm');

        // Load categories
        fetchCategories();
        
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
            imagePreview.src = '';
        });
        
        // Handle form submission
        productForm.addEventListener('submit', handleSubmit);

        // Check authentication status
        function checkAuthentication() {
            axios.get('/api/auth/me')
                .then(response => {
                    // Store the token for future requests
                    const token = response.data.access_token;
                    if (token) {
                        localStorage.setItem('access_token', token);
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
        
        function fetchCategories() {
            axios.get('/api/admin/product-categories')
                .then(response => {
                    if (response.data.status === 'success') {
                        populateCategoryDropdown(response.data.data);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat kategori: ' + (error.response?.data?.message || error.message));
                });
        }

        function populateCategoryDropdown(categories) {
                    const categorySelect = document.getElementById('id_kategori');
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id_kategori;
                        option.textContent = category.kategori;
                        categorySelect.appendChild(option);
                    });
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
                previewImage(file);
                showFileInfo();
            }
        }

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function updateFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
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
                Menyimpan...
            `;
            
            // Create FormData and append form values
            const formData = new FormData();
            formData.append('nama_produk', document.getElementById('nama_produk').value);
            formData.append('id_kategori', document.getElementById('id_kategori').value);
            formData.append('harga_produk', document.getElementById('harga_produk').value);
            formData.append('stok_produk', document.getElementById('stok_produk').value);
            formData.append('deskripsi_produk', document.getElementById('deskripsi_produk').value);
            
            if (fileInput.files.length > 0) {
                formData.append('gambar_produk', fileInput.files[0]);
            }
            
            // Submit form via API
            axios.post('/api/admin/products', formData, {
                    headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'Produk berhasil ditambahkan!');
                    
                    // Reset form
                    productForm.reset();
                    fileInfo.classList.remove('active');
                    imagePreview.src = '';
                    
                    // Redirect to index page after 2 seconds
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.produk.index') }}";
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
            
            // Validate product name
            const namaProduct = document.getElementById('nama_produk').value;
            if (!namaProduct) {
                showFieldError('nama_produk', 'Nama produk harus diisi');
                isValid = false;
            }
            
            // Validate category
            const kategori = document.getElementById('id_kategori').value;
            if (!kategori) {
                showFieldError('id_kategori', 'Kategori harus dipilih');
                isValid = false;
            }
            
            // Validate price
            const harga = document.getElementById('harga_produk').value;
            if (!harga || isNaN(harga) || harga < 0) {
                showFieldError('harga_produk', 'Harga produk harus diisi dengan angka valid');
                isValid = false;
            }
            
            // Validate stock
            const stok = document.getElementById('stok_produk').value;
            if (!stok || isNaN(stok) || stok < 0) {
                showFieldError('stok_produk', 'Stok produk harus diisi dengan angka valid');
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
    });
</script>
@endpush 