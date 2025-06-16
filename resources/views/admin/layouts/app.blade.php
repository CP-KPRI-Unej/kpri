<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - KPRI Admin</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @yield('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="flex h-screen bg-gray-100 dark:bg-gray-900" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
