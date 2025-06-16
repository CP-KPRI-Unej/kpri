@extends('article.layouts.app')

@section('title', 'Semua Artikel')

@section('content')
    <section class="relative bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Artikel & Berita</h1>
                <p class="text-xl md:text-2xl mb-8 text-orange-100">Informasi terkini seputar KPRI Universitas Jember</p>
                <div class="flex justify-center">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-2">
                        <div class="flex items-center space-x-4 text-sm" id="statsInfo">
                            <span class="flex items-center">
                                <i class="fas fa-newspaper mr-2"></i>
                                <span id="articleCountText">0 Artikel</span>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                <span id="totalViewsText">0 Pembaca</span>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <span id="latestUpdateText">-</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-16 h-16 bg-white bg-opacity-10 rounded-full animate-pulse delay-1000">
        </div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-white bg-opacity-10 rounded-full animate-pulse delay-500"></div>
    </section>

    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari artikel..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 flex-col md:flex-row">
                    <select id="categoryFilter"
                        class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Kategori</option>
                        <option value="pelatihan">Pelatihan</option>
                        <option value="penghargaan">Penghargaan</option>
                        <option value="inovasi">Inovasi</option>
                        <option value="keuangan">Keuangan</option>
                    </select>

                    <select id="sortFilter"
                        class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="popular">Terpopuler</option>
                        <option value="title">A-Z</option>
                    </select>

                    <div class="flex bg-white dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600">
                        <button id="gridView"
                            class="px-4 py-3 text-amber-500 bg-amber-50 dark:bg-amber-900 rounded-l-lg transition duration-300">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="listView"
                            class="px-4 py-3 text-gray-500 dark:text-gray-400 hover:text-amber-500 rounded-r-lg transition duration-300">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Artikel Terbaru</h2>
                <div class="w-20 h-1 bg-amber-500 rounded"></div>
            </div>

            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl overflow-hidden shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-2" id="featuredArticle">
                </div>
            </div>

        </div>
    </section>

    <section class="py-12 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Semua Artikel</h2>
                    <div class="w-20 h-1 bg-amber-500 rounded"></div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan <span id="articleCount">12</span> artikel
                </div>
            </div>

            <div id="articlesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            </div>

            <div class="text-center mt-12">
                <button id="loadMoreBtn"
                    class="inline-flex items-center bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 px-8 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>
                    Muat Lebih Banyak
                </button>
            </div>

            <div id="noResults" class="text-center py-12 hidden">
                <i class="fas fa-search text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">Tidak ada artikel ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-500">Coba ubah kata kunci pencarian atau filter yang digunakan</p>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const articlesContainer = document.getElementById('articlesContainer');
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortFilter = document.getElementById('sortFilter');
            const articleCount = document.getElementById('articleCount');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const noResults = document.getElementById('noResults');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            let allArticles = [];
            let visibleCount = 6;

            function fetchArticles() {
                fetch('https://6264-180-245-74-56.ngrok-free.app/api/articles')
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            allArticles = data.data;
                            updateStatsInfo(allArticles);
                            sortAndRenderArticles();
                            renderFeaturedArticle();
                        }
                    });
            }

            function renderFeaturedArticle() {
                if (allArticles.length === 0) return;

                const featured = [...allArticles]
                    .filter(a => a.release_date)
                    .sort((a, b) => new Date(b.release_date) - new Date(a.release_date))[0];

                const container = document.getElementById('featuredArticle');
                container.innerHTML = `
        <div class="p-8 lg:p-12 text-white">
            <div class="mb-4">
                <span class="inline-block bg-white bg-opacity-20 text-white text-xs px-3 py-1 rounded-full mb-4">
                    <i class="fas fa-star mr-1"></i>
                    Artikel Terbaru
                </span>
            </div>
            <h3 class="text-2xl lg:text-3xl font-bold mb-4 leading-tight">
                ${featured.title}
            </h3>
            <p class="text-orange-100 mb-6 leading-relaxed">
                ${featured.excerpt}
            </p>
            <div class="flex items-center space-x-6 mb-6 text-sm text-orange-100">
                <span class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    ${featured.author || 'Admin'}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    ${formatDate(featured.release_date)}
                </span>
            </div>
            <a href="/articles/${featured.id}"
                class="inline-flex items-center bg-white text-amber-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition duration-300">
                Baca Selengkapnya
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="relative">
            <img src="${featured.thumbnail || '/images/hero.png'}" alt="Featured Article" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-30"></div>
        </div>
    `;
            }

            function updateStatsInfo(articles) {
                const articleCountText = document.getElementById('articleCountText');
                const totalViewsText = document.getElementById('totalViewsText');
                const latestUpdateText = document.getElementById('latestUpdateText');

                const totalArticles = articles.length;
                const totalViews = articles.reduce((sum, article) => sum + (article.views || 0), 0);
                const latestDate = articles
                    .filter(a => a.release_date)
                    .map(a => new Date(a.release_date))
                    .sort((a, b) => b - a)[0];

                articleCountText.textContent = `${totalArticles} Artikel`;
                totalViewsText.textContent = `${totalViews.toLocaleString('id-ID')} Pembaca`;
                latestUpdateText.textContent = latestDate ?
                    latestDate.toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) :
                    '-';
            }



            function renderArticles(articles) {
                articlesContainer.innerHTML = '';
                articles.slice(0, visibleCount).forEach(article => {
                    const card = document.createElement('article');
                    card.className =
                        `article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105`;
                    card.setAttribute('data-title', article.title.toLowerCase());
                    card.setAttribute('data-category', article.category?.toLowerCase() || '');
                    card.setAttribute('data-date', article.created_at);

                    card.innerHTML = `
                        <div class="relative">
                            <img src="${article.thumbnail || '{{ asset('images/hero.png') }}'}" alt="Article" class="w-full h-48 object-cover">
                            <div class="absolute top-4 left-4">
                                <span class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full">${article.tags || 'Lainnya'}</span>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                <span class="flex items-center"><i class="fas fa-calendar mr-1"></i>${formatDate(article.release_date)}</span>
                                <span class="flex items-center"><i class="fas fa-eye mr-1"></i>${article.views || 0} views</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                                <a href="/articles/${article.slug}">${article.title}</a>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                ${article.excerpt || ''}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">${article.author || 'Admin'}</span>
                                </div>
                                <a href="/articles/${article.id}" class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                    Baca →
                                </a>
                            </div>
                        </div>
                    `;
                    articlesContainer.appendChild(card);
                });

                articleCount.textContent = articles.length;
                noResults.classList.toggle('hidden', articles.length > 0);
                loadMoreBtn.style.display = articles.length > visibleCount ? 'inline-flex' : 'none';
            }

            function sortAndRenderArticles() {
                let filtered = allArticles.filter(article => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const category = categoryFilter.value.toLowerCase();
                    const matchesTitle = article.title.toLowerCase().includes(searchTerm);
                    const matchesCategory = !category || (article.category?.toLowerCase() === category);
                    return matchesTitle && matchesCategory;
                });

                switch (sortFilter.value) {
                    case 'newest':
                        filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        break;
                    case 'oldest':
                        filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        break;
                    case 'title':
                        filtered.sort((a, b) => a.title.localeCompare(b.title));
                        break;
                    case 'popular':
                        filtered.sort((a, b) => (b.views || 0) - (a.views || 0));
                        break;
                }

                renderArticles(filtered);
            }

            function formatDate(dateStr) {
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            searchInput.addEventListener('input', sortAndRenderArticles);
            categoryFilter.addEventListener('change', sortAndRenderArticles);
            sortFilter.addEventListener('change', sortAndRenderArticles);

            loadMoreBtn.addEventListener('click', () => {
                visibleCount += 6;
                sortAndRenderArticles();
            });

            gridView.addEventListener('click', () => {
                articlesContainer.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
                gridView.classList.add('text-amber-500', 'bg-amber-50');
                listView.classList.remove('text-amber-500', 'bg-amber-50');
            });

            listView.addEventListener('click', () => {
                articlesContainer.className = 'space-y-6';
                listView.classList.add('text-amber-500', 'bg-amber-50');
                gridView.classList.remove('text-amber-500', 'bg-amber-50');
            });

            fetchArticles();
            renderFeaturedArticle();

        });
    </script>
@endsection
