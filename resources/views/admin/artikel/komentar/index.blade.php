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
            <h1 class="text-2xl font-semibold">Komentar Artikel: <span id="artikelTitle">Loading...</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola komentar untuk artikel ini</p>
        </div>
        <a href="/admin/artikel" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
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
                <a href="#" class="tab-link inline-block p-4 rounded-t-lg border-b-2 text-orange-600 border-orange-600 active dark:text-orange-500 dark:border-orange-500" data-status="all">
                    Semua 
                    <span id="allCount" class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-gray-500 rounded-full">
                        0
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="#" class="tab-link inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" data-status="pending">
                    Pending
                    <span id="pendingCount" class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-yellow-500 rounded-full">
                        0
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="#" class="tab-link inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" data-status="approved">
                    Disetujui
                    <span id="approvedCount" class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-green-500 rounded-full">
                        0
                    </span>
                </a>
            </li>
            <li class="mr-2">
                <a href="#" class="tab-link inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" data-status="rejected">
                    Ditolak
                    <span id="rejectedCount" class="inline-flex items-center justify-center px-2 py-1 ml-2 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                        0
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-visible table-container">
        <div id="commentTableContainer">
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="p-6 text-center text-gray-500 dark:text-gray-400">
                <svg class="animate-spin h-10 w-10 mx-auto mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p>Loading comments...</p>
            </div>
            
            <!-- Comments table will be inserted here -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }
        
        // Get article ID from URL
        const pathParts = window.location.pathname.split('/');
        const articleId = pathParts[pathParts.length - 2]; // Assuming URL pattern is /admin/artikel/{id}/komentar
        
        // Current filter status
        let currentStatus = 'all';
        
        // Fetch article and comments data
        fetchArticleData(articleId);
        
        // Add event listeners to status tabs
        document.querySelectorAll('.tab-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                document.querySelectorAll('.tab-link').forEach(t => {
                    t.classList.remove('text-orange-600', 'border-orange-600', 'active', 'dark:text-orange-500', 'dark:border-orange-500');
                    t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
                });
                
                // Add active class to clicked tab
                this.classList.add('text-orange-600', 'border-orange-600', 'active', 'dark:text-orange-500', 'dark:border-orange-500');
                this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
                
                // Update current status
                currentStatus = this.getAttribute('data-status');
                
                // Filter comments
                filterCommentsByStatus(currentStatus);
            });
        });
        
        function fetchArticleData(articleId) {
            // First, fetch the article to get its title
            fetch('/api/admin/articles', {
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
                    throw new Error('Failed to fetch articles');
                }
                return response.json();
            })
            .then(data => {
                // Access the articles data from the response object
                const articles = data.data || [];
                const article = articles.find(a => a.id_artikel == articleId);
                if (article) {
                    document.getElementById('artikelTitle').textContent = article.nama_artikel;
                }
                
                // Now fetch the comments
                fetchComments(articleId);
            })
            .catch(error => {
                console.error('Error fetching article data:', error);
                document.getElementById('loadingIndicator').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-red-500">Failed to load article data. Please try again.</p>
                `;
            });
        }
        
        function fetchComments(articleId) {
            fetch(`/api/admin/articles/${articleId}/comments`, {
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
                    throw new Error('Failed to fetch comments');
                }
                return response.json();
            })
            .then(comments => {
                renderComments(comments);
            })
            .catch(error => {
                console.error('Error fetching comments:', error);
                document.getElementById('loadingIndicator').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-red-500">Failed to load comments. Please try again.</p>
                `;
            });
        }
        
        function renderComments(comments) {
            const container = document.getElementById('commentTableContainer');
            
            // Update counts
            document.getElementById('allCount').textContent = comments.length;
            document.getElementById('pendingCount').textContent = comments.filter(c => c.status === 'pending').length;
            document.getElementById('approvedCount').textContent = comments.filter(c => c.status === 'approved').length;
            document.getElementById('rejectedCount').textContent = comments.filter(c => c.status === 'rejected').length;
            
            if (comments.length === 0) {
                container.innerHTML = `
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Tidak ada komentar untuk ditampilkan</p>
                    </div>
                `;
                return;
            }
            
            let html = `
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="text-gray-500 dark:text-gray-400 text-sm">
                        Menampilkan <span id="displayedCount">${comments.length}</span> komentar
            </div>
            <div class="flex space-x-2">
                        <div id="approveAllContainer" class="inline-block ${comments.filter(c => c.status === 'pending').length === 0 ? 'hidden' : ''}">
                            <button type="button" id="approveAllBtn" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm">
                        <i class="bi bi-check-all mr-1"></i> Setujui Semua Pending
                    </button>
                        </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-md text-sm flex items-center">
                        <i class="bi bi-gear mr-1"></i> Tindakan Terpilih 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 action-dropdown">
                        <div class="py-1">
                            <button type="button" onclick="bulkUpdateStatus('approved')" class="w-full text-left px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-check-circle mr-2"></i> Setujui Terpilih
                            </button>
                            <button type="button" onclick="bulkUpdateStatus('rejected')" class="w-full text-left px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-x-circle mr-2"></i> Tolak Terpilih
                            </button>
                            <button type="button" onclick="bulkUpdateStatus('pending')" class="w-full text-left px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="bi bi-clock mr-2"></i> Pending Terpilih
                            </button>
                            <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                            <button type="button" onclick="confirmMultipleDelete()" class="w-full text-left px-4 py-2 text-sm text-red-500 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
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
                        <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
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
                    <tbody id="commentTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        ${comments.map(comment => createCommentRow(comment)).join('')}
                    </tbody>
                </table>
            `;
            
            container.innerHTML = html;
            
            // Add event listeners
            document.getElementById('selectAll').addEventListener('change', function() {
                document.querySelectorAll('#commentTableBody input[type="checkbox"]').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
            
            document.getElementById('approveAllBtn')?.addEventListener('click', function() {
                approveAllPendingComments(comments);
            });
            
            // Initialize dropdowns with Alpine.js
            // This is needed because we're dynamically adding elements
            if (typeof Alpine !== 'undefined') {
                Alpine.initTree(document.getElementById('commentTableContainer'));
            }
            
            // Add event listeners to action buttons
            attachActionEventListeners();
            
            // Apply current filter
            filterCommentsByStatus(currentStatus);
        }
        
        function createCommentRow(comment) {
            // Determine status classes and text
            let statusClass = '';
            let statusBadge = '';
            
            if (comment.status === 'approved') {
                statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                statusBadge = '<i class="bi bi-check-circle"></i> Disetujui';
            } else if (comment.status === 'rejected') {
                statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                statusBadge = '<i class="bi bi-x-circle"></i> Ditolak';
            } else {
                statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                statusBadge = '<i class="bi bi-clock"></i> Menunggu';
            }
            
            // Format date
            const date = new Date(comment.created_at);
            const formattedDate = `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            
            return `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dropdown-container" data-status="${comment.status}">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <input type="checkbox" value="${comment.id_komentar}" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${comment.nama_pengomentar}</div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            ${comment.isi_komentar}
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusBadge}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        ${formattedDate}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-orange-500 hover:text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50 action-dropdown">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <!-- Direct status actions -->
                                    <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">Ubah Status</div>

                                    <!-- Approve Comment -->
                                    ${comment.status !== 'approved' ? `
                                    <button type="button" class="update-status-btn w-full text-left block px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                            role="menuitem" 
                                            data-comment-id="${comment.id_komentar}" 
                                            data-status="approved">
                                            <i class="bi bi-check-circle mr-2"></i> Setujui
                                        </button>
                                    ` : ''}

                                    <!-- Reject Comment -->
                                    ${comment.status !== 'rejected' ? `
                                    <button type="button" class="update-status-btn w-full text-left block px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                            role="menuitem" 
                                            data-comment-id="${comment.id_komentar}" 
                                            data-status="rejected">
                                            <i class="bi bi-x-circle mr-2"></i> Tolak
                                        </button>
                                    ` : ''}

                                    <!-- Return to Pending -->
                                    ${comment.status !== 'pending' ? `
                                    <button type="button" class="update-status-btn w-full text-left block px-4 py-2 text-sm text-orange-500 dark:text-orange-300 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                            role="menuitem" 
                                            data-comment-id="${comment.id_komentar}" 
                                            data-status="pending">
                                            <i class="bi bi-arrow-counterclockwise mr-2"></i> Set Pending
                                        </button>
                                    ` : ''}

                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>

                                    <!-- Delete Comment -->
                                    <button type="button" class="delete-comment-btn w-full text-left block px-4 py-2 text-sm text-red-500 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                            role="menuitem" 
                                            data-comment-id="${comment.id_komentar}">
                                            <i class="bi bi-trash mr-2"></i> Hapus
                                        </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        }
        
        function attachActionEventListeners() {
            // Status update buttons
            document.querySelectorAll('.update-status-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const status = this.getAttribute('data-status');
                    updateCommentStatus(commentId, status);
                });
            });
            
            // Delete buttons
            document.querySelectorAll('.delete-comment-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
                        deleteComment(commentId);
                    }
                });
            });
        }
        
        function filterCommentsByStatus(status) {
            const rows = document.querySelectorAll('#commentTableBody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                if (status === 'all' || row.getAttribute('data-status') === status) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
        }
    });

            // Update displayed count
            const displayedCount = document.getElementById('displayedCount');
            if (displayedCount) {
                displayedCount.textContent = visibleCount;
            }
            
            // Show/hide approve all button based on pending comments
            const approveAllContainer = document.getElementById('approveAllContainer');
            if (approveAllContainer) {
                if (status === 'pending' && visibleCount > 0) {
                    approveAllContainer.classList.remove('hidden');
                } else {
                    approveAllContainer.classList.add('hidden');
        }
            }
        }
        
        function updateCommentStatus(commentId, status) {
            fetch(`/api/admin/comments/${commentId}/status`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update comment status');
                }
                return response.json();
            })
            .then(data => {
                // Refresh comments
                fetchComments(articleId);
            })
            .catch(error => {
                console.error('Error updating comment status:', error);
                alert('Failed to update comment status. Please try again.');
            });
        }
        
        function deleteComment(commentId) {
            fetch(`/api/admin/comments/${commentId}`, {
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
                    throw new Error('Failed to delete comment');
                }
                return response.json();
            })
            .then(data => {
                // Refresh comments
                fetchComments(articleId);
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                alert('Failed to delete comment. Please try again.');
            });
        }
        
        function approveAllPendingComments(allComments) {
            const pendingComments = allComments.filter(comment => comment.status === 'pending');
            
            if (pendingComments.length === 0) {
                alert('No pending comments to approve.');
                return;
            }
            
            if (confirm(`Are you sure you want to approve all ${pendingComments.length} pending comments?`)) {
                // Create promises for all update requests
                const updatePromises = pendingComments.map(comment => {
                    return fetch(`/api/admin/comments/${comment.id_komentar}/status`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: 'approved' })
                    });
                });
                
                // Execute all promises
                Promise.all(updatePromises)
                    .then(() => {
                        // Refresh comments after all updates
                        fetchComments(articleId);
                    })
                    .catch(error => {
                        console.error('Error approving all comments:', error);
                        alert('Failed to approve all comments. Please try again.');
                    });
        }
    }

        // Global functions needed for bulk actions
        window.bulkUpdateStatus = function(status) {
            const selectedIds = [];
            document.querySelectorAll('#commentTableBody input[type="checkbox"]:checked').forEach(checkbox => {
            selectedIds.push(checkbox.value);
            });
        
        if (selectedIds.length === 0) {
                alert('Please select at least one comment.');
            return;
        }
        
            const statusText = status === 'approved' ? 'approve' : (status === 'rejected' ? 'reject' : 'set to pending');
        
            if (confirm(`Are you sure you want to ${statusText} ${selectedIds.length} selected comments?`)) {
                // Create promises for all update requests
                const updatePromises = selectedIds.map(id => {
                    return fetch(`/api/admin/comments/${id}/status`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status })
                    });
                });
                
                // Execute all promises
                Promise.all(updatePromises)
                    .then(() => {
                        // Refresh comments after all updates
                        fetchComments(articleId);
                    })
                    .catch(error => {
                        console.error('Error updating comments:', error);
                        alert('Failed to update comments. Please try again.');
                    });
            }
        };
        
        window.confirmMultipleDelete = function() {
            const selectedIds = [];
            document.querySelectorAll('#commentTableBody input[type="checkbox"]:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });
            
            if (selectedIds.length === 0) {
                alert('Please select at least one comment.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedIds.length} selected comments?`)) {
                // Create promises for all delete requests
                const deletePromises = selectedIds.map(id => {
                    return fetch(`/api/admin/comments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                });
                
                // Execute all promises
                Promise.all(deletePromises)
                    .then(() => {
                        // Refresh comments after all deletions
                        fetchComments(articleId);
                    })
                    .catch(error => {
                        console.error('Error deleting comments:', error);
                        alert('Failed to delete comments. Please try again.');
                    });
    }
        };
    });
</script>
@endpush

@endsection 