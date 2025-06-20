@extends('admin.layouts.app')

@section('title', 'Tambah Anggota Pengurus')

@section('styles')
<style>
    .loading-spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
    }
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Tambah Anggota Pengurus</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambah anggota baru ke struktur kepengurusan</p>
        </div>
        <a href="/admin/struktur" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
        <ul class="list-disc pl-4" id="error-list"></ul>
    </div>

    <div id="success-container" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="success-message">Data berhasil disimpan!</span>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div id="loading-state" class="hidden flex justify-center items-center p-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
            <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>

        <form id="createForm" class="create-form">
            <div class="mb-4">
                <label for="id_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan <span class="text-red-500">*</span></label>
                <select name="id_jabatan" id="id_jabatan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Pilih Jabatan --</option>
                    <!-- Jabatan options will be loaded dynamically -->
                </select>
                <p class="text-red-500 text-xs mt-1 hidden" id="id_jabatan_error"></p>
            </div>

            <div class="mb-4">
                <label for="id_periode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode <span class="text-red-500">*</span></label>
                <select name="id_periode" id="id_periode" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Pilih Periode --</option>
                    <!-- Periode options will be loaded dynamically -->
                </select>
                <p class="text-red-500 text-xs mt-1 hidden" id="id_periode_error"></p>
            </div>

            <div class="mb-6">
                <label for="nama_pengurus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pengurus <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pengurus" id="nama_pengurus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="nama_pengurus_error"></p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.struktur.index') }}" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get access token from localStorage
    const token = localStorage.getItem('access_token');
    
    if (!token) {
        // Redirect to login if no token
        window.location.href = '/admin/login';
        return;
    }
    
    // Get query parameter for pre-selecting jabatan
    const urlParams = new URLSearchParams(window.location.search);
    const preselectedJabatan = urlParams.get('jabatan');
    
    // Fetch available jabatan
    fetchJabatan();
    
    // Fetch available periode
    fetchPeriode();
    
    // Handle form submission
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm();
    });
    
    function fetchJabatan() {
        showLoading(true);
        
        fetch('/api/admin/jabatan', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            showLoading(false);
            
            if (data.success) {
                populateJabatanDropdown(data.data);
            } else {
                showError(['Tidak dapat memuat data jabatan.']);
            }
        })
        .catch(error => {
            console.error('Error fetching jabatan:', error);
            showLoading(false);
            showError(['Tidak dapat memuat data jabatan. Silakan coba lagi nanti.']);
        });
    }
    
    function fetchPeriode() {
        fetch('/api/admin/periode', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                populatePeriodeDropdown(data.data);
            } else {
                showError(['Tidak dapat memuat data periode.']);
            }
        })
        .catch(error => {
            console.error('Error fetching periode:', error);
            showError(['Tidak dapat memuat data periode. Silakan coba lagi nanti.']);
        });
    }
    
    function populateJabatanDropdown(jabatan) {
        const selectElement = document.getElementById('id_jabatan');
        
        // Keep the default option
        selectElement.innerHTML = '<option value="">-- Pilih Jabatan --</option>';
        
        // Add options for each jabatan
        jabatan.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id_jabatan;
            option.textContent = item.nama_jabatan;
            
            // Check if this matches the preselected jabatan from URL
            if (preselectedJabatan && item.nama_jabatan === preselectedJabatan) {
                option.selected = true;
            }
            
            selectElement.appendChild(option);
        });
    }
    
    function populatePeriodeDropdown(periodes) {
        const selectElement = document.getElementById('id_periode');
        
        // Keep the default option
        selectElement.innerHTML = '<option value="">-- Pilih Periode --</option>';
        
        // Sort periodes: active first, then by start date (newest first)
        periodes.sort((a, b) => {
            if (a.status === 'aktif' && b.status !== 'aktif') return -1;
            if (a.status !== 'aktif' && b.status === 'aktif') return 1;
            return new Date(b.tanggal_mulai) - new Date(a.tanggal_mulai);
        });
        
        // Add all periodes with active one selected
        periodes.forEach(periode => {
            const option = document.createElement('option');
            option.value = periode.id_periode;
            option.textContent = periode.status === 'aktif' ? 
                `${periode.nama_periode} (Aktif)` : 
                `${periode.nama_periode} (${formatDate(periode.tanggal_mulai)} - ${formatDate(periode.tanggal_selesai)})`;
                
            if (periode.status === 'aktif') {
                option.selected = true;
            }
            
            selectElement.appendChild(option);
        });
    }
    
    // Helper function to format dates
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
        });
    }
    
    function submitForm() {
        // Clear previous errors
        clearErrors();
        hideSuccess();
        
        // Get form data
        const id_jabatan = document.getElementById('id_jabatan').value;
        const id_periode = document.getElementById('id_periode').value;
        const nama_pengurus = document.getElementById('nama_pengurus').value;
        
        // Simple validation
        let errors = [];
        
        if (!id_jabatan) {
            errors.push('Jabatan harus dipilih.');
            document.getElementById('id_jabatan_error').textContent = 'Jabatan harus dipilih.';
            document.getElementById('id_jabatan_error').classList.remove('hidden');
        }
        
        if (!id_periode) {
            errors.push('Periode harus dipilih.');
            document.getElementById('id_periode_error').textContent = 'Periode harus dipilih.';
            document.getElementById('id_periode_error').classList.remove('hidden');
        }
        
        if (!nama_pengurus) {
            errors.push('Nama pengurus harus diisi.');
            document.getElementById('nama_pengurus_error').textContent = 'Nama pengurus harus diisi.';
            document.getElementById('nama_pengurus_error').classList.remove('hidden');
        }
        
        if (errors.length > 0) {
            return;
        }
        
        // Send data to API
        showLoading(true);
        
        fetch('/api/admin/struktur', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                id_jabatan: id_jabatan,
                id_periode: id_periode,
                nama_pengurus: nama_pengurus
            })
        })
        .then(response => response.json())
        .then(data => {
            showLoading(false);
            
            if (data.success) {
                showSuccess('Data pengurus berhasil ditambahkan!');
                
                // Reset form
                document.getElementById('createForm').reset();
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = "{{ route('admin.struktur.index') }}";
                }, 2000);
            } else {
                // Show validation errors
                if (data.errors) {
                    let errorMessages = [];
                    
                    if (data.errors.id_jabatan) {
                        document.getElementById('id_jabatan_error').textContent = data.errors.id_jabatan[0];
                        document.getElementById('id_jabatan_error').classList.remove('hidden');
                        errorMessages.push(data.errors.id_jabatan[0]);
                    }
                    
                    if (data.errors.id_periode) {
                        document.getElementById('id_periode_error').textContent = data.errors.id_periode[0];
                        document.getElementById('id_periode_error').classList.remove('hidden');
                        errorMessages.push(data.errors.id_periode[0]);
                    }
                    
                    if (data.errors.nama_pengurus) {
                        document.getElementById('nama_pengurus_error').textContent = data.errors.nama_pengurus[0];
                        document.getElementById('nama_pengurus_error').classList.remove('hidden');
                        errorMessages.push(data.errors.nama_pengurus[0]);
                    }
                    
                    if (errorMessages.length > 0) {
                    showError(errorMessages);
                    } else {
                        showError(['Terjadi kesalahan saat menyimpan data.']);
                    }
                } else {
                    showError(['Terjadi kesalahan saat menyimpan data.']);
                }
            }
        })
        .catch(error => {
            console.error('Error saving data:', error);
            showLoading(false);
            showError(['Tidak dapat menyimpan data. Silakan coba lagi nanti.']);
        });
    }
    
    function showLoading(isLoading) {
        const loadingElement = document.getElementById('loading-state');
        const formElement = document.getElementById('createForm');
        
        if (isLoading) {
            loadingElement.classList.remove('hidden');
            formElement.classList.add('opacity-50', 'pointer-events-none');
        } else {
            loadingElement.classList.add('hidden');
            formElement.classList.remove('opacity-50', 'pointer-events-none');
        }
    }
    
    function showError(messages) {
        const errorContainer = document.getElementById('error-container');
        const errorList = document.getElementById('error-list');
        
        // Clear previous errors
        errorList.innerHTML = '';
        
        // Add each error message
        messages.forEach(message => {
            const listItem = document.createElement('li');
            listItem.textContent = message;
            errorList.appendChild(listItem);
        });
        
        // Show the error container
        errorContainer.classList.remove('hidden');
        
        // Scroll to the error container
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    function clearErrors() {
        document.getElementById('error-container').classList.add('hidden');
        document.getElementById('error-list').innerHTML = '';
        
        // Hide all field-specific error messages
        document.querySelectorAll('.text-red-500.text-xs').forEach(element => {
            element.classList.add('hidden');
            element.textContent = '';
        });
    }
    
    function showSuccess(message) {
        const successContainer = document.getElementById('success-container');
        const successMessage = document.getElementById('success-message');
        
        successMessage.textContent = message;
        successContainer.classList.remove('hidden');
        
        // Scroll to the success container
        successContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    function hideSuccess() {
        document.getElementById('success-container').classList.add('hidden');
    }
});
</script>
@endsection 