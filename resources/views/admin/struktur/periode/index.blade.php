@extends('admin.layouts.app')

@section('title', 'Kelola Periode Kepengurusan')

@section('styles')
<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
    }
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
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Kelola Periode Kepengurusan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambah, edit, dan hapus periode kepengurusan</p>
        </div>
        <a href="{{ route('admin.struktur.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="error-message">Terjadi kesalahan saat memuat data.</span>
    </div>

    <div id="success-container" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="success-message">Operasi berhasil.</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex items-center gap-2">
            <button id="add-periode-btn" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Periode Baru
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Loading state -->
        <div id="loading-state" class="p-6 flex justify-center items-center">
            <div class="loading-spinner text-orange-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>

        <!-- Content state -->
        <div id="content-state" class="hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Periode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Mulai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Selesai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="periode-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Periode -->
<div id="periode-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-800 dark:text-gray-200">Tambah Periode Baru</h3>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="periode-form" class="p-4">
            <input type="hidden" id="id_periode" value="">
            
            <div class="mb-4">
                <label for="nama_periode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Periode <span class="text-red-500">*</span></label>
                <input type="text" name="nama_periode" id="nama_periode" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="nama_periode_error"></p>
            </div>
            
            <div class="mb-4">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="tanggal_mulai_error"></p>
            </div>
            
            <div class="mb-4">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="tanggal_selesai_error"></p>
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
                <p class="text-red-500 text-xs mt-1 hidden" id="status_error"></p>
            </div>
            
            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
                <p class="text-red-500 text-xs mt-1 hidden" id="keterangan_error"></p>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancel-form" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</button>
                <button type="submit" id="submit-form" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan</button>
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
    
    // DOM Elements
    const addPeriodeBtn = document.getElementById('add-periode-btn');
    const periodeModal = document.getElementById('periode-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelFormBtn = document.getElementById('cancel-form');
    const periodeForm = document.getElementById('periode-form');
    const modalTitle = document.getElementById('modal-title');
    
    // Event Listeners
    addPeriodeBtn.addEventListener('click', openAddModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelFormBtn.addEventListener('click', closeModal);
    periodeForm.addEventListener('submit', handleFormSubmit);
    
    // Initial data load
    fetchPeriodeData();
    
    function openAddModal() {
        modalTitle.textContent = 'Tambah Periode Baru';
        periodeForm.reset();
        document.getElementById('id_periode').value = '';
        
        // Set default dates
        const today = new Date();
        document.getElementById('tanggal_mulai').valueAsDate = today;
        
        // Default end date is 5 years from now
        const endDate = new Date();
        endDate.setFullYear(endDate.getFullYear() + 5);
        document.getElementById('tanggal_selesai').valueAsDate = endDate;
        
        periodeModal.classList.remove('hidden');
    }
    
    function openEditModal(periode) {
        modalTitle.textContent = 'Edit Periode';
        
        document.getElementById('id_periode').value = periode.id_periode;
        document.getElementById('nama_periode').value = periode.nama_periode;
        document.getElementById('tanggal_mulai').value = formatDateForInput(periode.tanggal_mulai);
        document.getElementById('tanggal_selesai').value = formatDateForInput(periode.tanggal_selesai);
        document.getElementById('status').value = periode.status;
        document.getElementById('keterangan').value = periode.keterangan || '';
        
        periodeModal.classList.remove('hidden');
    }
    
    function closeModal() {
        periodeModal.classList.add('hidden');
        clearFormErrors();
    }
    
    function formatDateForInput(dateStr) {
        const date = new Date(dateStr);
        return date.toISOString().split('T')[0];
    }
    
    function fetchPeriodeData() {
        // Show loading state
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('content-state').classList.add('hidden');
        document.getElementById('error-container').classList.add('hidden');
        
        // Fetch data from API
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
            // Hide loading, show content
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('content-state').classList.remove('hidden');
            
            // Process the data
            if (data.success) {
                renderPeriodeData(data.data);
            } else {
                showError('Terjadi kesalahan saat memuat data.');
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('loading-state').classList.add('hidden');
            showError('Tidak dapat memuat data. Silakan coba lagi nanti.');
        });
    }
    
    function renderPeriodeData(periodes) {
        const tableBody = document.getElementById('periode-table-body');
        tableBody.innerHTML = '';
        
        if (!periodes || periodes.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Belum ada data periode. Silakan tambahkan periode baru.
                </td>
            `;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        // Sort periods by status (active first) then by start date (newest first)
        periodes.sort((a, b) => {
            if (a.status === 'aktif' && b.status !== 'aktif') return -1;
            if (a.status !== 'aktif' && b.status === 'aktif') return 1;
            return new Date(b.tanggal_mulai) - new Date(a.tanggal_mulai);
        });
        
        periodes.forEach(periode => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
            
            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            };
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${periode.nama_periode}</div>
                    ${periode.keterangan ? `<div class="text-xs text-gray-500 dark:text-gray-400">${periode.keterangan}</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${formatDate(periode.tanggal_mulai)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${formatDate(periode.tanggal_selesai)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${periode.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                        ${periode.status === 'aktif' ? 'Aktif' : 'Nonaktif'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <button class="text-blue-500 hover:text-blue-700 edit-btn" data-id="${periode.id_periode}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700 delete-btn" data-id="${periode.id_periode}" data-name="${periode.nama_periode}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Add event listeners to edit and delete buttons
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const periode = periodes.find(p => p.id_periode == id);
                if (periode) {
                    openEditModal(periode);
                }
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                if (confirm(`Apakah Anda yakin ingin menghapus periode "${name}"?`)) {
                    deletePeriode(id);
                }
            });
        });
    }
    
    function handleFormSubmit(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearFormErrors();
        
        // Get form data
        const id_periode = document.getElementById('id_periode').value;
        const nama_periode = document.getElementById('nama_periode').value;
        const tanggal_mulai = document.getElementById('tanggal_mulai').value;
        const tanggal_selesai = document.getElementById('tanggal_selesai').value;
        const status = document.getElementById('status').value;
        const keterangan = document.getElementById('keterangan').value;
        
        // Simple validation
        let errors = {};
        let hasErrors = false;
        
        if (!nama_periode) {
            errors.nama_periode = 'Nama periode harus diisi';
            hasErrors = true;
        }
        
        if (!tanggal_mulai) {
            errors.tanggal_mulai = 'Tanggal mulai harus diisi';
            hasErrors = true;
        }
        
        if (!tanggal_selesai) {
            errors.tanggal_selesai = 'Tanggal selesai harus diisi';
            hasErrors = true;
        }
        
        if (tanggal_mulai && tanggal_selesai && new Date(tanggal_mulai) > new Date(tanggal_selesai)) {
            errors.tanggal_selesai = 'Tanggal selesai harus setelah tanggal mulai';
            hasErrors = true;
        }
        
        if (!status) {
            errors.status = 'Status harus dipilih';
            hasErrors = true;
        }
        
        if (hasErrors) {
            displayFormErrors(errors);
            return;
        }
        
        // Prepare data for API
        const periodeData = {
            nama_periode,
            tanggal_mulai,
            tanggal_selesai,
            status,
            keterangan
        };
        
        // Show loading
        const submitBtn = document.getElementById('submit-form');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner w-4 h-4 mr-2"></span> Menyimpan...';
        
        // Determine if this is an add or edit operation
        const isEdit = id_periode !== '';
        const url = isEdit ? `/api/admin/periode/${id_periode}` : '/api/admin/periode';
        const method = isEdit ? 'PUT' : 'POST';
        
        // Send data to API
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(periodeData)
        })
        .then(response => response.json())
        .then(data => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            
            if (data.success) {
                // Show success message
                showSuccess(isEdit ? 'Periode berhasil diperbarui' : 'Periode baru berhasil ditambahkan');
                
                // Close modal
                closeModal();
                
                // Refresh data
                fetchPeriodeData();
            } else {
                // Show validation errors
                if (data.errors) {
                    displayFormErrors(data.errors);
                } else {
                    // Show general error
                    showError('Terjadi kesalahan saat menyimpan data.');
                }
            }
        })
        .catch(error => {
            console.error('Error saving data:', error);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            showError('Tidak dapat menyimpan data. Silakan coba lagi nanti.');
        });
    }
    
    function deletePeriode(id) {
        // Show loading
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('content-state').classList.add('hidden');
        
        fetch(`/api/admin/periode/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Periode berhasil dihapus');
                fetchPeriodeData();
            } else {
                showError(data.message || 'Gagal menghapus periode.');
                document.getElementById('loading-state').classList.add('hidden');
                document.getElementById('content-state').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error deleting periode:', error);
            showError('Tidak dapat menghapus periode. Silakan coba lagi nanti.');
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('content-state').classList.remove('hidden');
        });
    }
    
    function displayFormErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(`${field}_error`);
            if (errorElement) {
                errorElement.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                errorElement.classList.remove('hidden');
            }
        });
    }
    
    function clearFormErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    }
    
    function showError(message) {
        const errorContainer = document.getElementById('error-container');
        document.getElementById('error-message').textContent = message;
        errorContainer.classList.remove('hidden');
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    function showSuccess(message) {
        const successContainer = document.getElementById('success-container');
        document.getElementById('success-message').textContent = message;
        successContainer.classList.remove('hidden');
        successContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Hide success message after 3 seconds
        setTimeout(() => {
            successContainer.classList.add('hidden');
        }, 3000);
    }
});
</script>
@endsection 