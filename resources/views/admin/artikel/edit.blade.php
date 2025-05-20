@extends('admin.layouts.app')

@section('title', 'Edit Artikel')

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
        --tag-bg: #4f46e5;
        --tag-hover: #4338ca;
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
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Artikel</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Edit artikel yang sudah ada</p>
        </div>
        <a href="{{ route('admin.artikel.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden p-6">
        <form action="{{ route('admin.artikel.update', $artikel->id_artikel) }}" method="POST" enctype="multipart/form-data" id="artikelForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <!-- Judul Artikel -->
                    <div>
                        <label for="nama_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_artikel" id="nama_artikel" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('nama_artikel') border-red-500 @enderror" value="{{ old('nama_artikel', $artikel->nama_artikel) }}" required>
                        @error('nama_artikel')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Deskripsi Artikel -->
                    <div>
                        <label for="deskripsi_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konten Artikel <span class="text-red-500">*</span></label>
                        <textarea id="editor" name="deskripsi_artikel" class="hidden">{{ old('deskripsi_artikel', $artikel->deskripsi_artikel) }}</textarea>
                        @error('deskripsi_artikel')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tags -->
                    <div>
                        <label for="tags_artikel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags</label>
                        <input id="tags_artikel" name="tags_artikel" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('tags_artikel') border-red-500 @enderror" value="{{ old('tags_artikel', $artikel->tags_artikel) }}">
                        @error('tags_artikel')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pisahkan tag dengan koma</p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Status -->
                    <div>
                        <label for="id_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="id_status" id="id_status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('id_status') border-red-500 @enderror" required>
                            <option value="">Pilih Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id_status }}" {{ old('id_status', $artikel->id_status) == $status->id_status ? 'selected' : '' }}>
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tanggal Rilis -->
                    <div>
                        <label for="tgl_rilis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Rilis <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_rilis" id="tgl_rilis" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white @error('tgl_rilis') border-red-500 @enderror" value="{{ old('tgl_rilis', date('Y-m-d', strtotime($artikel->tgl_rilis))) }}" required>
                        @error('tgl_rilis')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Current Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Saat Ini ({{ $artikel->images->count() }}/3)</label>
                        <div class="flex flex-wrap gap-3 mt-2">
                            @foreach($artikel->images as $image)
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $image->gambar) }}" class="image-preview rounded-md shadow-sm" alt="Current Image">
                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_image_{{ $image->id }}" class="hidden delete-image-checkbox">
                                    <label for="delete_image_{{ $image->id }}" class="w-6 h-6 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full cursor-pointer shadow-sm toggle-delete-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </label>
                                </div>
                                <div class="text-xs text-center mt-1 image-mark">Gambar #{{ $loop->iteration }}</div>
                            </div>
                            @endforeach
                        </div>
                        @if($artikel->images->count() > 0)
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">
                            <i class="bi bi-exclamation-triangle-fill"></i> Klik tanda X untuk menghapus gambar
                        </p>
                        @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Tidak ada gambar yang tersedia</p>
                        @endif
                    </div>
                    
                    <!-- Add New Images -->
                    <div class="mt-4">
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tambah Gambar Baru
                            @if($artikel->images->count() == 0) 
                            <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md relative @error('gambar') border-red-500 @enderror" id="dropzone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
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
                        <div id="selected-images-count" class="text-xs text-gray-500 dark:text-gray-400 mt-1">0 gambar dipilih untuk ditambahkan</div>
                        <div id="total-images-message" class="text-xs {{ $artikel->images->count() >= 3 ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                            Total: {{ $artikel->images->count() }} gambar (max: 3)
                        </div>
                        @error('gambar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.artikel.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Perbarui Artikel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/37.0.0/super-build/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Enhanced CKEditor
        CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
            // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
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
            // Changing the language of the interface requires loading the language file using the <script> tag.
            language: 'id',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
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
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
            placeholder: 'Tulis konten artikel di sini...',
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
            fontSize: {
                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                supportAllValues: true
            },
            // Be careful with the setting below. It's been reported that browsers do not support it correctly yet.
            // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
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
            // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
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
            // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
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
            // The "super-build" contains more premium features that require additional configuration, disable them below.
            // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
            removePlugins: [
                // These two are commercial, but you can try them out without registering to a trial.
                // 'ExportPdf',
                // 'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                // This plugin is available in Webpack build and Visual Studio Code plugin only.
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
                // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                'MathType',
                // The following features are part of the Productivity Pack and require additional license.
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents'
            ]
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
        
        // Toggle image deletion
        document.querySelectorAll('.toggle-delete-image').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const checkbox = document.getElementById(this.getAttribute('for'));
                const container = this.closest('.image-container');
                const mark = container.querySelector('.image-mark');
                
                // Toggle the checkbox
                checkbox.checked = !checkbox.checked;
                
                // Mark the image for deletion or restore
                if (checkbox.checked) {
                    container.classList.add('opacity-50');
                    mark.textContent = 'Akan dihapus';
                    mark.classList.add('text-red-500');
                } else {
                    container.classList.remove('opacity-50');
                    mark.textContent = 'Gambar #' + (Array.from(container.parentNode.children).indexOf(container) + 1);
                    mark.classList.remove('text-red-500');
                }
                
                // Update total count
                updateTotalImagesCount();
            });
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
    });
    
    // Update total images count (current + new - deleted)
    function updateTotalImagesCount() {
        const currentImages = {{ $artikel->images->count() }};
        const newImages = document.getElementById('file-upload').files.length;
        const deletedImages = document.querySelectorAll('.delete-image-checkbox:checked').length;
        
        const totalImages = currentImages + newImages - deletedImages;
        const messageElem = document.getElementById('total-images-message');
        
        if (totalImages > 3) {
            messageElem.textContent = `Total: ${totalImages} gambar (max: 3) - PERINGATAN: Maksimal 3 gambar diperbolehkan`;
            messageElem.classList.add('text-red-500');
            messageElem.classList.remove('text-gray-500', 'dark:text-gray-400');
        } else if (totalImages === 0) {
            messageElem.textContent = `Total: ${totalImages} gambar (max: 3) - PERINGATAN: Minimal 1 gambar dibutuhkan`;
            messageElem.classList.add('text-red-500'); 
            messageElem.classList.remove('text-gray-500', 'dark:text-gray-400');
        } else {
            messageElem.textContent = `Total: ${totalImages} gambar (max: 3)`;
            messageElem.classList.remove('text-red-500');
            messageElem.classList.add('text-gray-500', 'dark:text-gray-400');
        }
    }
    
    // Image preview functionality
    function previewImages(event) {
        const container = document.getElementById('image-preview-container');
        const countDisplay = document.getElementById('selected-images-count');
        const files = event.target.files;
        
        // Clear previous previews if replacing
        container.innerHTML = '';
        
        // Update count display
        countDisplay.textContent = `${files.length} gambar dipilih untuk ditambahkan`;
        
        // Check if adding these images would exceed the maximum
        const currentImages = {{ $artikel->images->count() }};
        const deletedImages = document.querySelectorAll('.delete-image-checkbox:checked').length;
        const totalAfterAddition = currentImages + files.length - deletedImages;
        
        if (totalAfterAddition > 3) {
            alert(`Maksimal 3 gambar diperbolehkan. Anda saat ini memiliki ${currentImages - deletedImages} gambar setelah penghapusan, dan mencoba menambahkan ${files.length} gambar baru.`);
        }
        
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
                    countDisplay.textContent = `${input.files.length} gambar dipilih untuk ditambahkan`;
                    updateTotalImagesCount();
                });
                
                imgContainer.appendChild(removeButton);
                container.appendChild(imgContainer);
                
                // Update total count when adding/removing images
                updateTotalImagesCount();
            };
            
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush 