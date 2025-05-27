@extends('comprof.layouts.app')

@section('title', 'Jasa Umum dan Rental')

@section('content')
<div id="profile" class="min-h-screen font-sans flex flex-col justify-center flex-wrap">

    <section class="flex flex-col w-full justify-center pt-14 pb-3 text-center px-4 relative">
        <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white p-8 pb-4 rounded-lg">
            <h1 class="text-4xl font-bold text-orange-500 mb-2">JASA UMUM DAN PPOB</h1>
            <div class="flex flex-col md:flex-row gap-8">
                <img src="{{ asset('images/unit-jasa.png') }}" alt="Cooperative Officials" class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                <ol class="list-decimal text-sm text-justify text-black mt-5 space-y-1">
                    <li>Jasa Pembayaran PPOB (Listrik, PDAM, Telepon, BPJS, Pulsa, dll)</li>
                    <li>Jasa Layanan Tiket Kereta Api</li>
                    <li>Jasa Sewa Aula, Sewa Tempat Outdoor, dan Cafe</li>
                    <li>Jasa Pengurusan STNK</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="flex flex-col justify-center pt-3 pb-3 text-center px-4 relative">
        <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white p-8 rounded-lg">
            <img src="{{ asset('images/unit-jasa-2.png') }}" alt="Cooperative Officials" class="w-full mb-4 object-cover rounded-s-3xl rounded-e-3xl m-auto" />
            <h1 class="text-4xl font-bold text-orange-500 mb-2">JASA RENTAL KENDARAAN DAN PUJASERA</h1>
            <p class="text-sm text-justify text-black">
                Koperasi KPRI UNEJ juga menyediakan layanan sewa atau rental kendaraan untuk umum dan anggota (khusus anggota akan mendapatkan harga sewa khusus).
                Armada yang tersedia saat ini adalah 2 unit Toyota Hi-Ace (termasuk sopir).
                Selain layanan rental kendaraan, Koperasi KPRI UNEJ juga mempunyai tempat/lapak Pujasera yang disewakan untuk umum dan anggota.
                Untuk info sewa dan rental,
                <span class="font-bold">Hubungi: Sdr. Agus Praptomo, 0852-3682-7345</span>
            </p>
        </div>
    </section>

</div>
@endsection
