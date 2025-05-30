@extends('comprof.layouts.app')

@section('title', 'Profil')

@section('content')
    <section
        class="flex flex-col justify-center pt-3 pb-3 text-center h-[500px] px-4 relative w-full bg-[url('../../public/images/hero-profile.png')] bg-cover bg-center"
        x-data>
        <h1 class="text-4xl font-bold text-orange-500 mb-2">Visi</h1>
        <p class="text-sm text-black dark:text-white" x-text="$store.profile.visi">
            Memuat...
        </p>

        <h1 class="text-4xl mt-5 font-bold text-orange-500 mb-2">Misi</h1>
        <p class="text-sm text-black dark:text-white" x-text="$store.profile.misi">
            Memuat...
        </p>
    </section>

    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-12">Galeri Foto</h2>

            <div id="splide" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ([['img' => 'info.png'], ['img' => 'info.png'], ['img' => 'info.png'], ['img' => 'info.png'], ['img' => 'info.png'], ['img' => 'info.png'], ['img' => 'info.png']] as $article)
                            <li class="splide__slide">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden w-full h-80">
                                    <img src="{{ asset('images/' . $article['img']) }}" alt="Artikel"
                                        class="w-full h-80 object-cover">
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative" x-data>
        <div class="w-3/4 m-auto flex flex-col gap-4">
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-12">SEJARAH SINGKAT</h2>

            <div class="flex justify-center gap-16 py-8 flex-col md:flex-row">
                <img src="{{ asset('images/profile-kiri.png') }}" alt="Profile Kiri"
                    class="w-96 h-96 object-cover rounded-s-3xl rounded-e-3xl" />
                <img src="{{ asset('images/profile-kanan.png') }}" alt="Profile Kanan"
                    class="w-96 h-96 object-cover rounded-s-3xl rounded-e-3xl" />
            </div>

            <p class="text-sm text-justify text-black dark:text-white" x-text="$store.profile.sejarah">
                Memuat sejarah...
            </p>
        </div>
    </section>

    <section class="py-14 px-4 relative" x-data>
        <div
            class="border border-orange-300 rounded-xl p-8 w-full max-w-4xl mx-auto bg-white dark:text-white dark:bg-gray-900 relative">
            <h2 class="text-2xl md:text-3xl font-bold text-orange-500 text-center mb-10">
                STRUKTUR ORGANISASI
            </h2>

            <template x-for="ketua in $store.profile.struktur.Ketua" :key="ketua.id_pengurus">
                <div class="text-center mb-8 flex justify-center">
                    <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs">
                        <h3 class="text-orange-500 font-semibold">KETUA</h3>
                        <p x-text="ketua.nama_pengurus"></p>
                    </div>
                </div>
            </template>

            <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-8">
                <template x-for="sekretaris in $store.profile.struktur.Sekretaris" :key="sekretaris.id_pengurus">
                    <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                        <h3 class="text-orange-500 font-semibold">SEKRETARIS</h3>
                        <p x-text="sekretaris.nama_pengurus"></p>
                    </div>
                </template>

                <template x-for="bendahara in $store.profile.struktur.Bendahara" :key="bendahara.id_pengurus">
                    <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                        <h3 class="text-orange-500 font-semibold">BENDAHARA</h3>
                        <p x-text="bendahara.nama_pengurus"></p>
                    </div>
                </template>
            </div>

            <div class="border border-orange-300 mx-auto w-full max-w-lg rounded-lg p-4 mb-8 text-center">
                <h3 class="text-orange-500 font-semibold">ANGGOTA</h3>
                <template x-for="anggota in $store.profile.struktur.Anggota" :key="anggota.id_pengurus">
                    <p x-text="anggota.nama_pengurus"></p>
                </template>
            </div>
        </div>
    </section>


    <a href="https://wa.me/6281234567890" target="_blank"
        class="fixed bottom-20 left-10 z-50 flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-orange-600 transition-all">
        <span class="text-sm md:text-base">Hubungi Kami</span>
        <img src="{{ asset('images/whatsapp-icon.png') }}" alt="WhatsApp" class="w-6 h-6" />
    </a>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('profile', {
                visi: '',
                misi: '',
                sejarah: '',
                struktur: {
                    Ketua: [],
                    Sekretaris: [],
                    Bendahara: [],
                    Anggota: []
                }
            });

        });

        fetch("https://kpri.fasilkomapp.com/api/service-types/2")
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data && Array.isArray(result.data.layanan)) {
                    result.data.layanan.forEach(item => {
                        if (item.judul.toLowerCase() === 'visi') {
                            Alpine.store('profile').visi = item.deskripsi;
                        } else if (item.judul.toLowerCase() === 'misi') {
                            Alpine.store('profile').misi = item.deskripsi;
                        } else if (item.judul.toLowerCase().includes('sejarah')) {
                            Alpine.store('profile').sejarah = item.deskripsi;
                        }
                    });
                }
            })
            .catch(err => console.error("Gagal mengambil data profil:", err));

        fetch("https://kpri.fasilkomapp.com/api/struktur")
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data) {
                    Alpine.store('profile').struktur = result.data;
                }
            })
            .catch(err => console.error("Gagal mengambil data struktur:", err));
    </script>
@endpush
