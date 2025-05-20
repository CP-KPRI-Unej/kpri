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

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <form action="{{ route('admin.promo.store') }}" method="POST">
            @csrf
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div>
                        <label for="judul_promo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Promosi <span class="text-red-600">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('judul_promo') border-red-500 @enderror" id="judul_promo" name="judul_promo" value="{{ old('judul_promo') }}" required maxlength="120" placeholder="Masukkan judul promosi">
                        @error('judul_promo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tgl_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai <span class="text-red-600">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tgl_start') border-red-500 @enderror" id="tgl_start" name="tgl_start" value="{{ old('tgl_start') }}" required placeholder="Pilih tanggal mulai">
                            @error('tgl_start')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="tgl_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Berakhir <span class="text-red-600">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tgl_end') border-red-500 @enderror" id="tgl_end" name="tgl_end" value="{{ old('tgl_end') }}" required placeholder="Pilih tanggal berakhir">
                            @error('tgl_end')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipe_diskon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Diskon <span class="text-red-600">*</span></label>
                            <select class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('tipe_diskon') border-red-500 @enderror" id="tipe_diskon" name="tipe_diskon" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="persen" {{ old('tipe_diskon') == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                                <option value="nominal" {{ old('tipe_diskon') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                            @error('tipe_diskon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nilai_diskon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai Diskon <span class="text-red-600">*</span></label>
                            <div class="flex">
                                <span id="diskon-prefix" class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    Rp
                                </span>
                                <input type="number" class="flex-1 px-3 py-2 border focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nilai_diskon') border-red-500 @enderror rounded-r-md" id="nilai_diskon" name="nilai_diskon" value="{{ old('nilai_diskon') }}" required min="1" placeholder="Nilai diskon">
                                <span id="diskon-suffix" class="hidden items-center px-3 py-2 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                                    %
                                </span>
                            </div>
                            <p id="diskon-help" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan nilai diskon dalam rupiah.</p>
                            @error('nilai_diskon')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-600">*</span></label>
                        <select class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('status') border-red-500 @enderror" id="status" name="status" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Right Column - Products Selection -->
                <div class="space-y-6">
                    <div>
                        <label for="produk_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk yang Dipromo <span class="text-red-600">*</span></label>
                        <select class="select2 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('produk_ids') border-red-500 @enderror" id="produk_ids" name="produk_ids[]" multiple required>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id_produk }}" {{ in_array($produk->id_produk, old('produk_ids', [])) ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih satu atau lebih produk yang akan dimasukkan dalam promosi ini.</p>
                        @error('produk_ids')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                <button type="submit" class="px-4 py-2 bg-indigo-700 hover:bg-indigo-800 text-white rounded-md text-sm transition duration-300">
                    <i class="bi bi-save mr-1"></i> Simpan Promo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'Pilih produk untuk promosi',
            width: '100%'
        });
        
        // Initialize date pickers
        flatpickr("#tgl_start", {
            locale: "id",
            dateFormat: "Y-m-d",
            minDate: "today"
        });
        
        flatpickr("#tgl_end", {
            locale: "id",
            dateFormat: "Y-m-d",
            minDate: "today"
        });
        
        // Handle discount type change
        const tipeDiskon = document.getElementById('tipe_diskon');
        const diskonPrefix = document.getElementById('diskon-prefix');
        const diskonSuffix = document.getElementById('diskon-suffix');
        const diskonHelp = document.getElementById('diskon-help');
        const nilaiDiskon = document.getElementById('nilai_diskon');
        
        tipeDiskon.addEventListener('change', updateDiskonType);
        
        function updateDiskonType() {
            const type = tipeDiskon.value;
            
            if (type === 'persen') {
                diskonPrefix.classList.add('hidden');
                diskonPrefix.classList.remove('inline-flex');
                diskonSuffix.classList.remove('hidden');
                diskonSuffix.classList.add('inline-flex');
                diskonHelp.textContent = 'Masukkan nilai diskon dalam persentase (1-100).';
                nilaiDiskon.setAttribute('max', '100');
            } else if (type === 'nominal') {
                diskonPrefix.classList.remove('hidden');
                diskonPrefix.classList.add('inline-flex');
                diskonSuffix.classList.add('hidden');
                diskonSuffix.classList.remove('inline-flex');
                diskonHelp.textContent = 'Masukkan nilai diskon dalam rupiah.';
                nilaiDiskon.removeAttribute('max');
            } else {
                diskonPrefix.classList.remove('hidden');
                diskonPrefix.classList.add('inline-flex');
                diskonSuffix.classList.add('hidden');
                diskonSuffix.classList.remove('inline-flex');
                diskonHelp.textContent = 'Pilih tipe diskon terlebih dahulu.';
            }
        }
        
        // Run on page load
        updateDiskonType();
    });
</script>
@endpush 