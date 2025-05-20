@extends('admin.layouts.app')

@section('title', 'Edit Anggota Pengurus')

@section('content')
<div class="container px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Anggota Pengurus</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form action="{{ route('admin.struktur.update', $pengurus->id_pengurus) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="id_jabatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jabatan</label>
                <select name="id_jabatan" id="id_jabatan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatan as $jab)
                        <option value="{{ $jab->id_jabatan }}" {{ $pengurus->id_jabatan == $jab->id_jabatan ? 'selected' : '' }}>
                            {{ $jab->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                @error('id_jabatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nama_pengurus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pengurus</label>
                <input type="text" name="nama_pengurus" id="nama_pengurus" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('nama_pengurus', $pengurus->nama_pengurus) }}">
                @error('nama_pengurus')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.struktur.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-300">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 