@extends('admin.layouts.app')

@section('title', 'Edit Halaman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Halaman: {{ $jenisLayanan->nama_layanan }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.halaman.update', $jenisLayanan->id_jenis_layanan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="nama_layanan" class="form-label">Nama Halaman <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_layanan') is-invalid @enderror" id="nama_layanan" name="nama_layanan" value="{{ old('nama_layanan', $jenisLayanan->nama_layanan) }}" required maxlength="30">
                    @error('nama_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Maksimal 30 karakter.</small>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Perbarui Halaman
                    </button>
                    <a href="{{ route('admin.halaman.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 