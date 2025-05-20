@extends('admin.layouts.app')

@section('title', 'Struktur Kepengurusan')

@section('styles')
<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
    }
    .jabatan-heading {
        background-color: #f8f9fc;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #4e73df;
    }
</style>
@endsection

@section('content')
<div class="w-full px-6 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Struktur Kepengurusan</h1>
        <a href="{{ route('admin.struktur.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md flex items-center gap-2 text-sm hover:bg-blue-700 transition duration-300">
            <i class="bi bi-plus-lg"></i> Pengurus Baru
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Struktur Kepengurusan KPRI UNEJ</h2>
        </div>

        <!-- Positions section -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Ketua -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Ketua</h3>
                    @if(!$strukturByJabatan->has('Ketua') || $strukturByJabatan['Ketua']->isEmpty())
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Ketua" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                    @endif
                </div>
                
                @if($strukturByJabatan->has('Ketua') && $strukturByJabatan['Ketua']->isNotEmpty())
                    @foreach($strukturByJabatan['Ketua'] as $ketua)
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $ketua->nama_pengurus }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.struktur.edit', $ketua->id_pengurus) }}" 
                               class="text-gray-500 hover:text-blue-500 transition duration-300">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.struktur.destroy', $ketua->id_pengurus) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500 transition duration-300">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                @endif
            </div>

            <!-- Sekretaris -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Sekretaris</h3>
                    @if(!$strukturByJabatan->has('Sekretaris') || $strukturByJabatan['Sekretaris']->isEmpty())
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Sekretaris" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                    @endif
                </div>
                
                @if($strukturByJabatan->has('Sekretaris') && $strukturByJabatan['Sekretaris']->isNotEmpty())
                    @foreach($strukturByJabatan['Sekretaris'] as $sekretaris)
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $sekretaris->nama_pengurus }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.struktur.edit', $sekretaris->id_pengurus) }}" 
                               class="text-gray-500 hover:text-blue-500 transition duration-300">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.struktur.destroy', $sekretaris->id_pengurus) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500 transition duration-300">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                @endif
            </div>

            <!-- Bendahara -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Bendahara</h3>
                    @if(!$strukturByJabatan->has('Bendahara') || $strukturByJabatan['Bendahara']->isEmpty())
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Bendahara" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                    @endif
                </div>
                
                @if($strukturByJabatan->has('Bendahara') && $strukturByJabatan['Bendahara']->isNotEmpty())
                    @foreach($strukturByJabatan['Bendahara'] as $bendahara)
                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $bendahara->nama_pengurus }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.struktur.edit', $bendahara->id_pengurus) }}" 
                               class="text-gray-500 hover:text-blue-500 transition duration-300">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.struktur.destroy', $bendahara->id_pengurus) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500 transition duration-300">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                @endif
            </div>

            <!-- Anggota -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Anggota</h3>
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Anggota" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                </div>
                
                @if($strukturByJabatan->has('Anggota') && $strukturByJabatan['Anggota']->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($strukturByJabatan['Anggota'] as $anggota)
                        <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $anggota->nama_pengurus }}</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.struktur.edit', $anggota->id_pengurus) }}" 
                                   class="text-gray-500 hover:text-blue-500 transition duration-300">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.struktur.destroy', $anggota->id_pengurus) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition duration-300">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                @endif
            </div>

            <!-- Pengawas -->
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-800 dark:text-gray-200">Pengawas</h3>
                    <a href="{{ route('admin.struktur.create') }}?jabatan=Pengawas" 
                       class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </a>
                </div>
                
                @if($strukturByJabatan->has('Pengawas') && $strukturByJabatan['Pengawas']->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($strukturByJabatan['Pengawas'] as $pengawas)
                        <div class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $pengawas->nama_pengurus }}</span>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.struktur.edit', $pengawas->id_pengurus) }}" 
                                   class="text-gray-500 hover:text-blue-500 transition duration-300">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.struktur.destroy', $pengawas->id_pengurus) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition duration-300">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center justify-center py-4 px-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-gray-400 dark:text-gray-500 text-sm italic">Belum ada data</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 