@extends('admin.layouts.app')

@section('title', 'Edit Artikel')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Artikel</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Edit artikel yang sudah ada</p>
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
            <input type="hidden" id="articleId" value="{{ $id }}">
            
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
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="id_status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="">Pilih Status</option>
                            <!-- Statuses will be loaded dynamically -->
                        </select>
                        <p class="text-red-500 text-xs mt-1 error-message" id="status_error"></p>
                    </div>
                    
                    <!-- Tanggal Rilis -->
                    <div>
                        <label for="tgl_rilis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Rilis <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_rilis" id="tgl_rilis" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" required>
                        <p class="text-red-500 text-xs mt-1 error-message" id="tgl_rilis_error"></p>
                    </div>
                    
                    <!-- Current Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Saat Ini <span id="current-images-count">(0/3)</span></label>
                        <div class="flex flex-wrap gap-3 mt-2" id="current-images-container">
                            <!-- Current images will be loaded dynamically -->
                        </div>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-2" id="delete-images-hint" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill"></i> Klik tanda X untuk menghapus gambar
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" id="no-images-message" style="display: none;">Tidak ada gambar yang tersedia</p>
                    </div>
                    
                    <!-- Add New Images -->
                    <div class="mt-4">
                        <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tambah Gambar Baru
                            <span class="text-red-500" id="images-required-marker" style="display: none;">*</span>
                        </label>
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
                        <div id="selected-images-count" class="text-xs text-gray-500 dark:text-gray-400 mt-1">0 gambar dipilih untuk ditambahkan</div>
                        <div id="total-images-message" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Total: 0 gambar (max: 3)
                        </div>
                        <p class="text-red-500 text-xs mt-1 error-message" id="gambar_error"></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="/admin/artikel" class="px-4 py-2 border border-orange-500 text-orange-500 rounded-md hover:bg-orange-500 hover:text-white transition-colors">Batal</a>
                <button type="button" id="submitBtn" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">Perbarui Artikel</button>
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
    let artikel; // Global variable to store the article data
    let deleteImageIds = []; // IDs of images to delete
    let tagifyInstance; // Global variable to store Tagify instance
    
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }
        
        const articleId = document.getElementById('articleId').value;
        
        // Fetch article data and statuses simultaneously
        Promise.all([
            fetchArticle(articleId),
            fetchStatuses()
        ]).then(([articleData, statusesData]) => {
            // Initialize editor after data is fetched
            initializeEditor();
        }).catch(error => {
            console.error('Error initializing page:', error);
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
    
    function fetchArticle(id) {
        const token = localStorage.getItem('access_token');
        
        return fetch(`/api/admin/articles/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    localStorage.removeItem('access_token');
                    window.location.href = '/admin/login';
                }
                throw new Error('Failed to fetch article');
            }
            return response.json();
        })
        .then(data => {
            artikel = data;
            populateForm(data);
            return data;
        });
    }
    
    function fetchStatuses() {
        const token = localStorage.getItem('access_token');
        
        return fetch('/api/admin/article-statuses', {
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
            
            // Set selected status if article data is available
            if (artikel && artikel.status) {
                statusSelect.value = artikel.status;
            }
            
            return statuses;
        });
    }
    
    function populateForm(article) {
        // Set form values
        document.getElementById('nama_artikel').value = article.nama_artikel;
        document.getElementById('id_status').value = article.status;
        document.getElementById('tgl_rilis').value = formatDateForInput(article.tgl_rilis);
        
        // Initialize Tagify after setting value
        tagifyInstance = new Tagify(document.getElementById('tags_artikel'), {
            delimiters: ',',
            pattern: /^.{0,20}$/,
            maxTags: 10
        });
        
        // Set tags value after Tagify is initialized
        if (article.tags_artikel) {
            try {
                // Check if the tags are already in JSON format
                let parsedTags = article.tags_artikel;
                
                // If it looks like a JSON string, try to parse it
                if (typeof parsedTags === 'string' && 
                    (parsedTags.startsWith('[') || parsedTags.startsWith('{'))) {
                    try {
                        const jsonTags = JSON.parse(parsedTags);
                        // Handle array of objects with 'value' property (Tagify format)
                        if (Array.isArray(jsonTags) && jsonTags.length > 0 && jsonTags[0].value) {
                            parsedTags = jsonTags.map(tag => tag.value).join(',');
                        }
                    } catch (e) {
                        console.error('Error parsing tags JSON:', e);
                    }
                }
                
                // Add the tags to Tagify
                tagifyInstance.addTags(parsedTags);
            } catch (e) {
                console.error('Error setting tags:', e);
            }
        }
        
        // Display current images
        displayCurrentImages(article.images);
        
        // Update required marker for images
        updateImagesRequiredMarker(article.images.length);
    }
    
    function formatDateForInput(dateString) {
        const date = new Date(dateString);
        return date.toISOString().substring(0, 10); // Format: YYYY-MM-DD
    }
    
    function initializeEditor() {
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
            
            // Set editor content
            if (artikel && artikel.deskripsi_artikel) {
                editor.setData(artikel.deskripsi_artikel);
            }
        })
        .catch(error => {
            console.error(error);
        });
    }
    
    function displayCurrentImages(images) {
        const container = document.getElementById('current-images-container');
        const countDisplay = document.getElementById('current-images-count');
        const noImagesMessage = document.getElementById('no-images-message');
        const deleteImagesHint = document.getElementById('delete-images-hint');
        
        container.innerHTML = '';
        
        // Update count display
        countDisplay.textContent = `(${images.length}/3)`;
        
        if (images.length === 0) {
            noImagesMessage.style.display = 'block';
            deleteImagesHint.style.display = 'none';
        } else {
            noImagesMessage.style.display = 'none';
            deleteImagesHint.style.display = 'block';
            
            images.forEach((image, index) => {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'image-container';
                imgContainer.setAttribute('data-image-id', image.id);
                
                const img = document.createElement('img');
                img.src = `/storage/${image.gambar}`;
                img.className = 'image-preview rounded-md shadow-sm';
                imgContainer.appendChild(img);
                
                const removeButton = document.createElement('div');
                removeButton.className = 'absolute top-2 right-2';
                removeButton.innerHTML = `
                    <div class="w-6 h-6 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full cursor-pointer shadow-sm toggle-delete-image">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                `;
                
                removeButton.addEventListener('click', function() {
                    toggleImageForDeletion(imgContainer, image.id);
                });
                
                imgContainer.appendChild(removeButton);
                
                const imageMark = document.createElement('div');
                imageMark.className = 'text-xs text-center mt-1 image-mark';
                imageMark.textContent = `Gambar #${index + 1}`;
                imgContainer.appendChild(imageMark);
                
                container.appendChild(imgContainer);
            });
        }
        
        updateTotalImagesCount();
    }
    
    function toggleImageForDeletion(container, imageId) {
        const imageMark = container.querySelector('.image-mark');
        
        if (container.classList.contains('opacity-50')) {
            // Restore the image (remove from deletion list)
                    container.classList.remove('opacity-50');
            imageMark.textContent = `Gambar #${Array.from(container.parentNode.children).indexOf(container) + 1}`;
            imageMark.classList.remove('text-red-500');
            
            // Remove from delete list
            const index = deleteImageIds.indexOf(imageId);
            if (index !== -1) {
                deleteImageIds.splice(index, 1);
            }
        } else {
            // Mark the image for deletion
            container.classList.add('opacity-50');
            imageMark.textContent = 'Akan dihapus';
            imageMark.classList.add('text-red-500');
            
            // Add to delete list
            if (!deleteImageIds.includes(imageId)) {
                deleteImageIds.push(imageId);
            }
        }
        
        updateTotalImagesCount();
        updateImagesRequiredMarker(artikel.images.length - deleteImageIds.length);
            }
    
    // Update total images count (current + new - deleted)
    function updateTotalImagesCount() {
        const currentImages = artikel ? artikel.images.length : 0;
        const newImages = document.getElementById('file-upload').files.length;
        const totalImages = currentImages + newImages - deleteImageIds.length;
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
    
    function updateImagesRequiredMarker(currentImagesCount) {
        const requiredMarker = document.getElementById('images-required-marker');
        requiredMarker.style.display = currentImagesCount === 0 ? 'inline' : 'none';
    }
    
    // Image preview functionality
    function previewImages(event) {
        const container = document.getElementById('image-preview-container');
        const countDisplay = document.getElementById('selected-images-count');
        const files = event.target.files;
        
        // Clear previous previews
        container.innerHTML = '';
        
        // Update count display
        countDisplay.textContent = `${files.length} gambar dipilih untuk ditambahkan`;
        
        // Check if adding these images would exceed the maximum
        const currentImagesCount = artikel ? artikel.images.length - deleteImageIds.length : 0;
        const totalAfterAddition = currentImagesCount + files.length;
        
        if (totalAfterAddition > 3) {
            alert(`Maksimal 3 gambar diperbolehkan. Anda saat ini memiliki ${currentImagesCount} gambar setelah penghapusan, dan mencoba menambahkan ${files.length} gambar baru.`);
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
    
    function submitForm() {
        console.log('Submit button clicked');
        
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(element => {
            element.textContent = '';
        });
        
        const token = localStorage.getItem('access_token');
        if (!token) {
            console.error('No access token found');
            alert('You are not logged in. Please log in and try again.');
            window.location.href = '/admin/login';
            return;
        }
        
        const form = document.getElementById('artikelForm');
        const formData = new FormData(form);
        const articleId = document.getElementById('articleId').value;
        
        console.log('Article ID:', articleId);
        
        // Add editor content
        formData.set('deskripsi_artikel', editorInstance.getData());
        
        // Handle tags properly - get raw values from Tagify
        if (tagifyInstance) {
            const tagValues = tagifyInstance.value.map(tag => tag.value).join(',');
            formData.set('tags_artikel', tagValues);
            console.log('Tags set:', tagValues);
        }
        
        // Add method spoofing for PUT request
        formData.append('_method', 'PUT');
        
        // Add delete_images array if any
        if (deleteImageIds.length > 0) {
            console.log('Images to delete:', deleteImageIds);
            deleteImageIds.forEach(id => {
                formData.append('delete_images[]', id);
            });
        }
        
        // Debug: Log form data
        console.log('Form data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (!csrfToken) {
            console.error('No CSRF token found');
        } else {
            console.log('CSRF token found');
        }
        
        // Submit form
        console.log(`Submitting to: /api/admin/articles/${articleId}`);
        fetch(`/api/admin/articles/${articleId}`, {
            method: 'POST', // Using POST with _method=PUT because of file uploads
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text().then(text => {
                if (text) {
                    try {
                        return { status: response.status, data: JSON.parse(text) };
                    } catch (e) {
                        console.error('Error parsing JSON response:', text);
                        return { status: response.status, data: { message: 'Invalid JSON response' } };
                    }
                }
                return { status: response.status, data: {} };
            });
        })
        .then(({ status, data }) => {
            console.log('Response data:', data);
            
            if (status >= 200 && status < 300) {
                // Success
                alert('Artikel berhasil diperbarui!');
                window.location.href = '/admin/artikel';
                return;
            }
            
            // Handle errors
            if (status === 422) {
                // Validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}_error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                }
                alert('Ada kesalahan pada form. Mohon periksa kembali.');
            } else if (status === 401) {
                localStorage.removeItem('access_token');
                alert('Sesi anda telah berakhir. Silahkan login kembali.');
                window.location.href = '/admin/login';
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat mengirim data. Silahkan coba lagi.');
        });
    }
</script>
@endpush 