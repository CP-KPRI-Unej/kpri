@extends('article.layouts.app')

@section('title', 'Detail Artikel')

@section('content')
    <article id="article-detail" class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto" id="article-container">

            </div>
        </div>
    </article>

    <section class="py-12 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 id="comment-title" class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Komentar</h2>
                <div class="space-y-6 mb-12" id="comment-list"></div>

                <div class="bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Tinggalkan Komentar</h3>

                    <div id="success-message"
                        class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6 hidden">
                        Komentar berhasil ditambahkan!
                    </div>

                    <form id="comment-form" class="space-y-6">
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                (Opsional)</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
                        </div>
                        <div>
                            <label for="content"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Komentar <span
                                    class="text-red-500">*</span></label>
                            <textarea id="content" name="content" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white resize-none"
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="related-articles"></div>
            </div>
        </div>
    </section>

    <script>
        const API_URL = 'https://92b0-2001-448a-5122-4227-613a-c69c-d8bf-3a6a.ngrok-free.app/api/articles/1';

        async function fetchArticle() {
            const res = await fetch('https://92b0-2001-448a-5122-4227-613a-c69c-d8bf-3a6a.ngrok-free.app/api/articles/{{ $id }}');
            const {
                data
            } = await res.json();

            renderArticle(data);
            renderComments(data.comments);
            fetchRelatedArticles(data.tags);
        }

        function renderArticle(data) {
            const tags = data.tags.split(',').map(tag => tag.trim());

            document.getElementById('article-container').innerHTML = `
            <header class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    ${data.title}
                </h1>
                <div class="flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <div class="flex items-center mr-6 mb-2">
                        <i class="fas fa-user mr-2 text-amber-500"></i><span>${data.author}</span>
                    </div>
                    <div class="flex items-center mr-6 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-amber-500"></i><span>${formatDate(data.release_date)}</span>
                    </div>
                </div>
            </header>

            <div class="mb-8">
                <img src="${data.thumbnail || '{{ asset('images/hero.png') }}'}" alt="KPRI UNEJ Building"
                    class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
            </div>

            <div class="prose prose-lg max-w-none dark:prose-invert mb-12">
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${data.content}</p>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-12">
                <div class="flex flex-wrap items-center mb-5">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 mr-3">Tags:</span>
                    <div class="flex flex-wrap gap-2">
                        ${tags.map(tag => `<span class="inline-block bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 text-xs px-3 py-1 rounded-full">${tag}</span>`).join('')}
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-12">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bagikan Artikel</h3>
                    <div class="flex flex-col gap-3 md:flex-row">
                        <a href="#"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-300">
                            <i class="fab fa-facebook-f mr-2"></i>
                            Facebook
                        </a>
                        <a href="#"
                            class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-md transition duration-300">
                            <i class="fab fa-twitter mr-2"></i>
                            Twitter
                        </a>
                        <a href="#"
                            class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition duration-300">
                            <i class="fab fa-whatsapp mr-2"></i>
                            WhatsApp
                        </a>
                        <button onclick="copyToClipboard()"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition duration-300">
                            <i class="fas fa-link mr-2"></i>
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>
        `;
        }

        function renderComments(comments) {
            const list = document.getElementById('comment-list');
            list.innerHTML = '';
            document.getElementById('comment-title').textContent = `Komentar (${comments.length})`;

            comments.forEach(c => {
                const div = document.createElement('div');
                div.className = 'bg-white dark:bg-gray-700 rounded-lg p-6 shadow-sm';
                div.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 dark:text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h4 class="font-semibold text-gray-900 dark:text-white">${c.name}</h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">${c.date}</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">${c.content}</p>
                    </div>
                </div>
            `;
                list.appendChild(div);
            });
        }

        async function fetchRelatedArticles(tags) {
            const tagParams = tags.split(',').map(t => t.trim()).join(',');
            const res = await fetch(
                `https://b631-180-245-74-56.ngrok-free.app/api/articles/relevant?tags=${encodeURIComponent(tagParams)}`);
            const {
                data
            } = await res.json();

            const related = document.getElementById('related-articles');
            related.innerHTML = data.slice(0, 3).map(article => `
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:scale-105 transition">
                <img src="{{ asset('images/hero.png') }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                        <a href="/artikel/${article.id}" class="hover:text-amber-500 dark:hover:text-amber-400">${article.title}</a>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">${formatDate(article.release_date)}</p>
                    <a href="/artikel/${article.id}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium py-2 px-4 rounded-md">
                        Baca Selengkapnya
                    </a>
                </div>
            </article>
        `).join('');
        }

        function formatDate(dateStr) {
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            return new Date(dateStr).toLocaleDateString('id-ID', options);
        }

        document.addEventListener('DOMContentLoaded', fetchArticle);

        document.getElementById('comment-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value.trim() || 'Anonymous';
            const content = document.getElementById('content').value.trim();

            if (!content || content.length > 1000) return;

            try {
                const response = await fetch('https://92b0-2001-448a-5122-4227-613a-c69c-d8bf-3a6a.ngrok-free.app/api/articles/{{ $id }}/comments', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            nama_pengomentar: name,
                            isi_komentar: content
                        })
                    });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    document.getElementById('success-message').classList.remove('hidden');
                    this.reset();

                    setTimeout(() => {
                        document.getElementById('success-message').classList.add('hidden');
                    }, 3000);
                } else {
                    alert('Gagal mengirim komentar. Coba lagi nanti.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengirim komentar.');
            }
        });
    </script>
@endsection
