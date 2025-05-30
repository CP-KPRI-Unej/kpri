@extends('comprof.layouts.app')

@section('title', 'Unit Toko')

@section('content')
    <div id="profile" class="min-h-screen font-sans mb-14 justify-center flex flex-wrap">

        <section class="flex flex-col justify-center pt-12 pb-3 text-center px-4 relative w-full">
            <img src="{{ asset('images/unit-toko.png') }}" alt="Cooperative Officials"
                class="w-full md:w-3/4 mb-4 object-cover p-0 md:p-8 rounded-s-3xl rounded-e-3xl m-auto" />
            <div class="w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">

                <h1 class="text-4xl font-bold text-orange-500 mb-2">PRODUK</h1>
                <p class="text-sm text-justify text-black dark:text-white">
                    Produk-produk yang disediakan oleh KP-RI Universitas Jember sangatlah beragam dan memliki harga yang
                    bersaing,
                    jenis produk yang dijual antara lain :
                </p>

                <p class="font-bold text-left">LANTAI 1 KP-RI UNEJ</p>
                <ol class="list-decimal list-inside text-left">
                    <li>Kebutuhan pokok (Konsumsi): Beras, gula, minyak, mie instan, susu bayi/dewasa, dll.</li>
                    <li>Kebutuhan pokok (non-konsumsi): sabun mandi, sabun cuci, kosmetik, parfume, dll.</li>
                    <li>Snack, softdrink dan makanan ringan</li>
                </ol>

                <p class="font-bold text-left mt-4">LANTAI 2 KP-RI UNEJ</p>
                <ol class="list-decimal list-inside text-left">
                    <li>Peralatan Elektronik: kipas angin, TV, Tape, Magic com, dll</li>
                    <li>Alat-alat listrik: sekering, kabel, adaptor, dll</li>
                    <li>Peralatan rumah tangga: keranjang, alat makan, alat kebersihan, dll</li>
                    <li>Tekstil: pakaian, kerudung, sarung, tas, dll</li>
                </ol>

                <p class="font-bold text-left mt-4">PESANAN PRODUK KHUSUS</p>
                <p class="text-left">
                    Koperasi juga menyediakan produk yang tidak tersedia melalui rekanan seperti komputer, motor, rumah,
                    dll.
                </p>

                <h1 class="text-4xl font-bold mt-6 text-orange-500 mb-2">PROMO</h1>

                <div id="produk-splide" class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ([['img' => 'produk.png', 'text' => 'Gula Pasir Rose Brand', 'harga' => 'Rp 15.000'], ['img' => 'produk-1.png', 'text' => 'Chi Sparkling Water', 'harga' => 'Rp 15.000'], ['img' => 'produk-2.png', 'text' => 'Minyak Goreng Bimoli', 'harga' => 'Rp 15.000']] as $item)
                                <li class="splide__slide">
                                    <div class="border-2 border-orange-400 rounded-lg py-4 px-2 flex flex-col items-center">
                                        <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['text'] }}"
                                            class="w-full md:w-3/4 rounded shadow-md" />
                                        <h2 class="text-center font-bold text-green-800 dark:text-white mt-2">{{ $item['text'] }}</h2>
                                        <h2 class="text-center font-bold text-black dark:text-white">{{ $item['harga'] }}</h2>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
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
