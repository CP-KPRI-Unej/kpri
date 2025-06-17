@extends('comprof.layouts.app')

@section('title', 'Unit Toko')

@section('content')
    <div id="profile" class="min-h-screen font-sans mb-14 justify-center flex flex-wrap">

        <section class="flex flex-col justify-center pt-12 pb-3 text-center px-4 relative w-full" x-data>
            <img src="{{ asset('images/unit-toko.png') }}" alt="Cooperative Officials"
                class="w-full md:w-3/4 mb-4 object-cover p-0 md:p-8 rounded-s-3xl rounded-e-3xl m-auto" />
            <div class="w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">PRODUK</h1>
                <div class="text-sm text-justify dark:text-white text-black space-y-2" x-html="$store.unitToko.produk"></div>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('toko.index') }}"
                    class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full transition duration-300 shadow-md">
                    Lihat Semua Produk
                </a>
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
            Alpine.store('unitToko', {
                produk: '',
            });

        });

        fetch("https://kpri.fasilkomapp.com/api/service-types/6")
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data && Array.isArray(result.data.layanan)) {
                    result.data.layanan.forEach(item => {
                        let html = item.deskripsi;

                        html = html.replace(/<ol>/,
                            '<ol class="list-decimal pl-6 space-y-2 text-justify">');

                        if (item.judul.toLowerCase() === 'produk') {
                            Alpine.store('unitToko').produk = html;
                        }
                    });
                }
            })
            .catch(err => console.error("Gagal mengambil data profil:", err));
    </script>
@endpush
