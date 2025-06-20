@extends('admin.layouts.app')

@section('title', 'Struktur Kepengurusan')

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
    .jabatan-heading {
        background-color: #f8f9fc;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #4e73df;
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
    .bulk-actions-container {
        display: none;
    }
    
    .bulk-actions-container.active {
        display: flex;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Struktur Kepengurusan (<span id="pengurusCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola struktur kepengurusan organisasi</p>
    </div>

    <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="error-message">Terjadi kesalahan saat memuat data.</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('error-container')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
            </svg>
        </button>
    </div>

    <div id="success-container" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="success-message">Operasi berhasil.</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('success-container')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
            </svg>
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="flex items-center">
         
            <select id="filterPeriode" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Memuat periode...</option>
            </select>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.struktur.periode.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Kelola Periode
            </a>
            
            <a href="{{ route('admin.struktur.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Pengurus Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">


        <!-- Loading state -->
        <div id="loading-state" class="p-6 flex justify-center items-center">
            <div class="loading-spinner text-orange-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>

        <!-- Content state -->
        <div id="content-state" class="hidden divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Ketua -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Ketua</h3>

                </div>

                <div id="ketua-container" class="ketua-container">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                </div>
            </div>

            <!-- Sekretaris -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Sekretaris</h3>

                </div>

                <div id="sekretaris-container" class="sekretaris-container">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                </div>
            </div>

            <!-- Bendahara -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Bendahara</h3>

                </div>

                <div id="bendahara-container" class="bendahara-container">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                </div>
            </div>

            <!-- Anggota -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Anggota</h3>
  
                </div>

                <div id="anggota-container" class="anggota-container">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                </div>
            </div>

            <!-- Pengawas -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Pengawas</h3>

                </div>

                <div id="pengawas-container" class="pengawas-container">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                </div>
            </div>
        </div>
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

    // Get periode filter element
    const filterPeriode = document.getElementById('filterPeriode');
    
    // Fetch periode data first
    fetchPeriodeData();
    
    // Add event listener for periode filter change
    filterPeriode.addEventListener('change', function() {
        fetchStrukturData(this.value);
    });

    

    function fetchPeriodeData() {
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
            if (data.success) {
                renderPeriodeOptions(data.data);
                // Fetch struktur data with the first periode in the list
                const firstPeriode = data.data.length > 0 ? data.data[0] : null;
                fetchStrukturData(firstPeriode ? firstPeriode.id_periode : null);
            } else {
                showError('Terjadi kesalahan saat memuat data periode.');
            }
        })
        .catch(error => {
            console.error('Error fetching periode data:', error);
            showError('Tidak dapat memuat data periode. Silakan coba lagi nanti.');
        });
    }

    function renderPeriodeOptions(periodeData) {
        // Clear options
        filterPeriode.innerHTML = '';

        // Add options - select first one by default
        periodeData.forEach((periode, index) => {
            const option = document.createElement('option');
            option.value = periode.id_periode;
            option.textContent = periode.nama_periode;
            if (index === 0) {
                option.selected = true;
            }
            filterPeriode.appendChild(option);
        });
    }

    function fetchStrukturData(periodeId = null) {
        // Show loading state
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('content-state').classList.add('hidden');
        document.getElementById('error-container').classList.add('hidden');

        // Fetch data from API with periode filter if provided
        let url = '/api/admin/struktur';
        if (periodeId) {
            url += `?id_periode=${periodeId}`;
        }

        fetch(url, {
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
                renderStrukturData(data.data);
                updatePengurusCount(data.data);
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

    function updatePengurusCount(strukturData) {
        let count = 0;
        for (const jabatan in strukturData) {
            count += strukturData[jabatan] ? strukturData[jabatan].length : 0;
        }
        document.getElementById('pengurusCount').textContent = count;
    }

    function renderStrukturData(strukturData) {
        // Process data for each jabatan
        renderJabatanData('Ketua', strukturData['Ketua']);
        renderJabatanData('Sekretaris', strukturData['Sekretaris']);
        renderJabatanData('Bendahara', strukturData['Bendahara']);
        renderJabatanData('Anggota', strukturData['Anggota']);
        renderJabatanData('Pengawas', strukturData['Pengawas']);
    }

    function renderJabatanData(jabatan, data) {
        const containerId = jabatan.toLowerCase() + '-container';
        const container = document.getElementById(containerId);

        if (!container) return;

        // Clear container
        container.innerHTML = '';

        // If no data, show empty state
        if (!data || data.length === 0) {
            container.innerHTML = `
                <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg empty-state">
                    <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                </div>
            `;
            return;
        }

        // For positions that can have multiple people (Anggota and Pengawas)
        if (jabatan === 'Anggota' || jabatan === 'Pengawas') {
            let html = '<div class="space-y-3">';

            data.forEach(pengurus => {
                html += `
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg pengurus-item" data-name="${pengurus.nama_pengurus}">
                        <div>
                        <span class="text-gray-800 dark:text-gray-200 font-medium">${pengurus.nama_pengurus}</span>
                            ${pengurus.periode ? `<span class="ml-2 text-xs text-gray-500">(${pengurus.periode.nama_periode})</span>` : ''}
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="/admin/struktur/${pengurus.id_pengurus}/edit"
                               class="text-gray-500 hover:text-blue-500 transition duration-300">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                  class="text-gray-500 hover:text-red-500 transition duration-300 delete-btn"
                                  data-id="${pengurus.id_pengurus}"
                                  data-name="${pengurus.nama_pengurus}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        }
        // For positions that should have just one person (Ketua, Sekretaris, Bendahara)
        else {
            data.forEach(pengurus => {
                container.innerHTML += `
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg pengurus-item" data-name="${pengurus.nama_pengurus}">
                        <div>
                        <span class="text-gray-800 dark:text-gray-200 font-medium">${pengurus.nama_pengurus}</span>
                            ${pengurus.periode ? `<span class="ml-2 text-xs text-gray-500">(${pengurus.periode.nama_periode})</span>` : ''}
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="/admin/struktur/${pengurus.id_pengurus}/edit"
                               class="text-gray-500 hover:text-blue-500 transition duration-300">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button"
                                  class="text-gray-500 hover:text-red-500 transition duration-300 delete-btn"
                                  data-id="${pengurus.id_pengurus}"
                                  data-name="${pengurus.nama_pengurus}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        // Add event listeners to delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                if (confirm(`Apakah Anda yakin ingin menghapus ${name} dari struktur kepengurusan?`)) {
                    deletePengurus(id);
                }
            });
        });
    }

    async function deletePengurus(id) {
        try {
            const response = await fetch(`/api/admin/struktur/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                showSuccess('Data berhasil dihapus');

                // Reload data after a short delay
                setTimeout(() => {
                    fetchStrukturData(filterPeriode.value);
                }, 1000);
            } else {
                showError('Gagal menghapus data. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Error deleting data:', error);
            showError('Terjadi kesalahan saat menghapus data. Silakan coba lagi nanti.');
        }
    }

    function showError(message) {
        const errorContainer = document.getElementById('error-container');
        document.getElementById('error-message').textContent = message;
        errorContainer.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert('error-container');
        }, 5000);
    }
    
    function showSuccess(message) {
        const successContainer = document.getElementById('success-container');
        document.getElementById('success-message').textContent = message;
        successContainer.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert('success-container');
        }, 5000);
    }
    
    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }
});
</script>
@endsection
