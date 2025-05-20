@extends('admin.layouts.app')

@section('title', 'Tambah FAQ')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Tambah FAQ Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tambahkan pertanyaan dan jawaban yang sering ditanyakan</p>
        </div>
        <div>
            <a href="{{ route('admin.faq.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form action="{{ route('admin.faq.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pertanyaan</label>
                <input type="text" name="judul" id="judul" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full p-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan pertanyaan" value="{{ old('judul') }}" required>
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jawaban</label>
                <textarea name="deskripsi" id="deskripsi" rows="6" class="border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full p-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan jawaban dari pertanyaan tersebut" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                    Simpan FAQ
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Add editor for description if needed
    // Example: CKEDITOR.replace('deskripsi');
</script>
@endpush

@endsection 