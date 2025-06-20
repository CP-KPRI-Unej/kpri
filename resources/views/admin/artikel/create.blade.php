@extends('admin.layouts.app')

@section('title', 'Buat Artikel Baru')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
<style>
    .ck-editor__editable {
        min-height: 300px;
        max-height: 600px;
        color: #1f2937;
    }
    
    .dark .ck-editor__editable {
        color: #e5e7eb;
        background-color: #374151;
    }
    
    .dark .ck.ck-editor__main>.ck-editor__editable {
        background-color: #374151;
    }
    
    .dark .ck.ck-toolbar {
        background-color: #1f2937;
        border-color: #4b5563;
    }
    
    .dark .ck.ck-button, 
    .dark .ck.ck-dropdown .ck-dropdown__button {
        color: #e5e7eb;
    }
    
    .dark .ck.ck-button.ck-on, 
    .dark .ck.ck-button:active, 
    .dark .ck.ck-button:focus, 
    .dark .ck.ck-button:hover {
        background-color: #374151;
    }
    
    .tagify {
        --tag-bg: #f97316;
        --tag-hover: #ea580c;
        --tag-text-color: #fff;
        --tags-border-color: #d1d5db;
        --tags-hover-border-color: #9ca3af;
        --tag-border-radius: 4px;
        --tag-pad: 0.3em 0.5em;
    }
    
    .tagify__input {
        min-width: 200px;
    }

    .image-preview {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
    }
    
    .image-container {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }
    
    .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-size: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Buat Artikel Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat artikel baru untuk ditampilkan di website</p>
        </div>
        <a href="/admin/artikel" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <form id="artikelForm" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <!-- Judul Artikel -->
                    <div>
                        <label for="nama_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_artikel" id="nama_artikel" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="nama_artikel_error"></p>
                    </div>
                    
                    <!-- Deskripsi Artikel -->
                    <div>
                        <label for="deskripsi_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konten Artikel <span class="text-red-500">*</span></label>
                        <textarea id="editor" name="deskripsi_artikel" class="hidden"></textarea>
                        <p class="text-red-500 text-xs mt-1 error-message" id="deskripsi_artikel_error"></p>
                    </div>
                    
                    <!-- Tags -->
                    <div>
                        <label for="tags_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags</label>
                        <input id="tags_artikel" name="tags_artikel" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white">
                        <p class="text-red-500 text-xs mt-1 error-message" id="tags_artikel_error"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pisahkan tag dengan koma</p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="mb-6">
                        <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="id_status" name="status" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm w-full">
                            <option value="">Pilih Status</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1 error-message" id="status_error"></p>
                    </div>
                    
                    <!-- Tanggal Rilis -->
                    <div>
                        <label for="tgl_rilis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Rilis <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_rilis" id="tgl_rilis" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" value="{{ date('Y-m-d') }}" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="tgl_rilis_error"></p>
                    </div>
                    
                    <!-- Gambar -->
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Artikel (1-3 gambar) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative" id="dropzone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-orange-600 dark:text-orange-400 hover:text-orange-500 focus-within:outline-none">
                                        <span>Upload gambar</span>
                                        <input id="file-upload" name="gambar[]" type="file" class="sr-only" accept="image/*" multiple onchange="previewImages(event)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </div>
                        <div id="image-preview-container" class="mt-3 flex flex-wrap gap-2"></div>
                        <div id="selected-images-count" class="text-xs text-gray-500 dark:text-gray-400 mt-1">0 gambar dipilih (min 1, max 3)</div>
                        <p class="text-red-500 text-xs mt-1 error-message" id="gambar_error"></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="/admin/artikel" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="button" id="submitBtn" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/37.0.0/super-build/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    let editorInstance; // Global variable to store CKEditor instance
    
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }
        
        // Fetch article statuses
        fetchStatuses();
        
        // Initialize Enhanced CKEditor
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
            toolbar: {
                items: [
                    'exportPDF','exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            language: 'id',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            placeholder: 'Tulis konten artikel di sini...',
            fontSize: {
                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                supportAllValues: true
            },
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            mention: {
                feeds: [
                    {
                        marker: '@',
                        feed: [
                            '@admin', '@editor', '@user'
                        ],
                        minimumCharacters: 1
                    }
                ]
            },
            removePlugins: [
                'CKBox',
                'CKFinder',
                'EasyImage',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType',
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents'
            ]
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });
        
        // Initialize Tagify
        new Tagify(document.getElementById('tags_artikel'), {
            delimiters: ',',
            pattern: /^.{0,20}$/,
            maxTags: 10
        });
        
        // Drag and drop functionality
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file-upload');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropzone.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
        }
        
        function unhighlight() {
            dropzone.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
        }
        
        dropzone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                previewImages({ target: { files: files } });
            }
        }
        
        // Submit form with API
        document.getElementById('submitBtn').addEventListener('click', submitForm);
    });
    
    function fetchStatuses() {
        const token = localStorage.getItem('access_token');
        
        fetch('/api/admin/article-statuses', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch statuses');
            }
            return response.json();
        })
        .then(statuses => {
            const statusSelect = document.getElementById('id_status');
            statusSelect.innerHTML = '<option value="">Pilih Status</option>';
            
            statuses.forEach(status => {
                const option = document.createElement('option');
                option.value = status.id_status;
                option.textContent = status.nama_status;
                statusSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching statuses:', error);
        });
    }
    
    // Image preview functionality
    function previewImages(event) {
        const container = document.getElementById('image-preview-container');
        const countDisplay = document.getElementById('selected-images-count');
        const files = event.target.files;
        
        // Clear previous previews if replacing
        container.innerHTML = '';
        
        // Check if exceeds maximum
        if (files.length > 3) {
            alert('Maksimal 3 gambar diperbolehkan.');
            document.getElementById('file-upload').value = '';
            countDisplay.textContent = '0 gambar dipilih (min 1, max 3)';
            return;
        }
        
        // Update count display
        countDisplay.textContent = `${files.length} gambar dipilih (min 1, max 3)`;
        
        // Create preview for each image
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'image-container';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'image-preview rounded-md shadow-sm';
                imgContainer.appendChild(img);
                
                const removeButton = document.createElement('div');
                removeButton.className = 'remove-image';
                removeButton.innerHTML = '×';
                removeButton.addEventListener('click', function() {
                    // Remove this preview
                    imgContainer.remove();
                    
                    // Create a new FileList without this file
                    const dt = new DataTransfer();
                    const input = document.getElementById('file-upload');
                    
                    for (let i = 0; i < input.files.length; i++) {
                        if (i !== index) {
                            dt.items.add(input.files[i]);
                        }
                    }
                    
                    input.files = dt.files;
                    countDisplay.textContent = `${input.files.length} gambar dipilih (min 1, max 3)`;
                });
                
                imgContainer.appendChild(removeButton);
                container.appendChild(imgContainer);
            };
            
            reader.readAsDataURL(file);
        });
    }
    
    function submitForm() {
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(element => {
            element.textContent = '';
        });
        
        const token = localStorage.getItem('access_token');
        const form = document.getElementById('artikelForm');
        const formData = new FormData(form);
        
        // Add editor content
        formData.set('deskripsi_artikel', editorInstance.getData());
        
        // Submit form
        fetch('/api/admin/articles', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    if (response.status === 422) {
                        // Validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(`${field}_error`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                }
                            });
                        }
                        throw new Error('Validation failed');
                    } else if (response.status === 401) {
                        localStorage.removeItem('access_token');
                        window.location.href = '/admin/login';
                        throw new Error('Unauthorized');
                    } else {
                        throw new Error(data.message || 'An error occurred');
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            // Redirect to article list on success
            window.location.href = '/admin/artikel';
        })
        .catch(error => {
            console.error('Error:', error);
            // Handled by the above catch block
        });
    }
</script>
@endpush 