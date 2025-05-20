@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Tambah Kategori Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat kategori produk baru untuk digunakan di toko</p>
        </div>
        <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Form Kategori Produk</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-3 py-2 border {{ $errors->has('kategori') ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600' }} rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" 
                        id="kategori" name="kategori" value="{{ old('kategori') }}" required maxlength="30" autofocus
                        placeholder="Masukkan nama kategori">
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 30 karakter.</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-md text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 