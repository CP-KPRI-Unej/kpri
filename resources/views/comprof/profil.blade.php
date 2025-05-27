@extends('comprof.layouts.app')

@section('title', 'Profil')

@section('content')
    <section
        class="flex flex-col justify-center pt-3 pb-3 text-center h-[500px] px-4 relative w-full bg-[url('../../public/images/hero-profile.png')] bg-cover bg-center">
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
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-12">Artikel Terbaru</h2>
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
                    class="inline-block border border-amber-500 text-orange-500 hover:bg-amber-500 hover:text-white font-medium py-2 px-6 rounded-md transition duration-300">Lihat
                    Semua Artikel</a>
            </div>
        </div>
    </section>

    <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative">
        <div class="w-3/4 m-auto flex flex-col gap-4">
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-12">SEJARAH SINGKAT</h2>

            <div class="flex justify-center gap-16 py-8 flex-col md:flex-row">
                <img src="{{ asset('images/profile-kiri.png') }}" alt="Cooperative Officials"
                    class="w-96 h-96 object-cover rounded-s-3xl rounded-e-3xl" />
                <img src="{{ asset('images/profile-kanan.png') }}" alt="Cooperative Officials"
                    class="w-96 h-96 object-cover rounded-s-3xl rounded-e-3xl" />
            </div>
            <p class="text-sm text-justify text-black">
                Koperasi Pegawai Republik Indonesia Universitas Jember (KP-RI UNEJ)
                didirikan pada 2 Agustus 1979 dengan nama awal Koperasi Pegawai
                Negeri Universitas Jember (KPN-UNEJ). Tujuan utama pendiriannya
                adalah untuk membantu masalah keuangan serta meningkatkan
                kesejahteraan tenaga dosen dan tenaga administrasi di lingkungan
                Universitas Jember.
            </p>

            <p class="text-sm text-black text-justify">
                Seiring perkembangannya, keanggotaan koperasi tidak hanya terdiri
                dari Pegawai Negeri Sipil (PNS) tetapi juga non-PNS. Oleh karena
                itu, pada tahun 1993, namanya diubah menjadi Koperasi Pegawai
                Republik Indonesia Universitas Jember (KP-RI UNEJ) agar lebih
                mencerminkan anggotanya secara luas.
            </p>

            <p class="text-sm text-black text-justify">
                Hingga tahun 2013, KP-RI UNEJ menunjukkan perkembangan yang positif,
                ditandai dengan peningkatan partisipasi anggota, jumlah transaksi di
                toko koperasi, serta peningkatan jumlah pinjaman dan simpanan
                sukarela. Keberhasilan ini didukung oleh peningkatan kualitas
                layanan koperasi, sesuai dengan moto "Pelayanan Prima adalah Visi
                Kami, Kepuasan Anda adalah Kebahagiaan Kami."
            </p>

            <p class="text-sm text-black text-justify">
                Selain itu, KP-RI UNEJ juga berperan dalam program Pendidikan Sistem
                Ganda (PSG) dan magang, memberikan kesempatan bagi siswa dan
                mahasiswa di Kabupaten Jember untuk mendapatkan pengalaman dalam
                praktek penjualan, pergudangan, serta pembukuan akuntansi.
            </p>
        </div>
    </section>

    <section class="py-14 px-4 relative">
        <div class="border border-orange-300 rounded-xl p-8 w-full max-w-4xl mx-auto bg-white relative">
            <h2 class="text-2xl md:text-3xl font-bold text-orange-500 text-center mb-10">
                STRUKTUR ORGANISASI
            </h2>

            <!-- Ketua -->
            <div class="text-center mb-8 flex justify-center">
                <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs">
                    <h3 class="text-orange-500 font-semibold">KETUA</h3>
                    <p>Prof. Dr. Yuli Witono, S.TP.,MP.</p>
                </div>
            </div>

            <!-- Sekretaris & Bendahara -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-8">
                <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                    <h3 class="text-orange-500 font-semibold">SEKRETARIS</h3>
                    <p>Echwan Iriyanto, SH.,MH.</p>
                </div>
                <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                    <h3 class="text-orange-500 font-semibold">BENDAHARA</h3>
                    <p>Hadi Paramu, SE.,M.BA.,Ph.D.</p>
                </div>
            </div>

            <!-- Anggota -->
            <div class="border border-orange-300 mx-auto w-full max-w-lg rounded-lg p-4 mb-8 text-center">
                <h3 class="text-orange-500 font-semibold">ANGGOTA</h3>
                <p>
                    dr. Al Munawir, M.Kes.,Ph.D.<br>
                    Dr. Ir. Herlina, MP.,IPM.<br>
                    Dr. Slamet Hariyadi, S.Pd.,M.Si.<br>
                    Adehardra Boru Sibasopait, SS
                </p>
            </div>

            <!-- Pengawas -->
            <div class="border border-orange-300 mx-auto w-full max-w-lg rounded-lg p-4 text-center">
                <h3 class="text-orange-500 font-semibold">PENGAWAS</h3>
                <p>
                    Dr. Adenan, MM.<br>
                    Prof. Dr. Hadi Prayitno, M.Kes<br>
                    Nur Hisamuddin, SE.,M.SA.,Ak.,CA
                </p>
            </div>
        </div>

        <a href="https://wa.me/6281234567890" target="_blank"
            class="fixed bottom-20 left-10 z-50 flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-orange-600 transition-all">
            <span class="text-sm md:text-base">Hubungi Kami</span>
            <img src="{{ asset('images/whatsapp-icon.png') }}" alt="WhatsApp" class="w-6 h-6" />
        </a>
    </section>

@endsection
