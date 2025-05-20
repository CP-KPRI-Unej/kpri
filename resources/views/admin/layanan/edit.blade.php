@extends('admin.layouts.app')

@section('title', 'Edit Layanan - ' . $jenisLayanan->nama_layanan)

@section('content')
<div class="p-4">
    <div class="mb-4 bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-orange-500 text-white flex justify-between items-center">
            <h5 class="text-lg font-semibold">{{ $jenisLayanan->nama_layanan }}</h5>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            @foreach($jenisLayanan->layanans as $tab)
                <a href="{{ route('admin.layanan.edit', [$jenisLayanan->id_jenis_layanan, $tab->id_layanan]) }}" 
                   class="px-4 py-3 text-sm font-medium {{ $tab->id_layanan == $layanan->id_layanan ? 'bg-white dark:bg-gray-800 border-b-2 border-orange-500 text-orange-500' : 'text-gray-600 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-500' }}">
                    {{ $tab->judul_layanan }}
                </a>
            @endforeach
        </div>
        
        <div class="p-4 bg-white dark:bg-gray-800">
            <form action="{{ route('admin.layanan.update', [$jenisLayanan->id_jenis_layanan, $layanan->id_layanan]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="deskripsi_layanan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi {{ $layanan->judul_layanan }} <span class="text-red-500">*</span></label>
                    <textarea class="form-control w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('deskripsi_layanan') border-red-500 @enderror" 
                              id="deskripsi_layanan" 
                              name="deskripsi_layanan" 
                              rows="10" 
                              required>{{ old('deskripsi_layanan', $layanan->deskripsi_layanan) }}</textarea>
                    @error('deskripsi_layanan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex space-x-2 mt-6">
                    <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-md">
                        <i class="bi bi-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- WYSIWYG editor for rich text editing -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#deskripsi_layanan'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush 