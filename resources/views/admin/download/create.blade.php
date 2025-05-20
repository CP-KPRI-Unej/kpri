@extends('admin.layouts.app')

@section('title', 'Tambah Item Download')

@section('styles')
<style>
    .dropzone {
        border: 2px dashed #e2e8f0;
        border-radius: 0.5rem;
        padding: 3rem 1rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .dropzone:hover {
        border-color: #4f46e5;
        background-color: #f8fafc;
    }
    .dropzone.dragover {
        border-color: #4f46e5;
        background-color: rgba(79, 70, 229, 0.05);
    }
    .upload-icon {
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .file-info {
        display: none;
        margin-top: 1rem;
    }
    .file-info.active {
        display: block;
    }
    .pdf-icon { color: #dc3545; }
    .doc-icon { color: #0d6efd; }
    .xls-icon { color: #198754; }
    .ppt-icon { color: #fd7e14; }
    .zip-icon { color: #6c757d; }
    .default-icon { color: #6b7280; }
</style>
@endsection

@section('content')
<div class="container max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tambah Item Download</h2>
                <a href="{{ route('admin.download.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm">
                    Kembali ke daftar
                </a>
            </div>
        </div>
        
        <form action="{{ route('admin.download.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="p-6">
                <div class="mb-6">
                    <label for="nama_item" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama File</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                           id="nama_item" name="nama_item" value="{{ old('nama_item') }}" required>
                    @error('nama_item')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <div class="relative dropzone" id="dropzoneArea">
                        <input type="file" class="file-input" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                        <div class="dropzone-content">
                            <i class="bi bi-cloud-arrow-up upload-icon text-3xl"></i>
                            <p class="text-gray-600 dark:text-gray-400">Upload File / Drop item disini</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Maks. 10MB)</p>
                        </div>
                        <div class="file-info bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            <div class="flex items-center">
                                <i class="bi bi-file-earmark file-icon mr-2" id="fileIcon"></i>
                                <div>
                                    <p class="text-sm font-medium" id="fileName">filename.pdf</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" id="fileSize">0 KB</p>
                                </div>
                                <button type="button" class="ml-auto text-gray-400 hover:text-red-500" id="removeFile">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                            id="status" name="status" required>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right flex justify-end space-x-3">
                <a href="{{ route('admin.download.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-800 hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
        const dropzone = document.getElementById('dropzoneArea');
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const fileInfo = document.querySelector('.file-info');
        const removeFileBtn = document.getElementById('removeFile');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight dropzone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        // Remove highlight when item is dragged away or dropped
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);
        
        // Handle file input change
        fileInput.addEventListener('change', handleFiles, false);
        
        // Handle remove file button
        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            fileInfo.classList.remove('active');
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropzone.classList.add('dragover');
        }

        function unhighlight() {
            dropzone.classList.remove('dragover');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                handleFiles();
            }
        }

        function handleFiles() {
            if (fileInput.files.length) {
                const file = fileInput.files[0];
                updateFileInfo(file);
                showFileInfo();
            }
        }

        function updateFileInfo(file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Set appropriate icon based on file extension
            const extension = file.name.split('.').pop().toLowerCase();
            fileIcon.className = 'bi file-icon mr-2';
            
            if (['pdf'].includes(extension)) {
                fileIcon.classList.add('bi-file-earmark-pdf', 'pdf-icon');
            } else if (['doc', 'docx'].includes(extension)) {
                fileIcon.classList.add('bi-file-earmark-word', 'doc-icon');
            } else if (['xls', 'xlsx'].includes(extension)) {
                fileIcon.classList.add('bi-file-earmark-excel', 'xls-icon');
            } else if (['ppt', 'pptx'].includes(extension)) {
                fileIcon.classList.add('bi-file-earmark-ppt', 'ppt-icon');
            } else if (['zip', 'rar'].includes(extension)) {
                fileIcon.classList.add('bi-file-earmark-zip', 'zip-icon');
            } else {
                fileIcon.classList.add('bi-file-earmark', 'default-icon');
            }
        }

        function showFileInfo() {
            fileInfo.classList.add('active');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
</script>
@endpush 