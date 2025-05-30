@extends('comprof.layouts.app')

@section('title', 'Simpanan & Pinjaman')

@section('content')
    <div id="profile" class="min-h-screen font-sans flex flex-col flex-wrap justify-center">
        <section class="flex flex-col justify-center pt-4 pb-3 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">SIMPANAN</h1>
                <div class="flex flex-col md:flex-row gap-8">
                    <img src="{{ asset('images/simpan-pinjam.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <div class="text-sm text-justify dark:text-white text-black space-y-2"
                        x-html="$store.unitSimpanPinjam.simpanan"></div>
                </div>
            </div>
        </section>

        <section class="flex flex-col w-full justify-center pt-7 pb-3 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">PINJAMAN</h1>
                <div class="flex flex-col md:flex-row gap-8">
                    <img src="{{ asset('images/simpan-pinjam-2.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <div class="text-sm text-justify dark:text-white text-black space-y-2"
                        x-html="$store.unitSimpanPinjam.pinjaman"></div>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-3 pb-10 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-5 rounded-lg">
                <img src="{{ asset('images/simpan-pinjam-3.png') }}" alt="Cooperative Officials"
                    class="w-3/4 mb-4 object-cover rounded-s-3xl rounded-e-3xl m-auto" />
                <h1 class="text-4xl font-bold text-orange-500 mb-2">DANA SOSIAL</h1>
                <div class="text-sm text-justify dark:text-white text-black space-y-2"
                    x-html="$store.unitSimpanPinjam.danaSosial"></div>
            </div>
        </section>
        <a href="https://wa.me/6281234567890" target="_blank"
            class="fixed bottom-20 left-10 z-50 flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-orange-600 transition-all">
            <span class="text-sm md:text-base">Hubungi Kami</span>
            <img src="{{ asset('images/whatsapp-icon.png') }}" alt="WhatsApp" class="w-6 h-6" />
        </a>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('unitSimpanPinjam', {
                simpanan: '',
                pinjaman: '',
                danaSosial: '',
            });

        });

        fetch("https://kpri.fasilkomapp.com/api/service-types/5")
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data && Array.isArray(result.data.layanan)) {
                    result.data.layanan.forEach(item => {
                        let html = item.deskripsi;

                        html = html.replace(/<ol>/,
                            '<ol class="list-decimal pl-6 space-y-2 text-justify">');

                        if (item.judul.toLowerCase() === 'pinjaman') {
                            Alpine.store('unitSimpanPinjam').pinjaman = html;
                        } else if (item.judul.toLowerCase() === 'simpanan') {
                            Alpine.store('unitSimpanPinjam').simpanan = html;
                        } else if (item.judul.toLowerCase() === 'dana sosial') {
                            Alpine.store('unitSimpanPinjam').danaSosial = html;
                        }
                    });
                }
            })
            .catch(err => console.error("Gagal mengambil data profil:", err));
    </script>
@endpush
