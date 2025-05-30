@extends('comprof.layouts.app')

@section('title', 'Layanan')

@section('content')
    <div id="profile" class="min-h-screen font-sans flex flex-col flex-wrap justify-center">
        <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold  text-orange-500 mb-2">LAYANAN ANGGOTA</h1>
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('images/layanan-atas.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-96 h-auto object-cover rounded-s-3xl rounded-e-3xl" />
                    <ol class="list-decimal text-sm text-justify dark:text-white text-black space-y-2">
                        <li class="font-bold">
                            Layanan Toko
                            <span class="font-normal">
                                , Layanan Toko Koperasi KPRI UNEJ berada di Jl. Kalimantan 27 Jember dan Jl.
                                Sumatra 101 A Jember , buka pada : jam 08.00 – 21.00 ( untuk hari Senin – Sabtu ) khusus
                                hari Minggu Buka jam 09.00 – 15.00 wib. Unit Toko Koperasi KPRI UNEJ juga melayani
                                pembayaran kredit, wajib belanja atau voucer ( khusus anggota ) , juga melayani pesan antar
                                .
                            </span>
                        </li>
                        <li class="font-bold">
                            Layanan Simpan Pinjam
                            <span class="font-normal">
                                , Untuk Layanan Simpan Pinjam bertempat di Kantor Koperasi KPRI UNEJ Jl. Sumatra 101 A
                                Jember
                                , jam layanan : Jam 08.00 – 13.30 ( untuk hari Senin – Jum’at) , untuk hari sabtu layanan
                                hanya setengah hari , jam 08.00 – 10.30 , dan untuk Hari Minggu dan tanggal Merah layanan
                                Simpan Pinjam Tutup.
                            </span>
                        </li>
                        <li class="font-bold">
                            Layanan Pendaftaran Anggota Baru
                            <span class="font-normal">
                                , untuk layanan Pendaftaran Anggota Baru, form pendaftaran bisa di download di web :
                                www.kpriunej.com , untuk syarat dan ketentuan sudah tertera di formulir pendaftaran.
                                Pelayanan Anggota baru hanya bisa di lakukan pada jam kerja aktiv : jam 08.00 – 13.30 (senin
                                – sampai Jum’at)
                            </span>
                        </li>
                        <li class="font-bold">
                            Layanan Unit Jasa dan Rental
                            <span class="font-normal">
                                , Untuk unit jasa pembayaran PPOB dan rekening lainnya anggota bisa membayarkan di loket
                                Koperasi KPRI UNEJ baik secara langsung maupun lewat potong gaji bulanan. sedang untuk unit
                                sewa kendaraan dan ruko bisa langsung datang ke Kantor Koperasi KPRI UNEJ Jl. Sumatra.
                            </span>
                        </li>
                        <li class="font-bold">
                            Layanan Umum Lainnya
                            <span class="font-normal">
                                , meliputi : Layanan Keluhan anggota, Layanan Dana Sosial dan Tali asih . Anggota Koperasi
                                KPRI UNEJ bisa menghubungi langsung perwakilan yang sudah ditunjuk di tiap masing masing
                                unit kerja.
                            </span>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-14 pb-3 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">LAYANAN UMUM</h1>
                <div class="flex flex-col md:flex-row gap-6 md:ml-8">
                    <img src="{{ asset('images/layanan-tengah.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <ol class="list-decimal text-sm text-justify dark:text-white text-black space-y-1">
                        <li>Pembayaran Listrik</li>
                        <li>Pembayaran Tagihan TELKOM (Telepon, Speedy, dll)</li>
                        <li>Pembayaran Tagihan Telkomsel (Paket HALO)</li>
                        <li>Pembayaran Tagihan PDAM</li>
                        <li>Penjualan Voucher Listrik TOKEN</li>
                        <li>Pembayaran Tagihan INDOVISION</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-14 pb-3 mb-10 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white dark:bg-gray-900 p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">LAYANAN PERWAKILAN</h1>
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="{{ asset('images/layanan-bawah.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <p class="text-sm text-justify dark:text-white text-black">
                        Koperasi Pegawai Republik Indonesia Universitas Jember (KP-RI UNEJ) didirikan pada 2 Agustus 1979
                        dengan nama awal Koperasi Pegawai Negeri Universitas Jember (KPN-UNEJ). Tujuan utama pendiriannya
                        adalah untuk membantu masalah keuangan serta meningkatkan kesejahteraan tenaga dosen dan tenaga
                        administrasi di lingkungan Universitas Jember.
                    </p>
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
