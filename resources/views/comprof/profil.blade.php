@extends('comprof.layouts.app')

@section('title', 'Profil')

@section('content')
    <section
        class="flex flex-col justify-center pt-3 pb-3 text-center h-[500px] px-4 relative w-full bg-[url('../../public/images/hero-profile.png')] bg-cover bg-center"
        x-data>
        <h1 class="text-4xl font-bold text-orange-500 mb-2">Visi</h1>
        <p class="text-sm text-black dark:text-white" x-html="$store.profile.visi">
            Memuat...
        </p>

        <h1 class="text-4xl mt-5 font-bold text-orange-500 mb-2">Misi</h1>
        <div class="mx-auto">
            <p class="text-sm text-black dark:text-white" x-html="$store.profile.misi">
                Memuat...
            </p>
        </div>
    </section>

    <section class="py-16 bg-white dark:bg-gray-900" x-data="galeriFoto()" x-init="loadGaleri()">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-12">Galeri Foto</h2>

            <div id="splide" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <template x-for="item in galeri" :key="item.id">
                            <li class="splide__slide">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden w-full h-80">
                                    <img :src="item.gambar" alt="Galeri" class="w-full h-80 object-cover">
                                </div>
                            </li>
                        </template>
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

            <p class="text-sm text-justify text-black dark:text-white" x-html="$store.profile.sejarah">
                Memuat sejarah...
            </p>
        </div>
    </section>

    <section class="py-14 px-4 relative" x-data="strukturOrganisasi()">
        <div
            class="border border-orange-300 rounded-xl p-8 w-full max-w-4xl mx-auto bg-white dark:text-white dark:bg-gray-900 relative">
            <h2 class="text-2xl md:text-3xl font-bold text-orange-500 text-center mb-10">STRUKTUR ORGANISASI</h2>

            <div class="mb-6 text-center">
                <label class="block mb-2 font-semibold text-sm text-gray-700 dark:text-white">Pilih Periode:</label>
                <select class="border border-orange-300 rounded-lg px-4 py-2 text-sm" x-model="selectedPeriode"
                    @change="loadStruktur()">
                    <template x-for="periode in periodes" :key="periode.id_periode">
                        <option :value="periode.id_periode" x-text="periode.nama_periode"></option>
                    </template>
                </select>
            </div>

            <!-- Ketua -->
            <template x-if="struktur.Ketua.length">
                <div class="text-center mb-8 flex justify-center">
                    <template x-for="ketua in struktur.Ketua" :key="ketua.id_pengurus">
                        <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs">
                            <h3 class="text-orange-500 font-semibold">KETUA</h3>
                            <p x-text="ketua.nama_pengurus"></p>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Sekretaris & Bendahara -->
            <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-8">
                <template x-for="sekretaris in struktur.Sekretaris" :key="sekretaris.id_pengurus">
                    <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                        <h3 class="text-orange-500 font-semibold">SEKRETARIS</h3>
                        <p x-text="sekretaris.nama_pengurus"></p>
                    </div>
                </template>

                <template x-for="bendahara in struktur.Bendahara" :key="bendahara.id_pengurus">
                    <div class="border border-orange-300 rounded-lg p-4 w-full max-w-xs text-center">
                        <h3 class="text-orange-500 font-semibold">BENDAHARA</h3>
                        <p x-text="bendahara.nama_pengurus"></p>
                    </div>
                </template>
            </div>

            <!-- Anggota -->
            <div class="border border-orange-300 mx-auto w-full max-w-lg rounded-lg p-4 mb-8 text-center">
                <h3 class="text-orange-500 font-semibold">ANGGOTA</h3>
                <template x-for="anggota in struktur.Anggota" :key="anggota.id_pengurus">
                    <p x-text="anggota.nama_pengurus"></p>
                </template>
            </div>

            <!-- Optional: Pengawas -->
            <div class="border border-orange-300 mx-auto w-full max-w-lg rounded-lg p-4 mb-8 text-center"
                x-show="struktur.Pengawas && struktur.Pengawas.length">
                <h3 class="text-orange-500 font-semibold">PENGAWAS</h3>
                <template x-for="pengawas in struktur.Pengawas" :key="pengawas.id_pengurus">
                    <p x-text="pengawas.nama_pengurus"></p>
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
        function galeriFoto() {
            return {
                galeri: [],
                loadGaleri() {
                    const baseUrl = window.location.origin;

                    fetch(`${baseUrl}/api/gallery`)
                        .then(res => res.json())
                        .then(result => {
                            if (result.success && Array.isArray(result.data)) {
                                this.galeri = result.data.map(item => ({
                                    ...item,
                                    gambar: item.gambar.startsWith('http') ? item.gambar :
                                        `${baseUrl}${item.gambar}`
                                }));

                                this.$nextTick(() => {
                                    new Splide('#splide', {
                                        type: 'loop',
                                        perPage: 3,
                                        gap: '1rem',
                                        breakpoints: {
                                            1024: {
                                                perPage: 2
                                            },
                                            640: {
                                                perPage: 1
                                            }
                                        }
                                    }).mount();
                                });
                            }
                        })
                        .catch(err => console.error("Gagal mengambil galeri:", err));
                }
            }
        }
    </script>

    <script>
        function strukturOrganisasi() {
            return {
                periodes: [],
                selectedPeriode: null,
                struktur: {
                    Ketua: [],
                    Sekretaris: [],
                    Bendahara: [],
                    Anggota: [],
                    Pengawas: []
                },
                init() {
                    const baseUrl = window.location.origin;

                    fetch(`${baseUrl}/api/struktur-periode`)
                        .then(res => res.json())
                        .then(result => {
                            if (result.success && result.data.length > 0) {
                                this.periodes = result.data;
                                this.selectedPeriode = result.data[0].id_periode;
                                this.loadStruktur();
                            }
                        });
                },
                loadStruktur() {
                    const baseUrl = window.location.origin;
                    fetch(`${baseUrl}/api/struktur?id_periode=${this.selectedPeriode}`)
                        .then(res => res.json())
                        .then(result => {
                            if (result.success && result.data) {
                                this.struktur = result.data;
                            }
                        })
                        .catch(err => console.error("Gagal mengambil struktur:", err));
                }
            }
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('profile', {
                visi: '',
                misi: '',
                sejarah: '',
            });
        });

        const baseUrl = window.location.origin;

        fetch(`${baseUrl}/api/service-types/2`)
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data && Array.isArray(result.data.layanan)) {
                    result.data.layanan.forEach(item => {
                        let html = item.deskripsi;

                        html = html.replace(/<ol>/,
                            '<ol class="list-decimal pl-6 space-y-2 text-justify">');

                        const title = item.judul.toLowerCase();

                        if (title === 'visi') {
                            Alpine.store('profile').visi = html;
                        } else if (title === 'misi') {
                            Alpine.store('profile').misi = html;
                        } else if (title.includes('sejarah')) {
                            Alpine.store('profile').sejarah = html;
                        }
                    });
                }
            })
            .catch(err => console.error("Gagal mengambil data profil:", err));
    </script>
@endpush
