@extends('admin.layouts.app')

@section('title', 'Manajemen Download Item')

@section('styles')
<style>
    .sortable-item {
        cursor: grab;
    }
    .sortable-item.sortable-ghost {
        opacity: 0.4;
        background-color: #e5edff !important;
    }
    .dark .sortable-item.sortable-ghost {
        background-color: #283548 !important;
    }
    .sortable-item.sortable-drag {
        opacity: 0.8;
        background-color: #f3f4f6;
    }
    .dark .sortable-item.sortable-drag {
        background-color: #374151;
    }
    .sort-handle {
        cursor: grab;
    }
    .sort-handle:hover {
        color: #4f46e5;
    }
    .file-icon {
        font-size: 1.5rem;
    }
    .pdf-icon { color: #dc3545; }
    .doc-icon { color: #0d6efd; }
    .xls-icon { color: #198754; }
    .ppt-icon { color: #fd7e14; }
    .zip-icon { color: #6c757d; }
    .default-icon { color: #6c757d; }
    
    @keyframes pulse-highlight {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
    
    .save-order-button {
        animation: pulse-highlight 1.5s infinite;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Download Item ({{ count($downloadItems) }})</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola item download yang tersedia untuk pengguna</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="relative w-full md:w-64">
            <input id="searchDownload" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cari Item Download">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <div class="relative">
                <button id="saveOrderBtn" style="display: none;" class="flex items-center px-3 py-2 border rounded-md text-sm bg-green-600 text-white save-order-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Urutan
                </button>
            </div>
            
            <a href="{{ route('admin.download.create') }}" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Item Download Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <span class="mr-2">#</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Item
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            File
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            Tanggal Upload
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="sortableList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($downloadItems as $key => $item)
                    <tr class="sortable-item hover:bg-gray-50 dark:hover:bg-gray-700" data-id="{{ $item->id_download_item }}">
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-500 rounded border-gray-300">
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 sort-handle transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </svg>
                                <span class="sort-order">{{ $key + 1 }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->nama_item }}</div>
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            @php
                                $extension = pathinfo($item->path_file, PATHINFO_EXTENSION);
                                $iconClass = 'default-icon';
                                $icon = 'file-earmark';
                                
                                if (in_array($extension, ['pdf'])) {
                                    $iconClass = 'pdf-icon';
                                    $icon = 'file-earmark-pdf';
                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                    $iconClass = 'doc-icon';
                                    $icon = 'file-earmark-word';
                                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                    $iconClass = 'xls-icon';
                                    $icon = 'file-earmark-excel';
                                } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                    $iconClass = 'ppt-icon';
                                    $icon = 'file-earmark-ppt';
                                } elseif (in_array($extension, ['zip', 'rar'])) {
                                    $iconClass = 'zip-icon';
                                    $icon = 'file-earmark-zip';
                                }
                            @endphp
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="bi bi-{{ $icon }} {{ $iconClass }} mr-2"></i>
                                <a href="{{ route('admin.download.file', $item->id_download_item) }}" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ basename($item->path_file) }}
                                </a>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            @php
                                $statusClass = $item->status == 'Active' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                            {{ date('d/m/Y', strtotime($item->tgl_upload)) }}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="{{ route('admin.download.file', $item->id_download_item) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-download mr-2"></i> Download
                                        </a>
                                        <a href="{{ route('admin.download.edit', $item->id_download_item) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.download.destroy', $item->id_download_item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="bi bi-trash mr-2"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data item download
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Showing <span class="font-medium">{{ count($downloadItems) }}</span> items
                    </p>
                </div>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span class="hidden md:inline">Drag & drop untuk mengubah urutan</span>
            </div>
        </div>
    </div>
</div>

<div id="orderFeedbackToast" class="fixed bottom-4 right-4 z-50 transform transition-transform duration-300 translate-y-full opacity-0 pointer-events-none">
    <div class="bg-indigo-700 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <span id="orderFeedbackMessage">Urutan telah berubah, jangan lupa untuk menyimpan!</span>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchDownload');
        
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#sortableList tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchValue) ? '' : 'none';
                });
            });
        }
    
        // Toast notification
        const orderFeedbackToast = document.getElementById('orderFeedbackToast');
        
        function showToast(message) {
            const messageEl = document.getElementById('orderFeedbackMessage');
            messageEl.textContent = message;
            orderFeedbackToast.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
            orderFeedbackToast.classList.add('translate-y-0', 'opacity-100');
            
            setTimeout(() => {
                orderFeedbackToast.classList.remove('translate-y-0', 'opacity-100');
                orderFeedbackToast.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
            }, 3000);
        }
        
        // Initialize Sortable
        const sortableList = document.getElementById('sortableList');
        let orderChanged = false;
        
        if (sortableList) {
            const sortable = new Sortable(sortableList, {
                animation: 150,
                handle: '.sort-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onStart: function(evt) {
                    document.body.style.cursor = 'grabbing';
                },
                onEnd: function(evt) {
                    document.body.style.cursor = '';
                    
                    // Update row numbers
                    updateRowNumbers();
                    
                    // Show save button and toast notification
                    if (evt.oldIndex !== evt.newIndex) {
                        orderChanged = true;
                        document.getElementById('saveOrderBtn').style.display = 'flex';
                        showToast('Urutan telah berubah, jangan lupa untuk menyimpan!');
                    }
                }
            });
        }
        
        // Save order button
        document.getElementById('saveOrderBtn').addEventListener('click', function() {
            saveOrder();
        });
        
        // Update row numbers
        function updateRowNumbers() {
            const items = document.querySelectorAll('#sortableList .sortable-item');
            items.forEach((item, index) => {
                item.querySelector('.sort-order').textContent = index + 1;
            });
        }
        
        // Save order to server
        function saveOrder() {
            const items = document.querySelectorAll('#sortableList .sortable-item');
            const orderData = [];
            
            items.forEach((item) => {
                orderData.push(item.dataset.id);
            });
            
            // Disable save button while saving
            const saveBtn = document.getElementById('saveOrderBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
            saveBtn.disabled = true;
            
            // Send AJAX request
            fetch('{{ route('admin.download.update-order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: orderData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message at the top of the page
                    showToast('Urutan berhasil disimpan!');
                    
                    // Reset order changed flag
                    orderChanged = false;
                    saveBtn.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                showToast('Terjadi kesalahan saat menyimpan urutan.');
            })
            .finally(() => {
                // Restore save button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        // Warning before leaving if order changed but not saved
        window.addEventListener('beforeunload', function(e) {
            if (orderChanged) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan urutan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
            }
        });
    });
</script>
@endpush

@endsection 