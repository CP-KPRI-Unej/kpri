<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Admin KPRI</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="h-full bg-white">
    <script>
    // Check if the user is already logged in with a valid token
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        const role = localStorage.getItem('user_role');
        
        if (token) {
            // Verify token validity by making a request to the me endpoint
            fetch('/api/auth/me', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(res => {
                if (res.ok) {
                    // Token is valid, redirect to the appropriate dashboard
                    if (role === 'kpri admin') {
                        window.location.href = '/admin/dashboard';
                    } else if (role === 'admin shop') {
                        window.location.href = '/admin/shop-dashboard';
                    } else {
                        window.location.href = '/admin';
                    }
                } else {
                    // Token is invalid, clear it from localStorage
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('user_role');
                }
            })
            .catch(error => {
                console.error('Error verifying token:', error);
                localStorage.removeItem('access_token');
                localStorage.removeItem('user_role');
            });
        }
    });
    </script>

    <div class="flex h-screen">
        <!-- Left: Login form -->
        <div class="w-full md:w-1/2 flex flex-col justify-center px-10 lg:px-24">
            <div class="mb-10">
                <img src="{{ asset('images/fasilkom_logo.png') }}" alt="Fasilkom Logo" class="h-12 mb-6">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
                <p class="text-gray-700 text-base">Silakan masukkan data diri anda</p>
            </div>

            <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
                <ul class="list-disc pl-4" id="error-list"></ul>
            </div>

            <form id="loginForm" class="space-y-6 w-full max-w-md">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <input id="username" name="username" type="text" required autocomplete="username"
                        class="w-full h-12 px-4 rounded-md border border-gray-300 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-900">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="w-full h-12 px-4 rounded-md border border-gray-300 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-900">
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-md bg-orange-500 hover:bg-orange-600 text-white font-semibold text-lg transition duration-200 shadow-sm">
                    Masuk
                </button>
            </form>
        </div>

        <!-- Right: Background image -->
        <div class="hidden md:block md:w-1/2 h-full">
            <img src="{{ asset('images/login.png') }}" alt="Gedung Fasilkom" class="w-full h-full object-cover">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');

            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();

                errorContainer.classList.add('hidden');
                errorList.innerHTML = '';

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ username, password })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.access_token) {
                        localStorage.setItem('access_token', data.access_token);
                        localStorage.setItem('user_role', data.role);

                        if (data.role === 'kpri admin') {
                            window.location.href = '/admin/dashboard';
                        } else if (data.role === 'admin shop') {
                            window.location.href = '/admin/shop-dashboard';
                        } else {
                            window.location.href = '/admin';
                        }
                    } else {
                        errorContainer.classList.remove('hidden');
                        errorList.innerHTML = '';
                        
                        // Custom Indonesian error messages
                        if (data.error === 'Unauthorized') {
                            const item = document.createElement('li');
                            item.textContent = 'Username atau password tidak valid. Silakan coba lagi.';
                            errorList.appendChild(item);
                        } else if (data.errors) {
                            // Map validation errors to Indonesian
                            const errorMessages = {
                                'username.required': 'Username wajib diisi.',
                                'password.required': 'Password wajib diisi.',
                                'username.string': 'Username harus berupa teks.',
                                'password.string': 'Password harus berupa teks.',
                                'username.exists': 'Username tidak terdaftar dalam sistem.'
                            };
                            
                            Object.keys(data.errors).forEach(field => {
                                data.errors[field].forEach(error => {
                                    const item = document.createElement('li');
                                    // Use custom message if available, otherwise use the original error
                                    const key = `${field}.${error.split('.').pop()}`;
                                    item.textContent = errorMessages[key] || error;
                                    errorList.appendChild(item);
                                });
                            });
                        } else {
                            const item = document.createElement('li');
                            item.textContent = data.error || 'Terjadi kesalahan saat login. Silakan coba lagi.';
                            errorList.appendChild(item);
                        }
                    }
                })
                .catch(error => {
                    errorContainer.classList.remove('hidden');
                    errorList.innerHTML = '';
                    const item = document.createElement('li');
                    item.textContent = 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.';
                    errorList.appendChild(item);
                    console.error(error);
                });
            });
        });
    </script>
</body>
</html>
