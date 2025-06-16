@extends('admin.layouts.app')

@section('styles')
<style>
    @keyframes pulse-highlight {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { box-shadow: 0 0 0 5px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    
    .comment-pending {
        animation: pulse-highlight 1.5s infinite;
        background: linear-gradient(135deg, #f59e0b, #ef4444);
    }
    
    .comment-icon {
        transition: all 0.3s ease;
    }
    
    .comment-icon:hover {
        transform: scale(1.1);
    }

    .row-has-pending-comments {
        background-color: rgba(254, 243, 199, 0.2) !important;
    }

    .row-has-pending-comments:hover {
        background-color: rgba(254, 243, 199, 0.4) !important;
    }

    .dark .row-has-pending-comments {
        background-color: rgba(120, 53, 15, 0.15) !important;
    }
    
    .dark .row-has-pending-comments:hover {
        background-color: rgba(120, 53, 15, 0.25) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-semibold">Artikel (<span id="articleCount">0</span>)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan atau edit artikel dari web</p>
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

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
        <div class="relative w-full md:w-64">
            <input id="searchArticle" type="text" class="border rounded-md p-2 w-full pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Cari Artikel">
            <div class="absolute left-3 top-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center px-3 py-2 border rounded-md text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Columns
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                    <!-- Column options would go here -->
                </div>
            </div>
            
            <a href="/admin/artikel/create" class="bg-indigo-800 text-white px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Artikel Baru
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                            Tanggal
                        </th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Komentar
                        </th>
                        <th scope="col" class="relative px-3 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="artikelTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Table rows will be dynamically inserted here -->
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center">
                            <div class="animate-pulse flex justify-center">
                                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="ml-2">Loading artikels...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class=" sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Showing <span class="font-medium" id="artikelCount">0</span> articles
                    </p>
                </div>
                <!-- Pagination would go here if needed -->
            </div>
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

        // Fetch articles from API
        fetchArticles();

        // Search functionality
        const searchInput = document.getElementById('searchArticle');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('#artikelTableBody tr');
                
                tableRows.forEach(row => {
                    if (row.hasAttribute('data-article-name')) {
                        const text = row.getAttribute('data-article-name').toLowerCase();
                        const description = row.getAttribute('data-article-description').toLowerCase();
                        row.style.display = text.includes(searchValue) || description.includes(searchValue) ? '' : 'none';
                    }
                });
            });
        }

        function fetchArticles() {
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
                        return null;
                    }
                    throw new Error('Failed to fetch articles');
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                
                renderArticles(data);
            })
            .catch(error => {
                console.error('Error fetching articles:', error);
                document.getElementById('artikelTableBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-sm text-red-500">
                            Error loading articles. Please try again later.
                        </td>
                    </tr>
                `;
            });
        }

        function renderArticles(articles) {
            const tbody = document.getElementById('artikelTableBody');
            const articleCount = document.getElementById('articleCount');
            const artikelCount = document.getElementById('artikelCount');
            
            if (articles.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data artikel
                        </td>
                    </tr>
                `;
                articleCount.textContent = '0';
                artikelCount.textContent = '0';
                return;
            }
            
            articleCount.textContent = articles.length;
            artikelCount.textContent = articles.length;
            
            let html = '';
            
            articles.forEach(artikel => {
                let statusClass = '';
                let statusText = artikel.status.nama_status;
                
                if (artikel.id_status == 1) {
                    statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    statusText = 'Published';
                } else if (artikel.id_status == 2) {
                    statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                    statusText = 'Draft';
                } else {
                    statusClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300';
                    statusText = 'Diarsipkan';
                }
                
                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 ${artikel.has_pending_comments ? 'row-has-pending-comments' : ''}" 
                        data-article-name="${artikel.nama_artikel}" 
                        data-article-description="${artikel.deskripsi_artikel}">
                        ${artikel.pending_comments > 0 ? `
                        <td class="px-3 py-4 whitespace-nowrap relative">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                            <div class="absolute -left-1 top-1/2 transform -translate-y-1/2">
                                <span class="flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                </span>
                            </div>
                        </td>
                        ` : `
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
                            </div>
                        </td>
                        `}
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">${artikel.nama_artikel.length > 20 ? artikel.nama_artikel.substring(0, 20) + '...' : artikel.nama_artikel}</div>
                                ${artikel.pending_comments > 0 ? `
                                <div class="mt-1 sm:mt-0 sm:ml-2 px-1.5 py-0.5 bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 text-xs rounded-md flex items-center" title="${artikel.pending_comments} komentar menunggu persetujuan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    Baru
                                </div>
                                ` : ''}
                            </div>
                        </td>
                        <td class="px-3 py-4 hidden md:table-cell">
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                ${stripHtmlTags(artikel.deskripsi_artikel).substring(0, 60)}${stripHtmlTags(artikel.deskripsi_artikel).length > 60 ? '...' : ''}
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                            ${formatDate(artikel.tgl_rilis)}
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="mr-2">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-md shadow-sm comment-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        ${artikel.total_comments}
                                    </span>
                                </div>
                                ${artikel.pending_comments > 0 ? `
                                <div>
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white rounded-md shadow-sm comment-pending" title="Komentar menunggu persetujuan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ${artikel.pending_comments}
                                    </span>
                                </div>
                                ` : ''}
                                <a href="/admin/artikel/${artikel.id_artikel}/komentar" class="ml-2 bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-800 p-1 rounded-full transition-colors duration-200 comment-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-40">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="/admin/artikel/${artikel.id_artikel}/edit" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                            <i class="bi bi-pencil mr-2"></i> Edit
                                        </a>
                                        <button onclick="deleteArticle(${artikel.id_artikel})" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="bi bi-trash mr-2"></i> Hapus
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }

        function stripHtmlTags(html) {
            const tmp = document.createElement('DIV');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
        }

        window.deleteArticle = function(id) {
            if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
                const token = localStorage.getItem('access_token');
                
                fetch(`/api/admin/articles/${id}`, {
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
                        if (response.status === 401) {
                            localStorage.removeItem('access_token');
                            window.location.href = '/admin/login';
                            return null;
                        }
                        throw new Error('Failed to delete article');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                
                    // Refresh the article list
                    fetchArticles();
                })
                .catch(error => {
                    console.error('Error deleting article:', error);
                    alert('Failed to delete article. Please try again.');
                });
            }
        };
    });
</script>
@endpush

@endsection 