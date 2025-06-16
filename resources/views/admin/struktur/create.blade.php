@extends('admin.layouts.app')

@section('title', 'Tambah Anggota Pengurus')

@section('content')
<div class="container px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Anggota Pengurus</h1>
    </div>

    <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
        <ul class="list-disc pl-4" id="error-list"></ul>
    </div>

    <div id="success-container" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="success-message">Data berhasil disimpan!</span>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div id="loading-state" class="hidden flex justify-center items-center p-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>

        <form id="createForm" class="create-form">
            <div class="mb-4">
                <label for="id_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan</label>
                <select name="id_jabatan" id="id_jabatan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Jabatan --</option>
                    <!-- Jabatan options will be loaded dynamically -->
                </select>
                <p class="text-red-500 text-xs mt-1 hidden" id="id_jabatan_error"></p>
            </div>

            <div class="mb-6">
                <label for="nama_pengurus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pengurus</label>
                <input type="text" name="nama_pengurus" id="nama_pengurus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="nama_pengurus_error"></p>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.struktur.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                    Simpan
                </button>
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
    
    function submitForm() {
        // Clear previous errors
        clearErrors();
        hideSuccess();
        
        // Get form data
        const id_jabatan = document.getElementById('id_jabatan').value;
        const nama_pengurus = document.getElementById('nama_pengurus').value;
        
        // Simple validation
        let errors = [];
        
        if (!id_jabatan) {
            errors.push('Jabatan harus dipilih.');
            document.getElementById('id_jabatan_error').textContent = 'Jabatan harus dipilih.';
            document.getElementById('id_jabatan_error').classList.remove('hidden');
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
                    
                    if (data.errors.nama_pengurus) {
                        document.getElementById('nama_pengurus_error').textContent = data.errors.nama_pengurus[0];
                        document.getElementById('nama_pengurus_error').classList.remove('hidden');
                        errorMessages.push(data.errors.nama_pengurus[0]);
                    }
                    
                    showError(errorMessages);
                } else {
                    showError(['Gagal menyimpan data. Silakan coba lagi.']);
                }
            }
        })
        .catch(error => {
            console.error('Error saving data:', error);
            showLoading(false);
            showError(['Terjadi kesalahan saat menyimpan data. Silakan coba lagi nanti.']);
        });
    }
    
    function showLoading(isLoading) {
        const form = document.getElementById('createForm');
        const loadingState = document.getElementById('loading-state');
        
        if (isLoading) {
            form.classList.add('hidden');
            loadingState.classList.remove('hidden');
        } else {
            form.classList.remove('hidden');
            loadingState.classList.add('hidden');
        }
    }
    
    function clearErrors() {
        document.getElementById('error-container').classList.add('hidden');
        document.getElementById('error-list').innerHTML = '';
        
        document.getElementById('id_jabatan_error').textContent = '';
        document.getElementById('id_jabatan_error').classList.add('hidden');
        
        document.getElementById('nama_pengurus_error').textContent = '';
        document.getElementById('nama_pengurus_error').classList.add('hidden');
    }
    
    function showError(messages) {
        const errorContainer = document.getElementById('error-container');
        const errorList = document.getElementById('error-list');
        
        errorList.innerHTML = '';
        
        messages.forEach(message => {
            const li = document.createElement('li');
            li.textContent = message;
            errorList.appendChild(li);
        });
        
        errorContainer.classList.remove('hidden');
    }
    
    function showSuccess(message) {
        const successContainer = document.getElementById('success-container');
        document.getElementById('success-message').textContent = message;
        successContainer.classList.remove('hidden');
    }
    
    function hideSuccess() {
        document.getElementById('success-container').classList.add('hidden');
    }
});
</script>
@endsection 