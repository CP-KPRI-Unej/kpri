@extends('admin.layouts.app')

@section('title', 'Tambah Layanan Baru')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Layanan Baru - {{ $jenisLayanan->nama_layanan }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.layanan.store', $jenisLayanan->id_jenis_layanan) }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="judul_layanan" class="form-label">Judul Layanan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul_layanan') is-invalid @enderror" id="judul_layanan" name="judul_layanan" value="{{ old('judul_layanan') }}" required maxlength="30">
                    @error('judul_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Maksimal 30 karakter.</small>
                </div>
                
                <div class="form-group mb-3">
                    <label for="deskripsi_layanan" class="form-label">Deskripsi Layanan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi_layanan') is-invalid @enderror" id="deskripsi_layanan" name="deskripsi_layanan" rows="10" required>{{ old('deskripsi_layanan') }}</textarea>
                    @error('deskripsi_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Layanan
                    </button>
                    <a href="{{ route('admin.layanan.index', $jenisLayanan->id_jenis_layanan) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- You can add a WYSIWYG editor like CKEditor here if needed -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#deskripsi_layanan'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection 