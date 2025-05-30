<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPRI Universitas Jember - @yield('title', 'Beranda')</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">


    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="font-poppins text-gray-800 antialiased bg-white dark:bg-gray-900 dark:text-gray-200">
    @include('comprof.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('comprof.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            window.toggleDarkMode = function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Splide('#produk-splide', {
                type: 'loop',
                perPage: 3,
                gap: '1rem',
                pagination: false,
                arrows: true,
                breakpoints: {
                    768: {
                        perPage: 1,
                    },
                    1024: {
                        perPage: 2,
                    },
                },
            }).mount();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Splide('#artikel-splide', {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                gap: '1rem',
                breakpoints: {
                    1024: {
                        perPage: 2,
                    },
                    640: {
                        perPage: 1,
                    },
                },
                autoplay: true,
                interval: 5000,
            }).mount();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Splide('#splide', {
                type: 'loop',
                perPage: 3,
                perMove: 1,
                gap: '1rem',
                breakpoints: {
                    640: {
                        perPage: 1,
                    },
                    768: {
                        perPage: 2,
                    },
                },
                autoplay: true,
            }).mount();
        });
    </script>
    @stack('scripts')
</body>

</html>
