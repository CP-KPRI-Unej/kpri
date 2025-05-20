@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="space-y-6">
    <!-- Change Password Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary px-6 py-4 text-white">
            <h2 class="text-xl font-semibold">Ubah Password</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.settings.update-password') }}" method="POST">
                @csrf
                
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Password Saat Ini
                    </label>
                    <input type="password" id="current_password" name="current_password" 
                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 @error('current_password') border-red-500 @enderror" 
                        required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Password Baru
                    </label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 @error('password') border-red-500 @enderror" 
                        required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" 
                        required>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Theme Settings Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary px-6 py-4 text-white">
            <h2 class="text-xl font-semibold">Tema Aplikasi</h2>
        </div>
        <div class="p-6">
            <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Mode Gelap</span>
                    <div class="relative">
                        <input type="checkbox" x-model="darkMode" @change="toggleDarkMode()" class="hidden">
                        <div class="w-12 h-6 bg-gray-300 dark:bg-gray-600 rounded-full transition-colors duration-300" :class="{ 'bg-primary': darkMode }"></div>
                        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transform transition-transform duration-300" :class="{ 'translate-x-6': darkMode }"></div>
                    </div>
                </label>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Pilih tema tampilan yang sesuai dengan preferensi Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDarkMode() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        const newMode = !isDarkMode;
        
        localStorage.setItem('darkMode', newMode);
        
        if (newMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Send theme preference to the server
        fetch('{{ route("admin.settings.save-theme") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: newMode ? 'dark' : 'light' })
        });
    }
    
    // Initialize theme based on user's preference
    window.addEventListener('DOMContentLoaded', () => {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    });
</script>
@endsection 