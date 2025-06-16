@extends('admin.layouts.app')

@section('title', 'Tambah FAQ')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Tambah FAQ Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan pertanyaan dan jawaban yang sering ditanyakan</p>
        </div>
        <div>
            <a href="{{ route('admin.faq.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
            </svg>
        </button>
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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form id="faqForm" onsubmit="submitForm(event)">
            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pertanyaan</label>
                <input type="text" name="judul" id="judul" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full p-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan pertanyaan" required>
                <p class="text-red-500 text-xs mt-1 hidden" id="judul-error"></p>
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jawaban</label>
                <textarea name="deskripsi" id="deskripsi" rows="6" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full p-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan jawaban dari pertanyaan tersebut" required></textarea>
                <p class="text-red-500 text-xs mt-1 hidden" id="deskripsi-error"></p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                    Simpan FAQ
                </button>
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
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Submit form handler
    function submitForm(event) {
        event.preventDefault();
        
        // Clear previous error messages
        clearErrors();
        
        // Get form data
        const formData = {
            judul: document.getElementById('judul').value,
            deskripsi: document.getElementById('deskripsi').value
        };
        
        // Submit to API
        axios.post('/api/admin/faqs', formData)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'FAQ berhasil disimpan');
                    // Reset form
                    document.getElementById('faqForm').reset();
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.faq.index") }}';
                    }, 1500);
                } else {
                    showAlert('error', 'Gagal menyimpan FAQ');
                }
            })
            .catch(error => {
                console.error('Error saving FAQ:', error);
                
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
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        }
                    }
                } else {
                    showAlert('error', 'Error saving FAQ: ' + (error.response?.data?.message || error.message));
                }
            });
    }
    
    // Clear all error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
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

@endsection                 </button>
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
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Submit form handler
    function submitForm(event) {
        event.preventDefault();
        
        // Clear previous error messages
        clearErrors();
        
        // Get form data
        const formData = {
            judul: document.getElementById('judul').value,
            deskripsi: document.getElementById('deskripsi').value
        };
        
        // Submit to API
        axios.post('/api/admin/faqs', formData)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'FAQ berhasil disimpan');
                    // Reset form
                    document.getElementById('faqForm').reset();
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.faq.index") }}';
                    }, 1500);
                } else {
                    showAlert('error', 'Gagal menyimpan FAQ');
                }
            })
            .catch(error => {
                console.error('Error saving FAQ:', error);
                
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
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        }
                    }
                } else {
                    showAlert('error', 'Error saving FAQ: ' + (error.response?.data?.message || error.message));
                }
            });
    }
    
    // Clear all error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
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

