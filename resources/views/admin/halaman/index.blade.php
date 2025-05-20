@extends('admin.layouts.app')

@section('title', 'Manajemen Halaman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Halaman Website</h5>
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
                <table class="table table-striped table-hover" id="halaman-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Halaman</th>
                            <th>Jumlah Layanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenisLayanans as $jenisLayanan)
                            <tr>
                                <td>{{ $jenisLayanan->id_jenis_layanan }}</td>
                                <td>{{ $jenisLayanan->nama_layanan }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $jenisLayanan->layanans_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.layanan.index', $jenisLayanan->id_jenis_layanan) }}" class="btn btn-sm btn-info" title="Kelola Layanan">
                                            <i class="bi bi-list-ul"></i> Layanan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada halaman yang ditambahkan.</td>
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
        $('#halaman-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection 