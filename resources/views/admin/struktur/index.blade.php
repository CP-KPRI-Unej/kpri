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
</style>
@endsection

@section('content')
<div class="w-full px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Struktur Kepengurusan</h1>
        <a href="{{ route('admin.struktur.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md flex items-center gap-2 text-sm hover:bg-blue-700 transition duration-300">
            <i class="bi bi-plus-lg"></i> Pengurus Baru
        </a>
    </div>

    <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
        <span id="error-message">Terjadi kesalahan saat memuat data.</span>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Struktur Kepengurusan KPRI UNEJ</h2>
        </div>

        <!-- Loading state -->
        <div id="loading-state" class="p-6 flex justify-center items-center">
            <div class="loading-spinner text-blue-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
        </div>

        <!-- Content state -->
        <div id="content-state" class="hidden divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Ketua -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Ketua</h3>
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Ketua" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1 ketua-add-link">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
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
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Sekretaris" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1 sekretaris-add-link">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
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
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Bendahara" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1 bendahara-add-link">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
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
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Anggota" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
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
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Pengawas" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
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
    
    fetchStrukturData();
    
    function fetchStrukturData() {
        // Show loading state
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('content-state').classList.add('hidden');
        document.getElementById('error-container').classList.add('hidden');
        
        // Fetch data from API
        fetch('/api/admin/struktur', {
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
    
    function renderStrukturData(strukturData) {
        // Process data for each jabatan
        renderJabatanData('Ketua', strukturData['Ketua']);
        renderJabatanData('Sekretaris', strukturData['Sekretaris']);
        renderJabatanData('Bendahara', strukturData['Bendahara']);
        renderJabatanData('Anggota', strukturData['Anggota']);
        renderJabatanData('Pengawas', strukturData['Pengawas']);
        
        // Hide add links for positions that already have data (except Anggota and Pengawas)
        if (strukturData['Ketua'] && strukturData['Ketua'].length > 0) {
            document.querySelector('.ketua-add-link').classList.add('hidden');
        }
        
        if (strukturData['Sekretaris'] && strukturData['Sekretaris'].length > 0) {
            document.querySelector('.sekretaris-add-link').classList.add('hidden');
        }
        
        if (strukturData['Bendahara'] && strukturData['Bendahara'].length > 0) {
            document.querySelector('.bendahara-add-link').classList.add('hidden');
        }
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
                <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
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
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-800 dark:text-gray-200 font-medium">${pengurus.nama_pengurus}</span>
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
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-800 dark:text-gray-200 font-medium">${pengurus.nama_pengurus}</span>
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
                showError(['Data berhasil dihapus']);
                
                // Reload data after a short delay
                setTimeout(() => {
                    fetchStrukturData();
                }, 1000);
            } else {
                showError(['Gagal menghapus data. Silakan coba lagi.']);
            }
        } catch (error) {
            console.error('Error deleting data:', error);
            showError(['Terjadi kesalahan saat menghapus data. Silakan coba lagi nanti.']);
        }
    }
    
    function showError(message) {
        const errorContainer = document.getElementById('error-container');
        document.getElementById('error-message').textContent = message;
        errorContainer.classList.remove('hidden');
    }
});
</script>
@endsection 