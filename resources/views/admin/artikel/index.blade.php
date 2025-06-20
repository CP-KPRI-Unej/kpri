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
    
    .bulk-actions-container {
        display: none;
    }
    
    .bulk-actions-container.active {
        display: flex;
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
        
        <!-- Bulk actions -->
        <div id="bulkActionsContainer" class="bulk-actions-container items-center bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-md mr-auto">
            <span class="text-sm mr-2"><span id="selectedCount">0</span> terpilih</span>
            <div x-data="{ open: false, posStyle: {} }">
                <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="flex items-center text-sm bg-white dark:bg-gray-800 px-3 py-1 rounded border">
                    Aksi Massal
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <button onclick="deleteBulkArticles()" class="w-full text-left block px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="bi bi-trash mr-2"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex space-x-2 w-full md:w-auto justify-end">
           
            
            <a href="/admin/artikel/create" class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm flex items-center hover:bg-orange-600 transition-colors">
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
                                <input type="checkbox" id="selectAllCheckbox" class="form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300">
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
                            Oleh
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
                        <td colspan="8" class="px-3 py-4 text-center">
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
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Menampilkan <span class="font-medium" id="showingStart">1</span> - <span class="font-medium" id="showingEnd">0</span> dari <span class="font-medium" id="artikelCount">0</span> artikel
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination" id="pagination">
                        <!-- Pagination buttons will be dynamically inserted here -->
                        <button id="prevPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="sr-only">Previous</span>
                            <!-- Heroicon name: solid/chevron-left -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="pageNumbers" class="flex"></div>
                        <button id="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="sr-only">Next</span>
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi untuk menghitung posisi popup - didefinisikan di luar DOMContentLoaded agar bisa diakses secara global
    function getPopupPosition(event) {
        const button = event.currentTarget;
        const rect = button.getBoundingClientRect();
        const popupWidth = 192; // w-48 = 12rem = 192px
        
        // Pastikan popup tidak keluar dari batas kanan layar
        let leftPos = rect.right - popupWidth;
        if (leftPos < 10) leftPos = 10; // Beri sedikit margin jika terlalu kiri
        
        return {
            position: 'fixed',
            top: `${rect.bottom + 5}px`, // 5px offset dari tombol
            left: `${leftPos}px`,
            width: `${popupWidth}px`
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }

        // Pagination variables
        let currentPage = 1;
        let itemsPerPage = 10;
        let totalItems = 0;
        let totalPages = 0;
        let sortField = 'tgl_rilis';
        let sortDirection = 'desc';
        let searchQuery = '';
        
        // Fetch articles from API
        fetchArticles();

        // Search functionality
        const searchInput = document.getElementById('searchArticle');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchQuery = this.value.toLowerCase();
                    // Reset to page 1 when searching
                    currentPage = 1;
                    // Fetch articles with search query
                    fetchArticles();
                }, 300);
            });
        }
        
        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('#artikelTableBody input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }
        
        // Event delegation for checkbox changes in the table body
        document.getElementById('artikelTableBody').addEventListener('change', function(e) {
            if (e.target && e.target.type === 'checkbox') {
                updateSelectedCount();
            }
        });

        // Pagination event listeners
        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                fetchArticles();
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                fetchArticles();
            }
        });

        function updateSelectedCount() {
            const selectedCheckboxes = document.querySelectorAll('#artikelTableBody input[type="checkbox"]:checked');
            const count = selectedCheckboxes.length;
            document.getElementById('selectedCount').textContent = count;
            
            const bulkActionsContainer = document.getElementById('bulkActionsContainer');
            if (count > 0) {
                bulkActionsContainer.classList.add('active');
            } else {
                bulkActionsContainer.classList.remove('active');
                // Uncheck the select all checkbox if no items are selected
                document.getElementById('selectAllCheckbox').checked = false;
            }
        }

        function fetchArticles() {
            // Show loading state
            document.getElementById('artikelTableBody').innerHTML = `
                <tr>
                    <td colspan="8" class="px-3 py-4 text-center">
                        <div class="animate-pulse flex justify-center">
                            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="ml-2">Loading artikels...</span>
                        </div>
                    </td>
                </tr>
            `;
            
            // Build query parameters
            const params = new URLSearchParams({
                page: currentPage,
                per_page: itemsPerPage,
                sort_field: sortField,
                sort_direction: sortDirection
            });
            
            if (searchQuery) {
                params.append('search', searchQuery);
            }
            
            fetch(`/api/admin/articles?${params.toString()}`, {
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
            .then(response => {
                if (!response) return;
                
                // Update pagination variables from API response
                const { data, meta } = response;
                totalItems = meta.total;
                totalPages = meta.total_pages;
                currentPage = meta.current_page;
                
                // Render the articles
                renderArticles(data);
                
                // Update pagination UI
                updatePagination();
                
                // Update article count display
                document.getElementById('articleCount').textContent = totalItems;
            })
            .catch(error => {
                console.error('Error fetching articles:', error);
                document.getElementById('artikelTableBody').innerHTML = `
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-center text-sm text-red-500">
                            Error loading articles. Please try again later.
                        </td>
                    </tr>
                `;
            });
        }

        function updatePagination() {
            // Update pagination info
            document.getElementById('showingStart').textContent = totalItems === 0 ? '0' : ((currentPage - 1) * itemsPerPage + 1);
            const end = Math.min(currentPage * itemsPerPage, totalItems);
            document.getElementById('showingEnd').textContent = end;
            document.getElementById('artikelCount').textContent = totalItems;
            
            // Enable/disable previous/next buttons
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
            
            // Generate page number buttons
            const pageNumbersContainer = document.getElementById('pageNumbers');
            pageNumbersContainer.innerHTML = '';
            
            // Determine which page numbers to show (max 5)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4 && startPage > 1) {
                startPage = Math.max(1, endPage - 4);
            }
            
            // Add "..." button for first page if needed
            if (startPage > 1) {
                const firstPageBtn = createPageButton(1);
                pageNumbersContainer.appendChild(firstPageBtn);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = "relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300";
                    ellipsis.textContent = "...";
                    pageNumbersContainer.appendChild(ellipsis);
                }
            }
            
            // Add page number buttons
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = createPageButton(i);
                pageNumbersContainer.appendChild(pageBtn);
            }
            
            // Add "..." button for last page if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = "relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300";
                    ellipsis.textContent = "...";
                    pageNumbersContainer.appendChild(ellipsis);
                }
                
                const lastPageBtn = createPageButton(totalPages);
                pageNumbersContainer.appendChild(lastPageBtn);
            }
        }
        
        function createPageButton(pageNumber) {
            const button = document.createElement('button');
            button.type = "button";
            
            if (pageNumber === currentPage) {
                button.className = "relative inline-flex items-center px-4 py-2 border border-orange-500 dark:border-orange-400 bg-orange-50 dark:bg-orange-900 text-sm font-medium text-orange-600 dark:text-orange-200";
            } else {
                button.className = "relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600";
            }
            
            button.textContent = pageNumber;
            button.addEventListener('click', function() {
                currentPage = pageNumber;
                fetchArticles();
            });
            
            return button;
        }

        function renderArticles(articles) {
            const tbody = document.getElementById('artikelTableBody');
            
            if (articles.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada data artikel
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            
            articles.forEach(artikel => {
                let statusClass = '';
                let statusText = '';
                
                if (artikel.status === 'published') {
                    statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                    statusText = 'Published';
                } else if (artikel.status === 'draft') {
                    statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                    statusText = 'Draft';
                } else if (artikel.status === 'archived') {
                    statusClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300';
                    statusText = 'Diarsipkan';
                }
                
                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 ${artikel.has_pending_comments ? 'row-has-pending-comments' : ''}" 
                        data-article-name="${artikel.nama_artikel}" 
                        data-article-description="${artikel.deskripsi_artikel}"
                        data-article-id="${artikel.id_artikel}">
                        ${artikel.pending_comments > 0 ? `
                        <td class="px-3 py-4 whitespace-nowrap relative">
                            <div class="flex items-center">
                                <input type="checkbox" class="article-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" value="${artikel.id_artikel}">
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
                                <input type="checkbox" class="article-checkbox form-checkbox h-4 w-4 text-orange-500 rounded border-gray-300" value="${artikel.id_artikel}">
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
                            ${artikel.user_name || 'Unknown'}
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
                            <div x-data="{ open: false, posStyle: {} }">
                                <button @click="open = !open; if (open) posStyle = getPopupPosition($event)" class="text-gray-400 hover:text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak :style="posStyle" class="fixed rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
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
            
            // Reset the selected count after rendering
            updateSelectedCount();
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
                
                    // Refresh the article list with current pagination
                    fetchArticles();
                })
                .catch(error => {
                    console.error('Error deleting article:', error);
                    alert('Failed to delete article. Please try again.');
                });
            }
        };
        
        // Function to handle bulk delete
        window.deleteBulkArticles = function() {
            const selectedCheckboxes = document.querySelectorAll('#artikelTableBody input[type="checkbox"]:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Tidak ada artikel yang dipilih');
                return;
            }
            
            if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} artikel yang dipilih?`)) {
                const token = localStorage.getItem('access_token');
                
                // For multiple deletions, you might want to use Promise.all for parallel requests
                // or create a specific bulk delete API endpoint
                const deletePromises = selectedIds.map(id => 
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
                            throw new Error(`Failed to delete article ${id}`);
                        }
                        return response.json();
                    })
                );
                
                Promise.all(deletePromises)
                    .then(() => {
                        // Go back to first page after bulk delete
                        currentPage = 1;
                        // Refresh the article list
                        fetchArticles();
                        alert(`${selectedIds.length} artikel berhasil dihapus`);
                    })
                    .catch(error => {
                        console.error('Error deleting articles:', error);
                        alert('Gagal menghapus beberapa artikel. Silakan coba lagi.');
                        // Refresh anyway to show the current state
                        fetchArticles();
                    });
            }
        };
    });
</script>
@endpush

@endsection 