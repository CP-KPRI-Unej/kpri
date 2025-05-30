@extends('article.layouts.app')

@section('title', 'Peningkatan Kapasitas Pengelola KKPRI UNEJ')

@section('content')
    <article class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <header class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        PENINGKATAN KAPASITAS PENGELOLA KKPRI UNEJ UNTUK KOPERASI YANG MANDIRI DAN BERKELAS
                    </h1>

                    <div class="flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                        <div class="flex items-center mr-6 mb-2">
                            <i class="fas fa-user mr-2 text-amber-500"></i>
                            <span>Adi Pramono</span>
                        </div>
                        <div class="flex items-center mr-6 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-amber-500"></i>
                            <span>3 Desember 2023</span>
                        </div>
                        <div class="flex items-center mr-6 mb-2">
                            <i class="fas fa-clock mr-2 text-amber-500"></i>
                            <span>5 menit baca</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-comments mr-2 text-amber-500"></i>
                            <span>1 komentar</span>
                        </div>
                    </div>
                </header>

                <div class="mb-8">
                    <img src="{{ asset('images/hero.png') }}"
                         alt="KPRI UNEJ Building"
                         class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                </div>

                <div class="prose prose-lg max-w-none dark:prose-invert mb-12">
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed space-y-6">
                        <p>
                            Koperasi Pegawai Republik Indonesia (KPRI) Universitas Jember merupakan salah satu koperasi percontohan terbaik di Indonesia yang telah berdiri sejak 1979. Dengan layanan simpan pinjam, toko kebutuhan harian, dan pengelolaan yang profesional serta transparan, KPRI UNEJ berhasil meraih berbagai penghargaan nasional dan menjadi inspirasi bagi koperasi lain.
                        </p>

                        <p>
                            Didukung penuh oleh civitas akademika Universitas Jember, koperasi ini terus berinovasi dan berkembang sebagai koperasi modern yang mengedepankan kesejahteraan anggota dan semangat gotong royong.
                        </p>

                        <p>
                            Kegiatan ini di adakan di dalam SOMBRA Universitas Jember dengan menghadirkan 100 dan pembicara materi yaitu perkembangan terkini dari Kementerian Koperasi dan UKM RI, Koperasi dan UKM Provinsi Jawa Timur, dan Koperasi dan UKM Kabupaten Jember. Kegiatan ini bertujuan untuk meningkatkan kapasitas pengelola KPRI UNEJ dalam mengelola koperasi yang mandiri dan berkelas.
                        </p>

                        <p>
                            Berbagai kegiatan yang dilakukan antara lain pelatihan manajemen koperasi, pelatihan keuangan koperasi, dan pelatihan teknologi informasi. Semua kegiatan ini diharapkan dapat meningkatkan kualitas pelayanan KPRI UNEJ kepada anggotanya dan menjadikan KPRI UNEJ sebagai koperasi yang mandiri dan berkelas.
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-12">
                    <div class="flex flex-wrap items-center">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 mr-3">Tags:</span>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-block bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs px-3 py-1 rounded-full">KPRI UNEJ</span>
                            <span class="inline-block bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs px-3 py-1 rounded-full">Koperasi</span>
                            <span class="inline-block bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs px-3 py-1 rounded-full">Pelatihan</span>
                            <span class="inline-block bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs px-3 py-1 rounded-full">Kapasitas</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bagikan Artikel</h3>
                    <div class="flex flex-col gap-3 md:flex-row">
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-300">
                            <i class="fab fa-facebook-f mr-2"></i>
                            Facebook
                        </a>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-md transition duration-300">
                            <i class="fab fa-twitter mr-2"></i>
                            Twitter
                        </a>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition duration-300">
                            <i class="fab fa-whatsapp mr-2"></i>
                            WhatsApp
                        </a>
                        <button onclick="copyToClipboard()" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-300">
                            <i class="fas fa-link mr-2"></i>
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <section class="py-12 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                    Komentar (1)
                </h2>

                <div class="space-y-6 mb-12">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 dark:text-gray-400"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Anonymous</h4>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">3 Desember 2023 14:30</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    Artikelnya sangat bermanfaat karena kita bisa mengetahui info info terbaru tentang KPRI UNEJ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Tinggalkan Komentar</h3>

                    <div id="success-message" class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 hidden">
                        Komentar berhasil ditambahkan!
                    </div>

                    <form id="comment-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama (Opsional)
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       placeholder="Nama Anda"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email (Opsional)
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       placeholder="email@example.com"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Komentar <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      rows="4"
                                      placeholder="Keren sekali KPRI, Maju Terus!!!"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-amber-500 focus:border-transparent dark:bg-gray-800 dark:text-white resize-none"
                                      required></textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 1000 karakter</p>
                        </div>

                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-md transition duration-300">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Kirim Komentar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Artikel Terkait</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                        <img src="{{ asset('images/hero.png') }}" alt="Artikel Terkait" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                <a href="#" class="hover:text-amber-500 dark:hover:text-amber-400">
                                    PELATIHAN PENGELOLA KKPRI UNEJ DAN BERKELAS
                                </a>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">15 November 2023</p>
                            <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 px-4 rounded-md transition duration-300">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </article>

                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                        <img src="{{ asset('images/hero.png') }}" alt="Artikel Terkait" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                <a href="#" class="hover:text-amber-500 dark:hover:text-amber-400">
                                    KPRI UNEJ RAIH PENGHARGAAN KOPERASI TERBAIK
                                </a>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">20 Oktober 2023</p>
                            <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 px-4 rounded-md transition duration-300">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </article>

                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                        <img src="{{ asset('images/hero.png') }}" alt="Artikel Terkait" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                <a href="#" class="hover:text-amber-500 dark:hover:text-amber-400">
                                    INOVASI LAYANAN DIGITAL KPRI UNEJ
                                </a>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">5 Oktober 2023</p>
                            <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 px-4 rounded-md transition duration-300">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <script>
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Link berhasil disalin!');
            });
        }

        document.getElementById('comment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value || 'Anonymous';
            const email = document.getElementById('email').value;
            const content = document.getElementById('content').value;

            if (!content.trim()) {
                alert('Komentar tidak boleh kosong!');
                return;
            }

            if (content.length > 1000) {
                alert('Komentar maksimal 1000 karakter!');
                return;
            }

            const commentsContainer = document.querySelector('.space-y-6');
            const newComment = document.createElement('div');
            newComment.className = 'bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm';

            const now = new Date();
            const formattedDate = now.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            newComment.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 dark:text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">${name}</h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">${formattedDate}</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${content}</p>
                    </div>
                </div>
            `;

            commentsContainer.insertBefore(newComment, commentsContainer.firstChild);

            const commentCount = document.querySelector('h2');
            const currentCount = parseInt(commentCount.textContent.match(/\d+/)[0]);
            commentCount.textContent = `Komentar (${currentCount + 1})`;

            const successMessage = document.getElementById('success-message');
            successMessage.classList.remove('hidden');

            this.reset();

            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);

            newComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        const textarea = document.getElementById('content');
        const maxLength = 1000;

        const counterElement = document.createElement('p');
        counterElement.className = 'mt-1 text-sm text-gray-500 dark:text-gray-400';
        counterElement.textContent = `0/${maxLength} karakter`;
        textarea.parentNode.appendChild(counterElement);

        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counterElement.textContent = `${currentLength}/${maxLength} karakter`;

            if (currentLength > maxLength) {
                counterElement.className = 'mt-1 text-sm text-red-500';
                this.value = this.value.substring(0, maxLength);
            } else if (currentLength > maxLength * 0.9) {
                counterElement.className = 'mt-1 text-sm text-yellow-500';
            } else {
                counterElement.className = 'mt-1 text-sm text-gray-500 dark:text-gray-400';
            }
        });
    </script>
@endsection
