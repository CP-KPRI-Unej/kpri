@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('styles')
<style>
    .error-message {
        display: none;
    }
    
    .error-message.visible {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit FAQ</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Edit pertanyaan dan jawaban yang sering ditanyakan</p>
        </div>
        <a href="{{ route('admin.faq.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
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

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <div id="loading-indicator" class="text-center py-6">
            <svg class="animate-spin h-8 w-8 mx-auto text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm text-gray-500 mt-2 block">Memuat data...</span>
        </div>

        <form id="faqForm" onsubmit="submitForm(event)" class="hidden">
            <input type="hidden" id="faq_id" value="{{ $id }}">
            
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" placeholder="Masukkan pertanyaan" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="judul-error"></p>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jawaban <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="8" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" placeholder="Masukkan jawaban dari pertanyaan tersebut" required></textarea>
                        <p class="text-red-500 text-xs mt-1 error-message" id="deskripsi-error"></p>
            </div>

                    <div>
                        <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="id_status" name="id_status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Memuat status...</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1 error-message" id="id_status-error"></p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.faq.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Perbarui FAQ</button>
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
            fetchStatusData();
            fetchFAQData();
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
                    fetchStatusData();
                    fetchFAQData();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch status data
    function fetchStatusData() {
        axios.get('/api/admin/statuses')
            .then(response => {
                if (response.data.status === 'success') {
                    renderStatusOptions(response.data.data);
                }
            })
            .catch(error => {
                console.error('Error fetching statuses:', error);
                showAlert('error', 'Gagal memuat data status');
            });
    }
    
    // Render status options
    function renderStatusOptions(statusData) {
        const selectElement = document.getElementById('id_status');
        
        // Clear options
        selectElement.innerHTML = '<option value="">Pilih Status</option>';
        
        // Add status options
        statusData.forEach(status => {
            const option = document.createElement('option');
            option.value = status.id_status;
            option.textContent = status.nama_status;
            selectElement.appendChild(option);
        });
        
        // If FAQ data is already loaded, set the selected status
        const faqData = window.faqData;
        if (faqData && faqData.id_status) {
            selectElement.value = faqData.id_status;
        }
    }
    
    // Fetch FAQ data
    function fetchFAQData() {
        const faqId = '{{ $id }}';
        
        axios.get(`/api/admin/faqs/${faqId}`)
            .then(response => {
                if (response.data.status === 'success') {
                    const faqData = response.data.data;
                    window.faqData = faqData; // Store for later use
                    
                    // Populate form fields
                    document.getElementById('judul').value = faqData.judul;
                    document.getElementById('deskripsi').value = faqData.deskripsi;
                    
                    // Set status if options are already loaded
                    const statusSelect = document.getElementById('id_status');
                    if (statusSelect.options.length > 1 && faqData.id_status) {
                        statusSelect.value = faqData.id_status;
                    }
                    
                    // Show form and hide loading indicator
                    document.getElementById('loading-indicator').classList.add('hidden');
                    document.getElementById('faqForm').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching FAQ data:', error);
                showAlert('error', 'Gagal memuat data FAQ');
                
                if (error.response && error.response.status === 404) {
                    // Redirect back to index if FAQ not found
                    setTimeout(() => {
                    window.location.href = '{{ route("admin.faq.index") }}';
                    }, 1500);
                }
            });
    }
    
    // Submit form handler
    function submitForm(event) {
        event.preventDefault();
        
        // Clear previous error messages
        clearErrors();
        
        const faqId = '{{ $id }}';
        
        // Get form data
        const formData = {
            judul: document.getElementById('judul').value,
            deskripsi: document.getElementById('deskripsi').value,
            id_status: document.getElementById('id_status').value
        };
        
        // Submit to API
        axios.put(`/api/admin/faqs/${faqId}`, formData)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'FAQ berhasil diperbarui');
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.faq.index") }}';
                    }, 1500);
                } else {
                    showAlert('error', 'Gagal memperbarui FAQ');
                }
            })
            .catch(error => {
                console.error('Error updating FAQ:', error);
                
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
                                errorElement.classList.add('visible');
                            }
                        }
                    }
                } else {
                    showAlert('error', 'Error updating FAQ: ' + (error.response?.data?.message || error.message));
                }
            });
    }
    
    // Clear all error messages
    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.remove('visible');
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