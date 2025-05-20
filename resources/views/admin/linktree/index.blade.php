@extends('admin.layouts.app')

@section('title', 'Linktree Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Linktree Management</h1>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Profile Settings -->
        <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Profile Settings</h2>
            
            <form action="{{ route('admin.linktree.update-profile') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                @csrf
                
                <!-- Logo Upload -->
                <div class="mb-6">
                    <div class="flex items-center justify-center mb-4">
                        <div id="logo-container" class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            @if($linktree->logo)
                                <img src="{{ asset('storage/' . $linktree->logo) }}" alt="Logo" class="w-full h-full object-cover" id="logo-preview">
                            @else
                                <div class="w-full h-full flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-full">
                                    <img src="{{ asset('images/logo.png') }}" alt="Default Logo" class="w-20 h-20 object-contain" id="logo-preview-default">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="document.getElementById('logo').click()" class="bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white py-2 px-4 rounded">
                            Pilih Gambar
                        </button>
                        
                        @if($linktree->logo)
                        <button type="button" id="remove-logo-btn" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 rounded">
                            Hapus Gambar
                        </button>
                        @endif
                    </div>
                    
                    <input type="file" id="logo" name="logo" class="hidden" accept="image/*">
                    <input type="checkbox" id="remove_logo" name="remove_logo" value="1" class="hidden">
                </div>
                
                <!-- Profile Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Profile Title</label>
                    <input type="text" id="title" name="title" value="{{ $linktree->title }}" required
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                    @error('title')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Bio -->
                <div class="mb-4">
                    <label for="bio" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Bio <span class="text-xs text-gray-500 dark:text-gray-400">(Max 80 characters)</span></label>
                    <textarea id="bio" name="bio" rows="3" maxlength="80"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">{{ $linktree->bio }}</textarea>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                        <span id="bio-counter">0</span> / 80
                    </div>
                    @error('bio')
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white py-2 px-4 rounded-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Links Management -->
        <div class="w-full md:w-2/3 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Links Management</h2>
            
            <!-- Add New Link Form -->
            <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-750">
                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Add New Link</h3>
                
                <form action="{{ route('admin.linktree.store-link') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                            @error('title')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Link</label>
                            <input type="url" id="url" name="url" value="{{ old('url') }}" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
                            @error('url')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-md">
                            Tambahkan Link
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Link List -->
            <div>
                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">Your Links</h3>
                
                @if(count($links) > 0)
                    <div id="link-list" class="space-y-4">
                        @foreach($links as $link)
                            <div class="link-item bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center" data-id="{{ $link->id }}">
                                <div class="grip-handle mr-3 cursor-move">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                    </svg>
                                </div>
                                
                                <div class="flex-grow">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $link->title }}</h4>
                                    <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-500 dark:text-blue-400 hover:underline">{{ $link->url }}</a>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="openEditModal({{ $link->id }}, '{{ $link->title }}', '{{ $link->url }}')" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    
                                    <form action="{{ route('admin.linktree.delete-link', $link->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this link?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <p class="text-gray-500 dark:text-gray-300">No links added yet.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-400 mt-1">Add your first link using the form above.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Edit Link</h3>
        
        <form id="edit-form" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label for="edit-title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Judul</label>
                <input type="text" id="edit-title" name="title" required
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
            </div>
            
            <div>
                <label for="edit-url" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Link</label>
                <input type="url" id="edit-url" name="url" required
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm py-2 px-3 focus:ring-orange-500 focus:border-orange-500 dark:focus:ring-orange-600 dark:focus:border-orange-600">
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white rounded-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // Character counter for bio
        const bioTextarea = $('#bio');
        const bioCounter = $('#bio-counter');
        
        function updateBioCount() {
            const count = bioTextarea.val().length;
            bioCounter.text(count);
        }
        
        bioTextarea.on('input', updateBioCount);
        updateBioCount();
        
        // Logo preview handling
        $('#logo').on('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Update preview container
                    const logoContainer = $('#logo-container');
                    
                    // Remove existing content
                    logoContainer.empty();
                    
                    // Create new image preview
                    const img = $('<img>', {
                        src: e.target.result,
                        alt: 'Logo Preview',
                        id: 'logo-preview',
                        class: 'w-full h-full object-cover'
                    });
                    
                    // Add to container
                    logoContainer.append(img);
                    
                    // Make sure remove logo checkbox is unchecked
                    $('#remove_logo').prop('checked', false);
                    
                    // Show remove button if not visible
                    if ($('#remove-logo-btn').length === 0) {
                        const removeBtn = $('<button>', {
                            type: 'button',
                            id: 'remove-logo-btn',
                            class: 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 rounded',
                            text: 'Hapus Gambar'
                        });
                        
                        removeBtn.on('click', handleRemoveLogo);
                        $('.flex.justify-center.gap-2').append(removeBtn);
                    }
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Logo removal
        $('#remove-logo-btn').on('click', handleRemoveLogo);
        
        function handleRemoveLogo() {
            // Check remove logo checkbox
            $('#remove_logo').prop('checked', true);
            
            // Update preview
            const logoContainer = $('#logo-container');
            logoContainer.empty();
            
            // Add default image
            const defaultContainer = $('<div>', {
                class: 'w-full h-full flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-full'
            });
            
            const defaultImg = $('<img>', {
                src: '{{ asset("images/logo.png") }}',
                alt: 'Default Logo',
                class: 'w-20 h-20 object-contain',
                id: 'logo-preview-default'
            });
            
            defaultContainer.append(defaultImg);
            logoContainer.append(defaultContainer);
            
            // Hide remove button
            $('#remove-logo-btn').remove();
        }
        
        // Edit modal functions
        window.openEditModal = function(id, title, url) {
            $('#edit-form').attr('action', '{{ route("admin.linktree.update-link", "") }}/' + id);
            $('#edit-title').val(title);
            $('#edit-url').val(url);
            $('#edit-modal').removeClass('hidden');
        };
        
        window.closeEditModal = function() {
            $('#edit-modal').addClass('hidden');
        };
        
        // Sortable links
        const linkList = document.getElementById('link-list');
        if (linkList) {
            new Sortable(linkList, {
                animation: 150,
                handle: '.grip-handle',
                onEnd: function() {
                    // Get the new order
                    const items = linkList.querySelectorAll('.link-item');
                    const positions = Array.from(items).map(item => item.dataset.id);
                    
                    // Update positions via AJAX
                    $.ajax({
                        url: '{{ route("admin.linktree.update-positions") }}',
                        method: 'POST',
                        data: JSON.stringify({ positions: positions }),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success) {
                                console.log('Positions updated successfully');
                            }
                        },
                        error: function(error) {
                            console.error('Error updating positions:', error);
                        }
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection 