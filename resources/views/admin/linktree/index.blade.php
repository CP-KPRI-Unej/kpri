@extends('admin.layouts.app')

@section('title', 'Pengelolaan Pohon Tautan')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
    .input-with-stroke {
        border-width: 2px !important;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Pengelolaan Pohon Tautan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola tautan dan profil pohon tautan</p>
    </div>

    <div id="flash-message" class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded relative mb-4 hidden">
        <span id="flash-message-text"></span>
        <button type="button" onclick="document.getElementById('flash-message').classList.add('hidden')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <span class="sr-only">Tutup</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="flex justify-end mb-4">
        <button id="add-new-link-btn" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tautan Baru
        </button>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Profile Settings -->
        <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Pengaturan Profil</h2>
            
            <form id="profile-form" class="profile-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <!-- Logo Upload -->
                <div class="mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <div id="logo-container" class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                
                        </div>
                    </div>
                    
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="document.getElementById('logo').click()" class="bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white py-2 px-4 rounded">
                            Pilih Gambar
                        </button>
                        
                        <button type="button" id="remove-logo-btn" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 rounded hidden">
                            Hapus Gambar
                        </button>
                    </div>
                    
                    <input type="file" id="logo" name="logo" class="hidden" accept="image/*">
                    <input type="checkbox" id="remove_logo" name="remove_logo" value="1" class="hidden">
                </div>
                
                <!-- Profile Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul Profil</label>
                    <input type="text" id="title" name="title" required
                        class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="title-error"></p>
                </div>
                
                <!-- Bio -->
                <div class="mb-4">
                    <label for="bio" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Bio <span class="text-xs text-gray-500 dark:text-gray-400">(Maksimal 80 karakter)</span></label>
                    <textarea id="bio" name="bio" rows="3" maxlength="80"
                        class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600"></textarea>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                        <span id="bio-counter">0</span> / 80
                    </div>
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="bio-error"></p>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white py-2 px-4 rounded-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Links Management -->
        <div class="w-full md:w-2/3 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Pengelolaan Tautan</h2>
            
            <!-- Link List -->
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Tautan Anda</h3>
                
                    <div id="link-list" class="space-y-4">
                    <!-- Links will be dynamically loaded here -->
                    <div id="loading-links" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500"></div>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Memuat tautan...</p>
                    </div>
                </div>
                
                <div id="no-links" class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hidden">
                        <p class="text-gray-500 dark:text-gray-300">Belum ada tautan yang ditambahkan.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-400 mt-1">Tambahkan tautan pertama Anda menggunakan tombol di atas.</p>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Edit Tautan</h3>
        
        <form id="edit-form" class="space-y-4">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="edit-link-id" value="">
            
            <div>
                <label for="edit-title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                <input type="text" id="edit-title" name="title" required
                    class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="edit-title-error"></p>
            </div>
            
            <div>
                <label for="edit-url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tautan</label>
                <input type="url" id="edit-url" name="url" required
                    class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="edit-url-error"></p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Link Modal -->
<div id="add-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Tambah Tautan Baru</h3>
        
        <form id="add-link-form" class="space-y-4">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div>
                <label for="link-title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                <input type="text" id="link-title" name="title" required
                    class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="link-title-error"></p>
            </div>
            
            <div>
                <label for="link-url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tautan</label>
                <input type="url" id="link-url" name="url" required
                    class="input-with-stroke block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                <p class="text-red-500 dark:text-red-400 text-xs mt-1 hidden" id="link-url-error"></p>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-md">
                    Tambah Tautan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }

        // Initialize data
        fetchLinktreeProfile();
        fetchLinks();
        
        // Character counter for bio
        const bioTextarea = document.getElementById('bio');
        const bioCounter = document.getElementById('bio-counter');
        
        function updateBioCount() {
            const count = bioTextarea.value.length;
            bioCounter.textContent = count;
        }
        
        bioTextarea.addEventListener('input', updateBioCount);
        
        // Logo preview handling
        document.getElementById('logo').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Update preview container
                    const logoContainer = document.getElementById('logo-container');
                    
                    // Remove existing content
                    logoContainer.innerHTML = '';
                    
                    // Create new image preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Logo Preview';
                    img.id = 'logo-preview';
                    img.className = 'w-full h-full object-cover';
                    
                    // Add to container
                    logoContainer.appendChild(img);
                    
                    // Make sure remove logo checkbox is unchecked
                    document.getElementById('remove_logo').checked = false;
                    
                    // Show remove button
                    document.getElementById('remove-logo-btn').classList.remove('hidden');
                    }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Logo removal
        document.getElementById('remove-logo-btn').addEventListener('click', handleRemoveLogo);
        
        function handleRemoveLogo() {
            // Check remove logo checkbox
            document.getElementById('remove_logo').checked = true;
            
            // Update preview
            const logoContainer = document.getElementById('logo-container');
            logoContainer.innerHTML = '';
            
            // Add default image
            const defaultContainer = document.createElement('div');
            defaultContainer.className = 'w-full h-full flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-full';
            
            const defaultImg = document.createElement('img');
 
           
            
            defaultContainer.appendChild(defaultImg);
            logoContainer.appendChild(defaultContainer);
            
            // Hide remove button
            document.getElementById('remove-logo-btn').classList.add('hidden');
        }
        
        // Fetch tautan profile data
        function fetchLinktreeProfile() {
            fetch('/api/admin/linktree', {
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
                        return null;
                    }
                    throw new Error('Gagal memuat profil pohon tautan');
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                
                // Set form values
                document.getElementById('title').value = data.title;
                document.getElementById('bio').value = data.bio || '';
                updateBioCount();
                
                // Set logo preview
                if (data.logo) {
                    const logoContainer = document.getElementById('logo-container');
                    logoContainer.innerHTML = '';
                    
                    const img = document.createElement('img');
                    img.src = `/storage/${data.logo}`;
                    img.alt = 'Logo';
                    img.className = 'w-full h-full object-cover';
                    img.id = 'logo-preview';
                    
                    logoContainer.appendChild(img);
                    
                    // Show remove button
                    document.getElementById('remove-logo-btn').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error fetching pohon tautan profile:', error);
                showFlashMessage('Gagal memuat data profil. Silakan coba lagi nanti.', 'error');
            });
        }
        
        // Fetch tautan
        function fetchLinks() {
            fetch('/api/admin/linktree/links', {
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
                        return null;
                    }
                    throw new Error('Failed to fetch tautan');
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                
                // Hide loading indicator
                document.getElementById('loading-links').classList.add('hidden');
                
                // Show appropriate view based on data
                if (data.length === 0) {
                    document.getElementById('no-links').classList.remove('hidden');
                } else {
                    document.getElementById('no-links').classList.add('hidden');
                    renderLinks(data);
                }
            })
            .catch(error => {
                console.error('Error fetching tautan:', error);
                document.getElementById('loading-links').classList.add('hidden');
                showFlashMessage('Gagal memuat tautan. Silakan coba lagi nanti.', 'error');
            });
        }
        
        // Render tautan
        function renderLinks(links) {
            const linkList = document.getElementById('link-list');
            linkList.innerHTML = '';
            
            links.forEach(link => {
                const linkItem = document.createElement('div');
                linkItem.className = 'link-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center';
                linkItem.dataset.id = link.id;
                
                linkItem.innerHTML = `
                    <div class="grip-handle mr-3 cursor-move">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    
                    <div class="flex-grow">
                        <h4 class="font-medium text-gray-900 dark:text-white">${link.title}</h4>
                        <a href="${link.url}" target="_blank" class="text-sm text-blue-500 dark:text-blue-400 hover:underline">${link.url}</a>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button type="button" class="edit-link-btn text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white" data-id="${link.id}" data-title="${link.title}" data-url="${link.url}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        
                        <button type="button" class="delete-link-btn text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" data-id="${link.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                
                linkList.appendChild(linkItem);
            });
            
            // Add event listeners to buttons
            document.querySelectorAll('.edit-link-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    openEditModal(
                        this.dataset.id,
                        this.dataset.title,
                        this.dataset.url
                    );
                });
            });
            
            document.querySelectorAll('.delete-link-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Apakah Anda yakin ingin menghapus tautan ini?')) {
                        deleteLink(this.dataset.id);
                    }
                });
            });
            
            // Initialize Sortable
            initSortable();
        }
        
        // Initialize sortable
        function initSortable() {
            const linkList = document.getElementById('link-list');
            if (linkList.childElementCount > 0) {
                new Sortable(linkList, {
                    animation: 150,
                    handle: '.grip-handle',
                    onEnd: function() {
                        updateLinkPositions();
                    }
                });
            }
        }
        
        // Update link positions
        function updateLinkPositions() {
            const items = document.querySelectorAll('.link-item');
            const positions = Array.from(items).map(item => parseInt(item.dataset.id));
            
            fetch('/api/admin/linktree/links/positions', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ positions: positions })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update positions');
                }
                return response.json();
            })
            .then(data => {
                console.log('Posisi berhasil diperbarui');
            })
            .catch(error => {
                console.error('Error updating positions:', error);
                showFlashMessage('Gagal memperbarui posisi tautan.', 'error');
            });
        }
        
        // Profile Form Submit
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset errors
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.classList.add('hidden');
            });
            
            const formData = new FormData(this);
            
            fetch('/api/admin/linktree/profile', {
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
                        if (response.status === 422 && data.errors) {
                            // Validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElem = document.getElementById(`${field}-error`);
                                if (errorElem) {
                                    errorElem.textContent = data.errors[field][0];
                                    errorElem.classList.remove('hidden');
                                }
                            });
                        }
                        throw new Error(data.message || 'Gagal memperbarui profil');
                    });
                }
                return response.json();
            })
            .then(data => {
                showFlashMessage('Profil berhasil diperbarui!', 'success');
                fetchLinktreeProfile(); // Refresh data
            })
            .catch(error => {
                console.error('Error updating profile:', error);
                showFlashMessage(error.message || 'Gagal memperbarui profil.', 'error');
            });
        });
        
        // Edit Link Form Submit
        document.getElementById('edit-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset errors
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.classList.add('hidden');
            });
            
            const formData = new FormData(this);
            const data = {
                title: formData.get('title'),
                url: formData.get('url')
            };
            const linkId = document.getElementById('edit-link-id').value;
            
            // Disable submit button and show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menyimpan...
            `;
            
            fetch(`/api/admin/linktree/links/${linkId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (response.status === 422 && data.errors) {
                            // Validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElem = document.getElementById(`edit-${field}-error`);
                                if (errorElem) {
                                    errorElem.textContent = data.errors[field][0];
                                    errorElem.classList.remove('hidden');
                                }
                            });
                        }
                        throw new Error('Gagal memperbarui tautan');
                    });
                }
                return response.json();
            })
            .then(data => {
                closeEditModal();
                showFlashMessage('Tautan berhasil diperbarui!', 'success');
                
                // Show loading while fetching updated tautan
                showLinkListLoading();
                fetchLinks();
            })
            .catch(error => {
                console.error('Error updating tautan:', error);
                showFlashMessage(error.message || 'Gagal memperbarui tautan.', 'error');
            })
            .finally(() => {
                // Re-enable button and restore text
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
        
        // Delete tautan with loading state
        function deleteLink(id) {
            // Show loading on the tautan list while deleting
            showLinkListLoading();
            
            fetch(`/api/admin/linktree/links/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete tautan');
                }
                return response.json();
            })
            .then(data => {
                showFlashMessage('Tautan berhasil dihapus!', 'success');
                fetchLinks();
            })
            .catch(error => {
                console.error('Error deleting tautan:', error);
                showFlashMessage('Gagal menghapus tautan.', 'error');
                fetchLinks(); // Still refresh the list to ensure UI is in sync
            });
        }
        
        // Edit modal functions
        window.openEditModal = function(id, title, url) {
            document.getElementById('edit-link-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-url').value = url;
            document.getElementById('edit-modal').classList.remove('hidden');
        };
        
        window.closeEditModal = function() {
            document.getElementById('edit-modal').classList.add('hidden');
        };
        
        // Add New tautan button
        document.getElementById('add-new-link-btn').addEventListener('click', function() {
            // Show add modal
            document.getElementById('add-modal').classList.remove('hidden');
        });
        
        // Add tautan Form Submit
        document.getElementById('add-link-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset errors
            document.querySelectorAll('.text-red-500').forEach(el => {
                el.classList.add('hidden');
            });
            
            const formData = new FormData(this);
            const data = {
                title: formData.get('title'),
                url: formData.get('url')
            };
            
            // Disable submit button and show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menambahkan...
            `;
            
            fetch('/api/admin/linktree/links', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        if (response.status === 422 && data.errors) {
                            // Validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElem = document.getElementById(`link-${field}-error`);
                                if (errorElem) {
                                    errorElem.textContent = data.errors[field][0];
                                    errorElem.classList.remove('hidden');
                                }
                            });
                        }
                        throw new Error('Gagal menambahkan tautan');
                    });
                }
                return response.json();
            })
            .then(data => {
                this.reset();
                closeAddModal();
                showFlashMessage('Tautan berhasil ditambahkan!', 'success');
                
                // Show loading while fetching updated tautan
                showLinkListLoading();
                fetchLinks();
            })
            .catch(error => {
                console.error('Error adding tautan:', error);
                showFlashMessage(error.message || 'Gagal menambahkan tautan.', 'error');
            })
            .finally(() => {
                // Re-enable button and restore text
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
        
        // Close Add Modal
        window.closeAddModal = function() {
            document.getElementById('add-modal').classList.add('hidden');
            document.getElementById('add-link-form').reset();
            document.querySelectorAll('#add-link-form .text-red-500').forEach(el => {
                el.classList.add('hidden');
            });
        };
        
        // Flash message
        function showFlashMessage(message, type = 'success') {
            const flashMessage = document.getElementById('flash-message');
            const flashMessageText = document.getElementById('flash-message-text');
            
            flashMessageText.textContent = message;
            
            if (type === 'error') {
                flashMessage.classList.remove('bg-green-100', 'dark:bg-green-800', 'border-green-400', 'dark:border-green-700', 'text-green-700', 'dark:text-green-100');
                flashMessage.classList.add('bg-red-100', 'dark:bg-red-800', 'border-red-400', 'dark:border-red-700', 'text-red-700', 'dark:text-red-100');
            } else {
                flashMessage.classList.remove('bg-red-100', 'dark:bg-red-800', 'border-red-400', 'dark:border-red-700', 'text-red-700', 'dark:text-red-100');
                flashMessage.classList.add('bg-green-100', 'dark:bg-green-800', 'border-green-400', 'dark:border-green-700', 'text-green-700', 'dark:text-green-100');
            }
            
            flashMessage.classList.remove('hidden');
            
            // Hide after 5 seconds
            setTimeout(() => {
                flashMessage.classList.add('hidden');
            }, 5000);
        }
        
        // Show loading state in tautan list
        function showLinkListLoading() {
            const linkList = document.getElementById('link-list');
            document.getElementById('no-links').classList.add('hidden');
            
            linkList.innerHTML = `
                <div id="loading-links" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500"></div>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Memuat tautan...</p>
                </div>
            `;
        }
    });
</script>
@endpush
@endsection 