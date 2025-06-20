@extends('admin.layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="bg-orange-500 dark:bg-orange-600 px-4 py-3">
            <h5 class="text-white font-medium text-lg" id="layanan-title">Edit Layanan</h5>
        </div>
        
        <div class="p-6">
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                <span class="block sm:inline" id="success-message"></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                    </svg>
                </button>
            </div>

            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
                <span class="block sm:inline" id="error-message"></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                    </svg>
                </button>
            </div>
        
            <!-- Tabs Navigation will be added dynamically here -->
            <div id="tabs-container" class="flex border-b border-gray-200 dark:border-gray-700 mb-4 hidden"></div>
            
            <form id="layananForm" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Layanan</label>
                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="judul_layanan_display"></div>
                    <input type="hidden" id="judul_layanan" name="judul_layanan">
                </div>
                
                <!-- Description editor - will be shown or hidden based on data -->
                <div id="deskripsi-section" class="mb-4 hidden">
                    <label for="deskripsi_layanan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Layanan <span class="text-red-600">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="deskripsi_layanan" name="deskripsi_layanan" rows="10"></textarea>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="deskripsi_layanan-error"></p>
                </div>
                
                <!-- Image upload - will be shown or hidden based on data -->
                <div id="gambar-section" class="mb-4 hidden">
                    <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Layanan <span class="text-red-600">*</span></label>
                    
                    <!-- Current image preview -->
                    <div id="current-image-container" class="mb-3 hidden">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar Saat Ini:</p>
                        <div class="relative w-full h-48 border border-gray-300 rounded-md overflow-hidden">
                            <img id="current-image" src="" alt="Gambar Layanan" class="w-full h-full object-contain">
                        </div>
                    </div>
                    
                    <!-- Image upload input -->
                    <div class="mt-2">
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: JPG, PNG, atau GIF. Maksimal 2MB.</p>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="gambar-error"></p>
                    </div>
                    
                    <!-- New image preview section for uploaded file -->
                    <div id="upload-preview-container" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Pratinjau Gambar yang Dipilih:</p>
                        <div class="relative w-full h-48 border border-gray-300 rounded-md overflow-hidden">
                            <img id="upload-preview" src="" alt="Pratinjau Gambar" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="#" id="cancel-button" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700">
                        <i class="bi bi-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md text-sm hover:bg-orange-700" id="submitBtn">
                        <i class="bi bi-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Store the layanan data
    let currentLayanan = null;
    let editor;
    let jenisLayanan = null;
    let hasDescription = false;
    let hasImage = false;

    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM loaded, initializing...");
        
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
        
        // Initialize CKEditor (but don't attach it yet - we'll do that conditionally)
        // We'll initialize it after fetching the layanan data
        
        // Fetch layanan data
        fetchLayanan("{{ $id }}");
        
        // Form submission handler
        const form = document.getElementById('layananForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("Form submission triggered");
            updateLayanan(e);
        });
        
        // Set up cancel button
        document.getElementById('cancel-button').addEventListener('click', function(e) {
            e.preventDefault();
            if (currentLayanan && currentLayanan.id_jenis_layanan) {
                // Fetch all layanan for this jenis_layanan to determine where to go back
                axios.get(`/api/admin/layanan/${currentLayanan.id_jenis_layanan}`)
                    .then(response => {
                        if (response.data.status === 'success' && response.data.data && response.data.data.length > 0) {
                            // Go to the first layanan's edit page
                            const firstLayanan = response.data.data[0];
                            if (firstLayanan.id_layanan != currentLayanan.id_layanan) {
                                window.location.href = `/admin/layanan/edit/${firstLayanan.id_layanan}`;
                            } else {
                                // Already on first layanan, just go back to previous page
                                window.history.back();
                            }
                        } else {
                            window.history.back();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching layanan for jenis:', error);
                        window.history.back();
                    });
            } else {
                window.history.back();
            }
        });
        
        // Add a direct click handler to the submit button as a backup
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            e.preventDefault();
            console.log("Submit button clicked directly");
            updateLayanan(e);
        });

        // Add an event listener for the file input to show preview immediately
        // Add file upload preview
        document.getElementById('gambar').addEventListener('change', function(e) {
            const fileInput = e.target;
            const previewContainer = document.getElementById('upload-preview-container');
            const previewImage = document.getElementById('upload-preview');
            
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                previewContainer.classList.add('hidden');
                previewImage.src = '';
            }
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
                    fetchLayanan("{{ $id }}");
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch layanan data
    function fetchLayanan(id) {
        console.log("Fetching layanan data for ID:", id);
        axios.get(`/api/admin/layanan/detail/${id}`)
            .then(response => {
                if (response.data.status === 'success') {
                    console.log("Layanan data fetched successfully:", response.data);
                    currentLayanan = response.data.data;
                    
                    // Determine what type of layanan this is
                    hasDescription = currentLayanan.deskripsi_layanan && currentLayanan.deskripsi_layanan.trim() !== '';
                    hasImage = currentLayanan.gambar && currentLayanan.gambar.trim() !== '';
                    
                    // Fetch jenis layanan details to get all layanan in this category
                    fetchJenisLayananDetails(currentLayanan.id_jenis_layanan);
                    
                    // Populate the form with current layanan data
                    populateForm(currentLayanan);
                } else {
                    console.error("Failed to fetch layanan data:", response.data);
                    showAlert('error', 'Gagal memuat data layanan');
                }
            })
            .catch(error => {
                console.error('Error fetching layanan:', error);
                showAlert('error', 'Gagal memuat data: ' + (error.response?.data?.message || error.message));
            });
    }
    
    // Fetch jenis layanan details
    function fetchJenisLayananDetails(jenisLayananId) {
        console.log("Fetching jenis layanan details for ID:", jenisLayananId);
        axios.get(`/api/admin/layanan/jenis/${jenisLayananId}`)
            .then(response => {
                if (response.data && response.data.data) {
                    console.log("Jenis layanan details fetched successfully:", response.data);
                    jenisLayanan = response.data.data;
                    document.getElementById('layanan-title').textContent = `Edit ${hasImage ? 'Gambar' : 'Deskripsi'} - ${jenisLayanan.nama_layanan}`;
                    
                    // Create tabs if there are multiple layanan in this category
                    if (jenisLayanan.layanan && jenisLayanan.layanan.length > 1) {
                        createTabs(jenisLayanan.layanan);
                    }
                }
            })
        .catch(error => {
                console.error('Error fetching jenis layanan details:', error);
            });
    }
    
    // Create tabs for navigation between layanan
    function createTabs(layanans) {
        const tabsContainer = document.getElementById('tabs-container');
        tabsContainer.innerHTML = '';
        tabsContainer.classList.remove('hidden');
        
        layanans.forEach(layanan => {
            const isActive = layanan.id_layanan == currentLayanan.id_layanan;
            const tabItem = document.createElement('a');
            tabItem.href = `/admin/layanan/edit/${layanan.id_layanan}`;
            tabItem.className = `px-4 py-2 text-sm font-medium ${isActive ? 'border-b-2 border-orange-500 text-orange-600 dark:text-orange-400' : 'text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400'}`;
            tabItem.textContent = layanan.judul_layanan;
            
            tabsContainer.appendChild(tabItem);
        });
    }
    
    // Populate form with layanan data
    function populateForm(layanan) {
        console.log("Populating form with layanan data:", layanan);
        document.getElementById('judul_layanan_display').textContent = layanan.judul_layanan;
        document.getElementById('judul_layanan').value = layanan.judul_layanan;
        
        // Show the appropriate section based on the layanan type
        if (hasDescription) {
            document.getElementById('deskripsi-section').classList.remove('hidden');
            
            // Initialize CKEditor if we have description
            ClassicEditor
                .create(document.querySelector('#deskripsi_layanan'))
                .then(newEditor => {
                    console.log("CKEditor initialized successfully");
                    editor = newEditor;
                    // Set the content
                    editor.setData(layanan.deskripsi_layanan || '');
                })
                .catch(error => {
                    console.error("CKEditor initialization error:", error);
                    // Fallback to regular textarea
                    document.getElementById('deskripsi_layanan').value = layanan.deskripsi_layanan || '';
                });
        }
        
        if (hasImage) {
            document.getElementById('gambar-section').classList.remove('hidden');
            
            // Show current image if available
            if (layanan.gambar) {
                document.getElementById('current-image-container').classList.remove('hidden');
                // Check if the path already contains storage/ or not
                const imagePath = layanan.gambar.startsWith('storage/') ? layanan.gambar : `storage/${layanan.gambar}`;
                document.getElementById('current-image').src = `/${imagePath}`;
            }
        }
        
        // If neither description nor image, show both sections (new layanan)
        if (!hasDescription && !hasImage) {
            document.getElementById('deskripsi-section').classList.remove('hidden');
            document.getElementById('gambar-section').classList.remove('hidden');
            
            // Initialize CKEditor for new layanan
            ClassicEditor
                .create(document.querySelector('#deskripsi_layanan'))
                .then(newEditor => {
                    console.log("CKEditor initialized successfully");
                    editor = newEditor;
                })
                .catch(error => {
                    console.error("CKEditor initialization error:", error);
                });
        }
    }
    
    // Update layanan
    function updateLayanan(e) {
        e.preventDefault();
        console.log("Updating layanan...");
        
        // Clear previous error messages
        document.getElementById('deskripsi_layanan-error').textContent = '';
        document.getElementById('gambar-error').textContent = '';
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memperbarui...';
        
        // Create form data object for file uploads
        const formData = new FormData();
        formData.append('judul_layanan', document.getElementById('judul_layanan').value);
        formData.append('id_jenis_layanan', currentLayanan.id_jenis_layanan);
        
        // Add description if the section is visible
        if (!document.getElementById('deskripsi-section').classList.contains('hidden')) {
            let deskripsiContent = "";
            if (editor) {
                deskripsiContent = editor.getData();
                console.log("Getting content from CKEditor:", deskripsiContent);
            } else {
                deskripsiContent = document.getElementById('deskripsi_layanan').value;
                console.log("Getting content from textarea:", deskripsiContent);
            }
            formData.append('deskripsi_layanan', deskripsiContent);
        } else {
            // Keep existing description if any
            if (currentLayanan.deskripsi_layanan) {
                formData.append('deskripsi_layanan', currentLayanan.deskripsi_layanan);
            }
        }
        
        // Add image if the section is visible and file is selected
        if (!document.getElementById('gambar-section').classList.contains('hidden')) {
            const imageFile = document.getElementById('gambar').files[0];
            if (imageFile) {
                formData.append('gambar', imageFile);
            }
        }
        
        console.log("Sending data for update...");
        
        axios.post(`/api/admin/layanan/{{ $id }}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(response => {
                console.log("Update response:", response.data);
                if (response.data.status === 'success') {
                    showAlert('success', `${hasImage ? 'Gambar' : 'Deskripsi'} layanan berhasil diperbarui.`);
                    // Update current layanan data
                    currentLayanan = response.data.data;
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // If image was updated, refresh the preview
                    if (hasImage && currentLayanan.gambar) {
                        document.getElementById('current-image-container').classList.remove('hidden');
                        // Check if the path already contains storage/ or not
                        const imagePath = currentLayanan.gambar.startsWith('storage/') ? currentLayanan.gambar : `storage/${currentLayanan.gambar}`;
                        // Add cache-busting parameter to force reload
                        document.getElementById('current-image').src = `/${imagePath}?t=${new Date().getTime()}`;
                    }
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    showAlert('error', 'Gagal memperbarui layanan.');
                }
            })
            .catch(error => {
                console.error("Update error:", error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                if (error.response) {
                    console.error("Error response:", error.response.data);
                    if (error.response.status === 422 && error.response.data.errors) {
                        // Handle validation errors
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                            }
                        });
                        showAlert('error', 'Ada kesalahan pada form. Silakan periksa kembali.');
                    } else {
                        showAlert('error', 'Gagal memperbarui layanan: ' + (error.response.data.message || error.message));
                    }
                } else {
                    showAlert('error', 'Gagal memperbarui layanan: ' + error.message);
                }
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