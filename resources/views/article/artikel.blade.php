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
                        <div class="flex items-center space-x-4 text-sm">
                            <span class="flex items-center">
                                <i class="fas fa-newspaper mr-2"></i>
                                12 Artikel
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                1,234 Pembaca
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                Update Terbaru
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

                <div class="flex space-x-4">
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
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-8 lg:p-12 text-white">
                        <div class="mb-4">
                            <span
                                class="inline-block bg-white bg-opacity-20 text-white text-xs px-3 py-1 rounded-full mb-4">
                                <i class="fas fa-star mr-1"></i>
                                Artikel Terbaru
                            </span>
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold mb-4 leading-tight">
                            PENINGKATAN KAPASITAS PENGELOLA KKPRI UNEJ UNTUK KOPERASI YANG MANDIRI DAN BERKELAS
                        </h3>
                        <p class="text-orange-100 mb-6 leading-relaxed">
                            Koperasi Pegawai Republik Indonesia (KPRI) Universitas Jember merupakan salah satu koperasi
                            percontohan terbaik di Indonesia yang telah berdiri sejak 1979...
                        </p>
                        <div class="flex items-center space-x-6 mb-6 text-sm text-orange-100">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Adi Pramono
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                3 Desember 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                245 views
                            </span>
                        </div>
                        <a href="{{ route('articles.show') }}"
                            class="inline-flex items-center bg-white text-amber-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition duration-300">
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    <div class="relative">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-FTptesYC9SooLsU9GkHyPjMVXQnTcc.png"
                            alt="Featured Article" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-30">
                        </div>
                    </div>
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
                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="pelatihan" data-date="2023-12-03"
                    data-title="PELATIHAN PENGELOLA KKPRI UNEJ DAN BERKELAS">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full">Pelatihan</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                15 Nov 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                156 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                4 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">PELATIHAN PENGELOLA KKPRI UNEJ DAN BERKELAS</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            Pelatihan dan peningkatan kapasitas Pengelola KKPRI UNEJ merupakan agenda rutin Koperasi KPRI
                            UNEJ, hal ini bertujuan untuk meningkatkan kualitas pelayanan...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Admin KPRI</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>

                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="penghargaan" data-date="2023-10-20"
                    data-title="KPRI UNEJ RAIH PENGHARGAAN KOPERASI TERBAIK">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">Penghargaan</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                20 Okt 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                289 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                3 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">KPRI UNEJ RAIH PENGHARGAAN KOPERASI TERBAIK</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            KPRI Universitas Jember kembali menorehkan prestasi membanggakan dengan meraih penghargaan
                            sebagai koperasi terbaik tingkat nasional...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Humas KPRI</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>

                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="inovasi" data-date="2023-10-05" data-title="INOVASI LAYANAN DIGITAL KPRI UNEJ">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-500 text-white text-xs px-3 py-1 rounded-full">Inovasi</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                5 Okt 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                198 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                6 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">INOVASI LAYANAN DIGITAL KPRI UNEJ</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            KPRI UNEJ meluncurkan platform digital terbaru untuk memudahkan anggota dalam mengakses berbagai
                            layanan koperasi secara online...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tim IT</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>

                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="keuangan" data-date="2023-09-15"
                    data-title="LAPORAN KEUANGAN KPRI UNEJ SEMESTER 1 2023">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-purple-500 text-white text-xs px-3 py-1 rounded-full">Keuangan</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                15 Sep 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                324 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                8 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">LAPORAN KEUANGAN KPRI UNEJ SEMESTER 1 2023</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            Transparansi keuangan menjadi komitmen utama KPRI UNEJ. Berikut adalah laporan keuangan semester
                            pertama tahun 2023 yang menunjukkan pertumbuhan positif...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Bendahara</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>

                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="pelatihan" data-date="2023-08-20" data-title="WORKSHOP MANAJEMEN KOPERASI MODERN">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full">Pelatihan</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                20 Agu 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                167 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                5 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">WORKSHOP MANAJEMEN KOPERASI MODERN</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            KPRI UNEJ mengadakan workshop manajemen koperasi modern untuk meningkatkan kemampuan pengelolaan
                            dan adaptasi terhadap perkembangan zaman...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Sekretaris</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>

                <article
                    class="article-card bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:transform hover:scale-105"
                    data-category="inovasi" data-date="2023-07-10" data-title="PELUNCURAN APLIKASI MOBILE KPRI UNEJ">
                    <div class="relative">
                        <img src="{{ asset('images/hero.png') }}" alt="Article" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-500 text-white text-xs px-3 py-1 rounded-full">Inovasi</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <button
                                class="bg-white bg-opacity-80 hover:bg-opacity-100 text-gray-700 p-2 rounded-full transition duration-300">
                                <i class="fas fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                10 Jul 2023
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                412 views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                4 min
                            </span>
                        </div>
                        <h3
                            class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-amber-500 transition duration-300">
                            <a href="#">PELUNCURAN APLIKASI MOBILE KPRI UNEJ</a>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                            Aplikasi mobile KPRI UNEJ resmi diluncurkan untuk memberikan kemudahan akses layanan koperasi
                            bagi seluruh anggota kapan saja dan dimana saja...
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Developer</span>
                            </div>
                            <a href="#"
                                class="text-amber-500 hover:text-amber-600 font-medium text-sm transition duration-300">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>
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
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortFilter = document.getElementById('sortFilter');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const articlesContainer = document.getElementById('articlesContainer');
            const articleCount = document.getElementById('articleCount');
            const noResults = document.getElementById('noResults');
            const loadMoreBtn = document.getElementById('loadMoreBtn');

            let articles = Array.from(document.querySelectorAll('.article-card'));
            let visibleArticles = 6;

            searchInput.addEventListener('input', function() {
                filterArticles();
            });

            categoryFilter.addEventListener('change', function() {
                filterArticles();
            });

            sortFilter.addEventListener('change', function() {
                sortArticles();
            });

            gridView.addEventListener('click', function() {
                setGridView();
            });

            listView.addEventListener('click', function() {
                setListView();
            });

            loadMoreBtn.addEventListener('click', function() {
                visibleArticles += 6;
                showArticles();
            });

            function filterArticles() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategory = categoryFilter.value;

                let filteredArticles = articles.filter(article => {
                    const title = article.dataset.title.toLowerCase();
                    const category = article.dataset.category;

                    const matchesSearch = title.includes(searchTerm);
                    const matchesCategory = !selectedCategory || category === selectedCategory;

                    return matchesSearch && matchesCategory;
                });

                showFilteredArticles(filteredArticles);
            }

            function sortArticles() {
                const sortBy = sortFilter.value;

                articles.sort((a, b) => {
                    switch (sortBy) {
                        case 'newest':
                            return new Date(b.dataset.date) - new Date(a.dataset.date);
                        case 'oldest':
                            return new Date(a.dataset.date) - new Date(b.dataset.date);
                        case 'title':
                            return a.dataset.title.localeCompare(b.dataset.title);
                        case 'popular':
                            return Math.random() - 0.5;
                        default:
                            return 0;
                    }
                });

                filterArticles();
            }

            function showFilteredArticles(filteredArticles) {
                articles.forEach(article => {
                    article.style.display = 'none';
                });

                filteredArticles.slice(0, visibleArticles).forEach(article => {
                    article.style.display = 'block';
                });

                articleCount.textContent = filteredArticles.length;

                if (filteredArticles.length === 0) {
                    noResults.classList.remove('hidden');
                    loadMoreBtn.style.display = 'none';
                } else {
                    noResults.classList.add('hidden');
                    loadMoreBtn.style.display = filteredArticles.length > visibleArticles ? 'inline-flex' : 'none';
                }
            }

            function showArticles() {
                filterArticles();
            }

            function setGridView() {
                articlesContainer.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
                gridView.classList.add('text-amber-500', 'bg-amber-50', 'dark:bg-amber-900');
                gridView.classList.remove('text-gray-500', 'dark:text-gray-400');
                listView.classList.remove('text-amber-500', 'bg-amber-50', 'dark:bg-amber-900');
                listView.classList.add('text-gray-500', 'dark:text-gray-400');
            }

            function setListView() {
                articlesContainer.className = 'space-y-6';
                listView.classList.add('text-amber-500', 'bg-amber-50', 'dark:bg-amber-900');
                listView.classList.remove('text-gray-500', 'dark:text-gray-400');
                gridView.classList.remove('text-amber-500', 'bg-amber-50', 'dark:bg-amber-900');
                gridView.classList.add('text-gray-500', 'dark:text-gray-400');

                articles.forEach(article => {
                    if (listView.classList.contains('text-amber-500')) {
                        article.classList.add('flex', 'flex-row');
                        const img = article.querySelector('img');
                        if (img) {
                            img.parentElement.classList.add('w-1/3');
                        }
                        const content = article.querySelector('.p-6');
                        if (content) {
                            content.classList.add('w-2/3');
                        }
                    }
                });
            }

            document.querySelectorAll('.fa-bookmark').forEach(bookmark => {
                bookmark.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.toggle('fas');
                    this.classList.toggle('far');

                    if (this.classList.contains('fas')) {
                        this.style.color = '#f59e0b';
                    } else {
                        this.style.color = '';
                    }
                });
            });

            showArticles();
        });
    </script>
@endsection
