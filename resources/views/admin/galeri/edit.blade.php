@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')

@section('styles')
<style>
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }
    
    .upload-area:hover, .upload-area.dragover {
        border-color: #6366f1;
        background-color: #f3f4f6;
    }
    
    .dark .upload-area {
        background-color: #374151;
        border-color: #4b5563;
    }
    
    .dark .upload-area:hover, .dark .upload-area.dragover {
        border-color: #6366f1;
        background-color: #1f2937;
    }
    
    .current-image {
        border-radius: 0.5rem;
        max-height: 200px;
        margin: 0 auto;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Edit Foto: {{ $galeri->nama_galeri }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data atau gambar foto</p>
    </div>
    
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden p-6 max-w-xl mx-auto">
        <form action="{{ route('admin.galeri.update', $galeri->id_galeri) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="nama_galeri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama File</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" 
                       id="nama_galeri" name="nama_galeri" value="{{ old('nama_galeri', $galeri->nama_galeri) }}" maxlength="30" required>
            </div>
            
            <div class="hidden">
                <select id="id_status" name="id_status" required>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}" {{ old('id_status', $galeri->id_status) == $status->id_status ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-5">
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar Saat Ini:</div>
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $galeri->gambar_galeri) }}" alt="{{ $galeri->nama_galeri }}" class="current-image">
                </div>
            </div>
            
            <div class="mb-5" id="uploadContainer">
                <div class="upload-area" id="dropZone">
                    <div class="flex flex-col items-center justify-center py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Upload File</span> / Drop Item disini</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG, GIF (Maksimal 2MB)</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                        
                        <input type="file" class="hidden" id="gambar_galeri" name="gambar_galeri" accept="image/*">
                    </div>
                </div>
                <div id="previewContainer" class="mt-4 text-center hidden">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar Baru:</div>
                    <img id="imagePreview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow">
                    <button type="button" id="removeImage" class="mt-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        <i class="bi bi-x-circle"></i> Hapus
                    </button>
                </div>
            </div>
            
            <div class="flex justify-between mt-6">
                <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-800 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('gambar_galeri');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeButton = document.getElementById('removeImage');
        
        // Trigger file input when clicking on the drop zone
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });
        
        // Handle file selection
        fileInput.addEventListener('change', handleFileSelect);
        
        // Handle drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropZone.classList.add('dragover');
        }
        
        function unhighlight() {
            dropZone.classList.remove('dragover');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect();
        }
        
        function handleFileSelect() {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        
        // Remove selected image
        removeButton.addEventListener('click', function() {
            fileInput.value = '';
            previewContainer.classList.add('hidden');
            imagePreview.src = '#';
        });
    });
</script>
@endpush 