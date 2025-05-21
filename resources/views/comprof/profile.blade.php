@extends('comprof.layouts.app')

@section('title', 'Profil')

@section('content')
    <section
        class="flex flex-col justify-center pt-3 pb-3 text-center h-[500px] px-4 relative w-full bg-[url('/images/hero-profile.png')] bg-cover bg-center">
        <h1 class="text-4xl font-bold text-orange-500 mb-2">Visi</h1>
        <p class="text-sm text-black">
            “Menjadi Koperasi Terbaik Dalam Mensejahterakan Anggota dan Bermitra
            Dengan Stakeholders”
        </p>
        <h1 class="text-4xl mt-5 font-bold text-orange-500 mb-2">Misi</h1>
        <ol class="list-decimal text-sm text-black mx-auto md:pl-5 pl-10 text-left">
            <li>Menyediakan Pelayanan Prima bagi anggota.</li>
            <li>Mewujudkan Sumber Daya Manusia koperasi yang professional.</li>
            <li>Menyediakan teknologi informasi yang handal.</li>
            <li>Membangun jaringan usaha dengan pihak yang berkepentingan.</li>
            <li>Meningkatkan social kepada anggota dan masyarakat sekitar.</li>
            <li>Bersinergi dengan perguruan tinggi.</li>
        </ol>
    </section>

    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Artikel Terbaru</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([['img' => 'article-1.jpg', 'title' => 'PELATIHAN PENGELOLA KKPRI UNEJ DAN BERKELAS'], ['img' => 'article-2.jpg', 'title' => 'PENINGKATAN KAPASITAS PENGELOLA KKPRI UNTUK KOPERASI MANDIRI'], ['img' => 'article-3.jpg', 'title' => 'PENINGKATAN KAPASITAS PENGELOLA KOPERASI YANG MANDIRI']] as $article)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition duration-300 hover:scale-105">
                        <img src="{{ asset('images/' . $article['img']) }}" alt="Artikel" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h4 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">{{ $article['title'] }}</h4>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Pelatihan dan peningkatan kapasitas Pengelola
                                KKPRI UNEJ merupakan agenda rutin koperasi, bertujuan untuk memperkuat profesionalisme
                                pengelolaan koperasi.</p>
                            <a href="#"
                                class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-md transition duration-300">Baca
                                Selengkapnya</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="#"
                    class="inline-block border border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-white font-medium py-2 px-6 rounded-md transition duration-300">Lihat
                    Semua Artikel</a>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">SEJARAH SINGKAT</h2>

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

        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Pertanyaan yang Sering Diajukan</h2>

            <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: 2 }">
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                    <button @click="active = active === 0 ? null : 0"
                        class="w-full px-6 py-4 text-left font-bold text-gray-800 dark:text-white flex justify-between items-center"
                        :class="{ 'bg-amber-500 text-white': active === 0 }">
                        <span>Apakah KPRI UNEJ hanya bergerak di bidang simpan pinjam?</span>
                        <svg class="w-5 h-5 transform transition-transform duration-300"
                            :class="{ 'rotate-180': active === 0 }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="active === 0" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        Tidak, KPRI UNEJ tidak hanya bergerak di bidang simpan pinjam. KPRI UNEJ juga memiliki unit usaha
                        lain seperti toko kebutuhan harian, unit jasa, dan berbagai layanan lainnya untuk memenuhi kebutuhan
                        anggota.
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full px-6 py-4 text-left font-bold text-gray-800 dark:text-white flex justify-between items-center"
                        :class="{ 'bg-amber-500 text-white': active === 1 }">
                        <span>Apa yang dimaksud dengan KPRI UNEJ?</span>
                        <svg class="w-5 h-5 transform transition-transform duration-300"
                            :class="{ 'rotate-180': active === 1 }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="active === 1" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        KPRI UNEJ adalah singkatan dari Koperasi Pegawai Republik Indonesia Universitas Jember. Ini adalah
                        koperasi yang didirikan untuk melayani kebutuhan finansial dan kesejahteraan para pegawai di
                        lingkungan Universitas Jember.
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full px-6 py-4 text-left font-bold text-gray-800 dark:text-white flex justify-between items-center"
                        :class="{ 'bg-amber-500 text-white': active === 2 }">
                        <span>Bagaimana cara bergabung dengan KPRI UNEJ?</span>
                        <svg class="w-5 h-5 transform transition-transform duration-300"
                            :class="{ 'rotate-180': active === 2 }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="active === 2" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        Calon anggota dapat mendaftar melalui pengurus atau sekretariat KPRI UNEJ dengan memenuhi syarat
                        administrasi dan menyetujui ketentuan yang berlaku dalam keanggotaan koperasi.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-amber-500 mb-12">Download</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                <div class="space-y-4">
                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pengajuan Kredit Uang</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pengajuan Kredit Barang</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pendaftaran Anggota Baru</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Tabel Angsuran Pinjaman Uang 350 Juta</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Tabel Angsuran Pinjaman Barang 350 Juta</span>
                        </div>
                    </a>
                </div>

                <div class="space-y-4">
                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Tabel Angsuran Pinjaman 300 Juta</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pengajuan Dana Sosial Rawat Inap</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pengunduran Diri Anggota</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Belanja Bulanan</span>
                        </div>
                    </a>

                    <a href="#"
                        class="block bg-gray-50 dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg p-4 transition duration-300">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-amber-500 text-2xl mr-4"></i>
                            <span class="text-gray-700 dark:text-gray-300">Form Pinjaman Khusus</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
