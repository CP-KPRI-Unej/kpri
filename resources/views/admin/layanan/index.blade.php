@extends('admin.layouts.app')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Layanan - {{ $jenisLayanan->nama_layanan }}</h5>
            <div>
                <a href="{{ route('admin.halaman.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="layanan-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul Layanan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenisLayanan->layanans as $layanan)
                            <tr>
                                <td>{{ $layanan->id_layanan }}</td>
                                <td>{{ $layanan->judul_layanan }}</td>
                                <td>
                                    {{ Str::limit($layanan->deskripsi_layanan, 100) }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.layanan.edit', [$jenisLayanan->id_jenis_layanan, $layanan->id_layanan]) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada layanan yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#layanan-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection 