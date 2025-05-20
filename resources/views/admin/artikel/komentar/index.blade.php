@extends('admin.layouts.app')

@section('styles')
<style>
    .action-dropdown {
        position: absolute !important;
        min-width: 12rem;
        right: 0;
        z-index: 100;
    }
    
    .relative {
        position: relative !important;
    }
    
    .table-container {
        overflow: visible !important;
    }

    .table-container tr {
        position: relative;
    }

    .dropdown-container {
        position: static;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-6 py-4">
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold">Komentar Artikel: {{ $artikel->nama_artikel }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola komentar untuk artikel ini</p>
        </div>
        <a href="{{ route('admin.artikel.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
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

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Status Tabs -->
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <a href="{{ route('admin.artikel.komentar.index', ['artikelId' => $artikel->id_artikel, 'status' => 'all']) }}" 
                   class="inline-block p-4 rounded-t-lg border-b-2 {{ $status == 'all' ? 'text-orange-600 border-orange-600 active dark:text-orange-500 dark:border-orange-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Semua 
                    <span class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-gray-500 rounded-full">
                        {{ $artikel->komentar->count() }}
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.artikel.komentar.index', ['artikelId' => $artikel->id_artikel, 'status' => 'pending']) }}"
                   class="inline-block p-4 rounded-t-lg border-b-2 {{ $status == 'pending' ? 'text-orange-600 border-orange-600 active dark:text-orange-500 dark:border-orange-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Pending
                    <span class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-yellow-500 rounded-full">
                        {{ $artikel->komentar->where('status', 'pending')->count() }}
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.artikel.komentar.index', ['artikelId' => $artikel->id_artikel, 'status' => 'approved']) }}"
                   class="inline-block p-4 rounded-t-lg border-b-2 {{ $status == 'approved' ? 'text-orange-600 border-orange-600 active dark:text-orange-500 dark:border-orange-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Disetujui
                    <span class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-green-500 rounded-full">
                        {{ $artikel->komentar->where('status', 'approved')->count() }}
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.artikel.komentar.index', ['artikelId' => $artikel->id_artikel, 'status' => 'rejected']) }}"
                   class="inline-block p-4 rounded-t-lg border-b-2 {{ $status == 'rejected' ? 'text-orange-600 border-orange-600 active dark:text-orange-500 dark:border-orange-500' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}">
                    Ditolak
                    <span class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                        {{ $artikel->komentar->where('status', 'rejected')->count() }}
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-visible table-container">
        @if(count($komentars) > 0)
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="text-gray-500 dark:text-gray-400 text-sm">
                Menampilkan {{ count($komentars) }} komentar
            </div>
            <div class="flex space-x-2">
                @if($status == 'pending' && $artikel->komentar->where('status', 'pending')->count() > 0)
                <form id="approveAllForm" action="{{ route('admin.komentar.update-status', 0) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <input type="hidden" name="artikelId" value="{{ $artikel->id_artikel }}">
                    <button type="button" onclick="confirmMultipleAction('approveAllForm', 'Apakah Anda yakin ingin menyetujui semua komentar yang menunggu?')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">
                        <i class="bi bi-check-all mr-1"></i> Setujui Semua Pending
                    </button>
                </form>
                @endif
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                        <i class="bi bi-gear mr-1"></i> Tindakan Terpilih 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 action-dropdown">
                        <div class="py-1">
                            <button type="button" onclick="bulkUpdateStatus('approved')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-check-circle mr-2"></i> Setujui Terpilih
                            </button>
                            <button type="button" onclick="bulkUpdateStatus('rejected')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-x-circle mr-2"></i> Tolak Terpilih
                            </button>
                            <button type="button" onclick="bulkUpdateStatus('pending')" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-clock mr-2"></i> Pending Terpilih
                            </button>
                            <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                            <button type="button" onclick="confirmMultipleDelete()" class="w-full text-left px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300">
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nama Pengomentar
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Isi Komentar
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="relative px-4 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($komentars as $komentar)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dropdown-container">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <input type="checkbox" value="{{ $komentar->id_komentar }}" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300">
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $komentar->nama_pengomentar }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $komentar->isi_komentar }}
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @php
                            $statusClass = '';
                            if($komentar->status == 'approved') {
                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                $statusBadge = '<i class="bi bi-check-circle"></i> Disetujui';
                            } elseif($komentar->status == 'rejected') {
                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                $statusBadge = '<i class="bi bi-x-circle"></i> Ditolak';
                            } else {
                                $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                $statusBadge = '<i class="bi bi-clock"></i> Menunggu';
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {!! $statusBadge !!}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $komentar->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50 action-dropdown">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <!-- Direct status actions -->
                                    <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">Ubah Status</div>

                                    <!-- Approve Comment -->
                                    @if($komentar->status != 'approved')
                                    <form action="{{ route('admin.komentar.update-status', $komentar->id_komentar) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-green-700 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-check-circle mr-2"></i> Setujui
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Reject Comment -->
                                    @if($komentar->status != 'rejected')
                                    <form action="{{ route('admin.komentar.update-status', $komentar->id_komentar) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-yellow-700 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-x-circle mr-2"></i> Tolak
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Return to Pending -->
                                    @if($komentar->status != 'pending')
                                    <form action="{{ route('admin.komentar.update-status', $komentar->id_komentar) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-blue-700 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-arrow-counterclockwise mr-2"></i> Set Pending
                                        </button>
                                    </form>
                                    @endif

                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                    <!-- Delete Comment -->
                                    <form action="{{ route('admin.komentar.destroy', $komentar->id_komentar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
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
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p>Tidak ada komentar untuk ditampilkan</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Function to handle the select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('table tbody input[type="checkbox"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    // Function to confirm multiple deletion
    function confirmMultipleDelete() {
        var selectedIds = [];
        var checkboxes = document.querySelectorAll('table tbody input[type="checkbox"]:checked');
        
        for (var checkbox of checkboxes) {
            selectedIds.push(checkbox.value);
        }
        
        if (selectedIds.length === 0) {
            alert('Silakan pilih setidaknya satu komentar.');
            return;
        }
        
        if (confirm('Apakah Anda yakin ingin menghapus ' + selectedIds.length + ' komentar terpilih?')) {
            // Create a form and submit it
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.komentar.destroy", 0) }}';
            
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            for (var id of selectedIds) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Function to confirm multiple action
    function confirmMultipleAction(formId, message) {
        if (confirm(message)) {
            document.getElementById(formId).submit();
        }
    }

    // Function to bulk update status
    function bulkUpdateStatus(status) {
        var selectedIds = [];
        var checkboxes = document.querySelectorAll('table tbody input[type="checkbox"]:checked');
        
        for (var checkbox of checkboxes) {
            selectedIds.push(checkbox.value);
        }
        
        if (selectedIds.length === 0) {
            alert('Silakan pilih setidaknya satu komentar.');
            return;
        }
        
        var statusText = status === 'approved' ? 'menyetujui' : (status === 'rejected' ? 'menolak' : 'mengubah status menjadi pending');
        
        if (confirm('Apakah Anda yakin ingin ' + statusText + ' ' + selectedIds.length + ' komentar terpilih?')) {
            // Create a form and submit it
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.komentar.update-status", 0) }}';
            
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            
            var statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);
            
            for (var id of selectedIds) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection 