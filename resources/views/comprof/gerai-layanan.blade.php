@extends('comprof.layouts.app')

@section('title', 'Layanan')

@section('content')
    <div id="profile" class="min-h-screen font-sans flex flex-col flex-wrap justify-center">
        <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold  text-orange-500 mb-2">LAYANAN ANGGOTA</h1>
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('images/layanan-atas.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-96 h-auto object-cover rounded-s-3xl rounded-e-3xl" />
                    <div class="text-sm text-justify dark:text-white text-black space-y-2"
                        x-html="$store.services.anggotaHtml"></div>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">LAYANAN UMUM</h1>
                <div class="flex flex-col md:flex-row gap-6 md:ml-8">
                    <img src="{{ asset('images/layanan-tengah.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <div class="text-sm text-justify dark:text-white text-black space-y-2"
                        x-html="$store.services.umumHtml"></div>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-14 pb-3 mb-10 text-center px-4 relative" x-data>
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">LAYANAN PERWAKILAN</h1>
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('images/layanan-bawah.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <div class="text-sm text-justify dark:text-white text-black space-y-2"
                        x-html="$store.services.perwakilanHtml"></div>
                </div>
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
            Alpine.store('services', {
                anggotaHtml: '',
                umumHtml: '',
                perwakilanHtml: '',
            });

            fetch("https://kpri.fasilkomapp.com/api/service-types/3")
                .then(res => res.json())
                .then(result => {
                    if (result.success && result.data && Array.isArray(result.data.layanan)) {
                        result.data.layanan.forEach(item => {
                            let html = item.deskripsi;

                            html = html.replace(/<ol>/,
                                '<ol class="list-decimal pl-6 space-y-2 text-justify">');

                            const title = item.judul.toLowerCase();
                            if (title.includes('anggota')) {
                                Alpine.store('services').anggotaHtml = html;
                            } else if (title.includes('umum')) {
                                Alpine.store('services').umumHtml = html;
                            } else if (title.includes('perwakilan')) {
                                Alpine.store('services').perwakilanHtml = html;
                            }
                        });
                    }
                })
                .catch(err => console.error("Gagal mengambil data layanan:", err));
        });
    </script>
@endpush
