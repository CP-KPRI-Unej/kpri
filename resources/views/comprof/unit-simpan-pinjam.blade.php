@extends('comprof.layouts.app')

@section('title', 'Simpanan & Pinjaman')

@section('content')
    <div id="profile" class="min-h-screen font-sans flex flex-col flex-wrap justify-center">

        <section class="flex flex-col justify-center pt-4 pb-3 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">SIMPANAN</h1>
                <div class="flex flex-col md:flex-row gap-8">
                    <img src="{{ asset('images/simpan-pinjam.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <ol class="list-decimal text-sm text-justify text-black space-y-2">
                        <li class="font-bold">
                            Simpanan Pokok
                            <span class="font-normal">
                                , simpanan yang dibayar satu kali selama menjadi anggota. Besarnya simpanan bergantung dari
                                hasil kesepakatan pengurus dan anggota koperasi. Simpanan hanya bisa diambil kembali ketika
                                keluar dari keanggotaan Koperasi. Untuk saat ini Simpanan Pokok untuk menjadi anggota KP-RI
                                Universitas Jember sebesar Rp. 100.000.
                            </span>
                        </li>
                        <li class="font-bold">
                            Simpanan Wajib
                            <span class="font-normal">
                                , Simpanan yang wajib dibayar sebulan sekali. Besarnya bergantung kesepakatan anggota dan
                                pengurus. Saat ini ditetapkan:
                                <ul class="list-inside list-disc">
                                    <li>Golongan I : Rp. 70.000</li>
                                    <li>Golongan II : Rp. 100.000</li>
                                    <li>Golongan III : Rp. 125.000</li>
                                    <li>Golongan IV : Rp. 200.000</li>
                                    <li>Honorer : Rp. 50.000</li>
                                </ul>
                                <br />
                                Layanan tersedia di Kantor Koperasi KPRI UNEJ Jl. Sumatra 101 A Jember, jam 08.00 – 13.30
                                (Senin–Jumat), Sabtu 08.00 – 10.30. Tutup saat Minggu/tanggal merah.
                            </span>
                        </li>
                        <li class="font-bold">
                            Simpanan Khusus
                            <span class="font-normal">
                                , simpanan yang besarnya tidak ditentukan, bergantung kemampuan anggota. Disetorkan setiap
                                saat dan hanya bisa diambil sesuai jangka waktu tertentu (1 tahun) dengan jasa bersaing.
                            </span>
                        </li>
                        <li class="font-bold">
                            Simpanan Sukarela
                            <span class="font-normal">
                                , simpanan sukarela yang hanya bisa diambil pada periode tertentu dengan ketentuan:
                                <ul class="list-inside list-disc">
                                    <li>Simpanan periode 6 Bulan</li>
                                    <li>Simpanan periode 12 Bulan (1 tahun)</li>
                                </ul>
                            </span>
                        </li>
                        <li class="font-bold">
                            Tabungan Hari Raya
                            <span class="font-normal">
                                , tabungan rutin bulanan untuk anggota, dipotongkan otomatis, dengan besaran tetap dan hanya
                                bisa diambil menjelang Hari Raya Idul Fitri.
                            </span>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="flex flex-col w-full justify-center pt-7 pb-3 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white p-8 rounded-lg">
                <h1 class="text-4xl font-bold text-orange-500 mb-2">PINJAMAN</h1>
                <div class="flex flex-col md:flex-row gap-8">
                    <img src="{{ asset('images/simpan-pinjam-2.png') }}" alt="Cooperative Officials"
                        class="w-full md:w-80 object-cover rounded-s-3xl rounded-e-3xl" />
                    <ol class="list-decimal text-sm text-justify text-black space-y-2">
                        <li class="font-bold">
                            Pinjaman Uang
                            <span class="font-normal">
                                : Layanan pinjaman berupa uang untuk anggota KP-RI UNEJ. Maksimal Rp. 350 Juta, angsuran
                                hingga 15 tahun (180 bulan). Berlaku untuk PNS dan HR. Jasa pinjaman 0,75% per bulan.
                            </span>
                        </li>
                        <li class="font-bold">
                            Pinjaman Barang
                            <span class="font-normal">
                                : Pinjaman dalam bentuk barang seperti motor, mobil, elektronik dll (bisa dibeli di
                                toko/dealer manapun). Maksimal pinjaman Rp. 350 Juta, maksimal angsuran 15 tahun, jasa
                                pinjaman 0,75% per bulan.
                            </span>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="flex flex-col justify-center pt-3 pb-3 text-center px-4 relative">
            <div class="w-full md:w-3/4 m-auto flex flex-col gap-4 bg-white p-5 rounded-lg">
                <img src="{{ asset('images/simpan-pinjam-3.png') }}" alt="Cooperative Officials"
                    class="w-3/4 mb-4 object-cover rounded-s-3xl rounded-e-3xl m-auto" />
                <h1 class="text-4xl font-bold text-orange-500 mb-2">DANA SOSIAL</h1>
                <p class="text-sm font-bold text-justify text-black">
                    KEPUTUSAN PENGURUS <br />
                    KOPERASI PEGAWAI REPUBLIK INDONESIA (KP-RI) UNIVERSITAS JEMBER <br />
                    NOMOR : 169/40.22/G/XI/2018 <br />
                    TENTANG <br />
                    DANA SOSIAL KP-RI UNIVERSITAS JEMBER <br />
                    KETUA KP-RI UNIVERSITAS JEMBER,
                </p>
                <p class="font-bold text-justify">
                    Menimbang
                    <span class="font-normal">: bahwa berdasarkan Keputusan Rapat Anggota Rencana Kerja (RARK) Tahun Buku
                        2019 dan peningkatan SHU, dana sosial perlu ditinjau ulang dan ditetapkan kembali oleh
                        pengurus.</span>
                </p>
                <p class="font-bold text-justify">
                    Mengingat
                    <span class="font-normal">: UU No. 25 Tahun 1992 tentang Perkoperasian; AD/ART KP-RI Universitas Jember;
                        Keputusan Pengurus No. 016/40.22/G/I/2016;</span>
                </p>
                <p class="font-bold text-left">Menetapkan :</p>
                <ol class="list-decimal text-justify pl-4">
                    <li>Penggunaan dana sosial untuk: uang duka, bantuan rawat inap, dan sumbangan sosial berdasarkan
                        keputusan pengurus.</li>
                    <li>Ketentuan pemberian:
                        <ul class="list-disc list-inside">
                            <li>Anggota wafat: Rp. 2.000.000</li>
                            <li>Keluarga anggota (tercatat): Rp. 1.000.000</li>
                            <li>Rawat inap anggota: maksimal Rp. 500.000</li>
                            <li>Rawat inap keluarga: maksimal Rp. 300.000</li>
                        </ul>
                    </li>
                    <li>Bantuan rawat inap diberikan 1 kali per tahun per keluarga.</li>
                    <li>Pengajuan diatur dalam surat edaran terpisah.</li>
                    <li>Keputusan lama yang bertentangan dinyatakan tidak berlaku.</li>
                    <li>Berlaku mulai 1 Januari 2019. Jika terdapat kekeliruan akan diperbaiki.</li>
                </ol>
            </div>
        </section>

    </div>
@endsection
