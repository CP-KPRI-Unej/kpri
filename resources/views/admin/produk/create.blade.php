@extends('admin.layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Tambah Produk</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Add a new product here.</p>
    </div>

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Images Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Images</label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 flex flex-col items-center justify-center">
                    <div id="image-preview-container" class="hidden mb-4">
                        <img id="image-preview" src="#" alt="Preview" class="max-h-40 rounded-md">
                    </div>
                    <div class="flex items-center justify-center cursor-pointer" onclick="document.getElementById('gambar_produk').click()">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Upload an Image</span>
                    </div>
                    <input type="file" id="gambar_produk" name="gambar_produk" accept="image/*" class="hidden" onchange="previewImage()">
                    @error('gambar_produk')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG, GIF. Max: 2MB.</p>
                </div>
            </div>

            <!-- Product Name -->
            <div>
                <label for="nama_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Product name">
                @error('nama_produk')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category and Stock Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select id="id_kategori" name="id_kategori" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                {{ $kategori->kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="stok_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock saat ini</label>
                    <input type="number" id="stok_produk" name="stok_produk" value="{{ old('stok_produk', 0) }}" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('stok_produk')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Price -->
            <div>
                <label for="harga_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" id="harga_produk" name="harga_produk" value="{{ old('harga_produk', 0) }}" min="0" class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    @error('harga_produk')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="deskripsi_produk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea id="deskripsi_produk" name="deskripsi_produk" rows="5" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Product description...">{{ old('deskripsi_produk') }}</textarea>
                @error('deskripsi_produk')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end space-x-3">
            <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-800 hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save Product
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage() {
        const input = document.getElementById('gambar_produk');
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize summernote if needed
        if (typeof $.fn.summernote !== 'undefined') {
            $('#deskripsi_produk').summernote({
                placeholder: 'Masukkan deskripsi produk...',
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endpush 