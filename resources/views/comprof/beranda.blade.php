@extends('comprof.layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="relative  text-white">
        <div class="absolute inset-0 bg-black opacity-60 z-0"></div>
        <img src="{{ asset('images/hero.png') }}" alt="Hero Background"
            class="absolute inset-0 w-full h-full object-cover z-[-1]">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 lg:py-40 relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">KPRI Universitas Jember untuk Masa
                    Depan yang Lebih Sejahtera</h1>
                <p class="text-lg md:text-xl mb-8 text-gray-200">Solusi keuangan dan kebutuhan harian yang dikelola secara
                    profesional dan transparan.</p>
                <a href="{{ route('tentang-kami') }}"
                    class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium py-3 px-6 rounded-md transition duration-300">Pelajari
                    Lebih Lanjut</a>
            </div>
        </div>
    </section>
    <section class="py-12 px-4 mb-5" x-data>
        <div class="flex flex-col lg:flex-row gap-10 justify-center items-start">
            <div class="flex flex-col md:flex-row gap-6 w-full lg:w-[40%]">
                <div class="w-full md:w-1/2 h-3/4">
                    <img src="{{ asset('images/visi-misi-1.png') }}" alt="Cooperative Officials"
                        class="w-full h-full object-cover rounded-3xl" />
                </div>
                <div class="w-full md:w-1/2 h-full">
                    <img src="{{ asset('images/visi-misi-2.png') }}" alt="Cooperative Officials"
                        class="w-full h-full object-cover rounded-3xl max-h-[600px]" />
                </div>
            </div>

            <div class="space-y-10 w-full lg:w-[60%]">
                <div id="visi" class="bg-orange-500 text-white p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Visi
                        <div class="flex w-16 gap-1">
                            <hr class="border-2 w-10 border-red-600 rounded-sm" />
                            <hr class="border-2 w-2 border-red-600 rounded-sm" />
                        </div>
                    </h2>
                    <p class="text-sm text-black dark:text-white" x-html="$store.beranda.visi">
                        Memuat...
                    </p>
                </div>

                <div class="border border-gray-200 p-6 rounded-lg bg-white">
                    <h2 class="text-xl font-bold mb-4 dark:text-black">Misi
                        <div class="flex w-16 gap-1">
                            <hr class="border-2 w-10 border-red-600 rounded-sm" />
                            <hr class="border-2 w-2 border-red-600 rounded-sm" />
                        </div>
                    </h2>
                    <p class="text-sm text-black" x-html="$store.beranda.misi">
                        Memuat...
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <div
                class="relative bg-orange-400 rounded-lg md:p-10 md:pb-0 p-6 pb-0 mb-12 flex flex-col md:flex-row gap-6 items-center md:items-start">
                <div class="flex-1 text-sm text-white">
                    <h3 class="font-bold text-lg mb-4">
                        KOPERASI KPRI UNEJ ADALAH SALAH SATU KOPERASI PERCONTOHAN TERBAIK DI INDONESIA
                    </h3>
                    <p class="text-justify">
                        Koperasi Pegawai Republik Indonesia (KPRI) Universitas Jember merupakan salah satu koperasi
                        percontohan terbaik di Indonesia sejak 1979. Layanan simpan pinjam, toko kebutuhan harian, dan
                        pengelolaan profesional menjadikan KPRI UNEJ inspirasi nasional yang terus berinovasi demi
                        kesejahteraan anggota.
                    </p>
                </div>
                <div class="w-full md:w-1/3">
                    <img src="{{ asset('images/info.png') }}" alt="Koperasi KPRI" class="rounded-lg w-full object-cover" />
                </div>
            </div>
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Artikel Terbaru</h2>


            <div id="artikel-splide" class="splide" x-data>
                <div class="splide__track">
                    <ul class="splide__list">
                        <template x-for="article in $store.artikel.articles" :key="article.id">
                            <li class="splide__slide">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition duration-300 hover:scale-105 p-4">
                                    <img :src="article.thumbnail ?? '{{ asset('images/info.png') }}'" alt="Artikel"
                                        class="w-full h-48 object-cover rounded-md mb-4">
                                    <h4 class="text-xl font-bold mb-3 text-gray-800 dark:text-white" x-text="article.title">
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4" x-text="article.excerpt"></p>
                                    <a :href="'/artikel/' + article.id"
                                        class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">Baca
                                        Selengkapnya</a>
                                </div>
                            </li>
                        </template>

                        <template x-if="$store.artikel.articles.length === 0">
                            <li class="splide__slide text-center text-gray-500 p-4">Belum ada artikel tersedia.</li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href={{ route('articles.all') }}
                    class="inline-block border border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-white font-medium py-2 px-6 rounded-md transition duration-300">Lihat
                    Semua Artikel</a>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Pertanyaan yang Sering Diajukan</h2>
            <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: null }" x-init>
                <template x-for="(faq, index) in $store.faqStore.faqs" :key="faq.id_faq">
                    <div class="bg-amber-500 text-white rounded-lg shadow-md overflow-hidden">
                        <button @click="active = active === index ? null : index"
                            class="w-full px-6 py-4 text-left font-bold text-white flex justify-between items-center"
                            :class="{ 'bg-amber-600': active === index }">
                            <span x-text="faq.judul"></span>
                            <svg class="w-5 h-5 transform transition-transform duration-300"
                                :class="{ 'rotate-180': active === index }" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <hr class="w-11/12 m-auto">
                        <div x-show="active === index" x-transition class="px-6 py-4 text-black dark:text-white">
                            <p x-text="faq.deskripsi"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>


    <section class="py-16 md:py-24 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Download</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto" x-data>
                <template x-for="file in $store.downloads.files" :key="file.id">
                    <a :href="file.file_url" target="_blank"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                        </div>
                    </a>
                </template>

                <template x-if="$store.downloads.files.length === 0">
                    <p class="col-span-2 text-center text-gray-400 dark:text-gray-500">Belum ada file yang tersedia.</p>
                </template>
            </div>
        </div>
    </section>

    <a href="https://wa.me/6281234567890" target="_blank"
        class="fixed bottom-20 left-10 z-50 flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-orange-600 transition-all">
        <span class="text-sm md:text-base">Hubungi Kami</span>
        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="WhatsApp" class="w-6 h-6" />
    </a>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('artikel', {
                articles: [],
            });
        });

        fetch("https://6264-180-245-74-56.ngrok-free.app/api/articles")
            .then(res => res.json())
            .then(result => {
                if (result.status === "success") {
                    Alpine.store('artikel').articles = result.data.slice(0, 6);

                    setTimeout(() => {
                        new Splide('#artikel-splide', {
                            type: 'loop',
                            perPage: 3,
                            gap: '1rem',
                            breakpoints: {
                                1024: {
                                    perPage: 2
                                },
                                640: {
                                    perPage: 1
                                },
                            },
                        }).mount();
                    }, 300);
                }
            })
            .catch(err => console.error("Gagal memuat data artikel:", err));
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('beranda', {
                visi: '',
                misi: '',
            });

        });

        fetch("https://kpri.fasilkomapp.com/api/service-types/1")
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data && Array.isArray(result.data.layanan)) {
                    result.data.layanan.forEach(item => {
                        let html = item.deskripsi;

                        html = html.replace(/<ol>/,
                            '<ol class="list-decimal pl-3 space-y-2 text-justify">');

                        if (item.judul.toLowerCase() === 'visi') {
                            Alpine.store('beranda').visi = html;
                        } else if (item.judul.toLowerCase() === 'misi') {
                            Alpine.store('beranda').misi = html;
                        }
                    });
                }
            })
            .catch(err => console.error("Gagal mengambil data profil:", err));
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('faqStore', {
                faqs: [],
            });
        });

        fetch("https://kpri.fasilkomapp.com/api/faqs")
            .then(res => res.json())
            .then(result => {
                if (result.status === "success") {
                    Alpine.store('faqStore').faqs = result.data;
                }
            })
            .catch(err => console.error("Gagal memuat data FAQ:", err));
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('downloads', {
                files: []
            });
        });

        fetch("https://kpri.fasilkomapp.com/api/downloads")
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    Alpine.store('downloads').files = result.data;
                }
            })
            .catch(err => console.error("Gagal memuat data downloads:", err));
    </script>
@endpush
