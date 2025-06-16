@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Edit Kategori Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data kategori produk</p>
        </div>
        <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message">Operation successful</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.classList.add('hidden')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message">An error occurred</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.classList.add('hidden')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <!-- Loading indicator -->
    <div id="loading-indicator" class="flex items-center justify-center p-12">
        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] text-blue-600 motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
        </div>
        <p class="ml-2 text-gray-600 dark:text-gray-400">Memuat data kategori...</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hidden" id="edit-form-container">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Form Edit Kategori: <span class="text-indigo-600 dark:text-indigo-400" id="category-name"></span></h3>
        </div>
        <div class="p-6">
            <form id="editCategoryForm">
                <input type="hidden" id="categoryId" value="{{ $id }}">
                
                <div class="mb-6">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                        id="kategori" name="kategori" required maxlength="30" autofocus
                        placeholder="Masukkan nama kategori">
                    <p id="kategori_error" class="mt-1 text-sm text-red-500 hidden"></p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 30 karakter.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Perbarui Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get access token from localStorage
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            // Redirect to login if no token
            window.location.href = '/admin/login';
            return;
        }
        
        // Get category ID
        const categoryId = document.getElementById('categoryId').value;
        
        // Fetch category data
        fetchCategory(categoryId);
        
        // Handle form submission
        const form = document.getElementById('editCategoryForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            updateCategory();
        });
        
        async function fetchCategory(id) {
            try {
                const response = await fetch(`/api/admin/categories/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch category data');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Hide loading indicator and show form
                    document.getElementById('loading-indicator').classList.add('hidden');
                    document.getElementById('edit-form-container').classList.remove('hidden');
                    
                    const category = data.data;
                    
                    // Populate form fields
                    document.getElementById('kategori').value = category.kategori || '';
                    document.getElementById('category-name').textContent = category.kategori || '';
                } else {
                    showError('Failed to load category data');
                }
            } catch (error) {
                console.error('Error fetching category:', error);
                showError('Failed to load category data. Please try again later.');
            }
        }
        
        async function updateCategory() {
            // Clear previous errors
            clearErrors();
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memperbarui...
            `;
            submitBtn.disabled = true;
            
            // Get form data
            const kategori = document.getElementById('kategori').value;
            
            try {
                const response = await fetch(`/api/admin/categories/${categoryId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kategori: kategori
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    showSuccess('Kategori berhasil diperbarui!');
                    
                    // Update category name in header
                    document.getElementById('category-name').textContent = data.data.kategori;
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.kategori.index') }}";
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors && data.errors.kategori) {
                        const errorElement = document.getElementById('kategori_error');
                        errorElement.textContent = data.errors.kategori[0];
                        errorElement.classList.remove('hidden');
                        document.getElementById('kategori').classList.add('border-red-500');
                    } else {
                        // Show general error
                        showError(data.message || 'Gagal memperbarui kategori');
                    }
                }
            } catch (error) {
                console.error('Error updating category:', error);
                showError('Terjadi kesalahan saat memperbarui kategori');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalBtnHTML;
                submitBtn.disabled = false;
            }
        }
        
        function clearErrors() {
            // Clear specific field errors
            const kategoriError = document.getElementById('kategori_error');
            kategoriError.textContent = '';
            kategoriError.classList.add('hidden');
            document.getElementById('kategori').classList.remove('border-red-500');
            
            // Hide alerts
            document.getElementById('success-alert').classList.add('hidden');
            document.getElementById('error-alert').classList.add('hidden');
        }
        
        function showSuccess(message) {
            const successAlert = document.getElementById('success-alert');
            const successMessage = document.getElementById('success-message');
            
            successMessage.textContent = message;
            successAlert.classList.remove('hidden');
        }
        
        function showError(message) {
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            
            errorMessage.textContent = message;
            errorAlert.classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection 