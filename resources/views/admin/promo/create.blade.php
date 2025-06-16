@extends('admin.layouts.app')

@section('title', 'Tambah Promosi')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: #fff;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        cursor: text;
        padding: 0.5rem;
        width: 100%;
    }
    .dark .select2-container--default .select2-selection--multiple {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #f3f4f6;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e5e7eb;
        border: none;
        border-radius: 0.25rem;
        margin: 0.125rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #374151;
        color: #f3f4f6;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #6b7280;
        margin-right: 0.25rem;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #9ca3af;
    }
    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }
    .dark .select2-dropdown {
        background-color: #1f2937;
        border-color: #4b5563;
    }
    .dark .select2-search__field {
        background-color: #374151;
        color: #f3f4f6;
    }
    .dark .select2-results__option {
        color: #f3f4f6;
    }
    .dark .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold">Tambah Promo Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat promo diskon untuk produk</p>
        </div>
        <a href="{{ route('admin.promo.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm flex items-center transition duration-300">
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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <form id="promoForm">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div>
                        <label for="judul_promo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Promosi <span class="text-red-600">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="judul_promo" name="judul_promo" required maxlength="120" placeholder="Masukkan judul promosi">
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="judul_promo-error"></p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tgl_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai <span class="text-red-600">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="tgl_start" name="tgl_start" required placeholder="Pilih tanggal mulai">
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="tgl_start-error"></p>
                        </div>
                        
                        <div>
                            <label for="tgl_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Berakhir <span class="text-red-600">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="tgl_end" name="tgl_end" required placeholder="Pilih tanggal berakhir">
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="tgl_end-error"></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipe_diskon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Diskon <span class="text-red-600">*</span></label>
                            <select class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="tipe_diskon" name="tipe_diskon" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="persen">Persentase (%)</option>
                                <option value="nominal">Nominal (Rp)</option>
                            </select>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="tipe_diskon-error"></p>
                        </div>
                        
                        <div>
                            <label for="nilai_diskon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai Diskon <span class="text-red-600">*</span></label>
                            <div class="flex">
                                <span id="diskon-prefix" class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    Rp
                                </span>
                                <input type="number" class="flex-1 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-r-md" id="nilai_diskon" name="nilai_diskon" required min="1" placeholder="Nilai diskon">
                                <span id="diskon-suffix" class="hidden items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    %
                                </span>
                            </div>
                            <p id="diskon-help" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan nilai diskon dalam rupiah.</p>
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="nilai_diskon-error"></p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-600">*</span></label>
                        <select class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-aktif</option>
                        </select>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="status-error"></p>
                    </div>
                </div>
                
                <!-- Right Column - Products Selection -->
                <div class="space-y-6">
                    <div>
                        <label for="products" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk yang Dipromo <span class="text-red-600">*</span></label>
                        <select class="select2 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="products" name="products" multiple required>
                            <!-- Products will be loaded from API -->
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih satu atau lebih produk yang akan dimasukkan dalam promosi ini.</p>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 error-message" id="products-error"></p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mt-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Penting</h3>
                        <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2 ml-4 list-disc">
                            <li>Promosi akan otomatis berjalan pada tanggal mulai dan berakhir pada tanggal yang telah ditentukan.</li>
                            <li>Anda dapat mengubah status promosi menjadi non-aktif kapan saja.</li>
                            <li>Untuk diskon persentase, nilai maksimal adalah 100%.</li>
                            <li>Pastikan produk yang dipilih masih tersedia dan aktif di toko.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                <a href="{{ route('admin.promo.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm transition duration-300">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-800 text-white rounded-md text-sm transition duration-300">
                    <i class="bi bi-save mr-1"></i> Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
        // Initialize date pickers
        flatpickr("#tgl_start", {
            locale: "id",
            dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr) {
            // Update the minimum date for end date picker when start date changes
            endDatePicker.set("minDate", dateStr);
        }
        });
        
    const endDatePicker = flatpickr("#tgl_end", {
            locale: "id",
            dateFormat: "Y-m-d",
            minDate: "today"
        });
        
    // Handle diskon type changes
    const tipeDiskonSelect = document.getElementById('tipe_diskon');
        const diskonPrefix = document.getElementById('diskon-prefix');
        const diskonSuffix = document.getElementById('diskon-suffix');
        const diskonHelp = document.getElementById('diskon-help');
    const nilaiDiskonInput = document.getElementById('nilai_diskon');
        
    tipeDiskonSelect.addEventListener('change', function() {
        if (this.value === 'persen') {
                diskonPrefix.classList.add('hidden');
                diskonPrefix.classList.remove('inline-flex');
                diskonSuffix.classList.remove('hidden');
                diskonSuffix.classList.add('inline-flex');
            diskonHelp.textContent = 'Masukkan nilai persentase diskon (1-100).';
            nilaiDiskonInput.classList.remove('rounded-r-md');
            nilaiDiskonInput.classList.add('rounded-none');
            nilaiDiskonInput.max = 100;
        } else {
                diskonPrefix.classList.remove('hidden');
                diskonPrefix.classList.add('inline-flex');
                diskonSuffix.classList.add('hidden');
                diskonSuffix.classList.remove('inline-flex');
                diskonHelp.textContent = 'Masukkan nilai diskon dalam rupiah.';
            nilaiDiskonInput.classList.add('rounded-r-md');
            nilaiDiskonInput.classList.remove('rounded-none');
            nilaiDiskonInput.removeAttribute('max');
        }
    });
    
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
        
        // Initialize Select2
        initializeSelect2();
        
        // Load available products
        loadAvailableProducts();
        
        // Form submission
        const form = document.getElementById('promoForm');
        form.addEventListener('submit', submitForm);
    });
    
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
                loadAvailableProducts();
            })
            .catch(error => {
                console.error('Authentication error:', error);
                // Redirect to login if unauthenticated
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Initialize Select2
    function initializeSelect2() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih produk untuk promosi',
            allowClear: true,
            closeOnSelect: false
        });
    }
    
    // Load available products from API
    function loadAvailableProducts() {
        axios.get('/api/admin/available-products')
            .then(response => {
                if (response.data.status === 'success') {
                    const products = response.data.data;
                    const select = document.getElementById('products');
                    select.innerHTML = '';
                    
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id_produk;
                        option.textContent = `${product.nama_produk} - Rp ${new Intl.NumberFormat('id-ID').format(product.harga_produk)}`;
                        select.appendChild(option);
                    });
                    
                    // Re-initialize select2 after populating options
                    $('.select2').trigger('change');
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal memuat data produk: ' + (error.response?.data?.message || error.message));
            });
    }
    
    // Submit form
    function submitForm(e) {
        e.preventDefault();
        
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
        });
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        
        // Get form data
        const formData = {
            judul_promo: document.getElementById('judul_promo').value,
            tgl_start: document.getElementById('tgl_start').value,
            tgl_end: document.getElementById('tgl_end').value,
            tipe_diskon: document.getElementById('tipe_diskon').value,
            nilai_diskon: document.getElementById('nilai_diskon').value,
            status: document.getElementById('status').value,
            products: Array.from(document.getElementById('products').selectedOptions).map(option => option.value)
        };
        
        // Send API request
        axios.post('/api/admin/promotions', formData)
            .then(response => {
                if (response.data.status === 'success') {
                    showAlert('success', 'Promo berhasil disimpan!');
                    
                    // Redirect to index page after a short delay
                    setTimeout(() => {
                        window.location.href = '/admin/promo';
                    }, 1500);
                }
            })
            .catch(error => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Handle validation errors
                if (error.response && error.response.status === 422 && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        // Convert field name if necessary (e.g., products.0 -> products)
                        const baseField = field.split('.')[0];
                        const errorEl = document.getElementById(`${baseField}-error`);
                        if (errorEl) {
                            errorEl.textContent = errors[field][0];
                        }
                    });
                    showAlert('error', 'Terdapat kesalahan pada form. Silakan periksa kembali.');
                } else {
                    showAlert('error', 'Gagal menyimpan promo: ' + (error.response?.data?.message || error.message));
                }
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
</script>
@endpush 