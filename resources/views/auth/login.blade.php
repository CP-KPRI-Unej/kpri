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
<body class="h-full">
    <div class="absolute inset-0">
        <img src="{{ asset('images/bg_Login.png') }}" alt="Background" class="w-full h-full object-cover">
    </div>

    <!-- Home icon -->
    <div class="fixed top-5 left-5 z-50">
        <a href="/" class="block p-3">
            <img src="{{ asset('icon/Home.png') }}" alt="Home" class="w-7 h-7">
        </a>
    </div>
    
    <div class="flex min-h-screen flex-col justify-center items-center px-6 py-12 relative z-10">
        <div class="text-center mb-10">
            <h2 class="text-5xl font-bold text-orange-500">Admin</h2>
            <h3 class="text-5xl font-bold text-orange-500 mt-1">KPRI Universitas Jember</h3>
        </div>
        
        <div class="w-full max-w-md">
            <div id="error-container" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4">
                <ul class="list-disc pl-4" id="error-list"></ul>
            </div>
            
            <form id="loginForm" class="space-y-8">
                @csrf
                
                <div class="mb-6">
                    <label for="username" class="block text-lg font-bold text-gray-900 mb-2">Username</label>
                    <input id="username" name="username" type="text" required autocomplete="username" 
                        class="block w-full h-14 rounded-lg py-2 px-4 text-gray-900 shadow-sm sm:text-lg bg-gray-200 border border-gray-300">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-lg font-bold text-gray-900 mb-2">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" 
                        class="block w-full h-14 rounded-lg py-2 px-4 text-gray-900 shadow-sm sm:text-lg bg-gray-200 border border-gray-300">
                </div>
                
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-full bg-orange-500 hover:bg-orange-600 px-5 py-4 text-xl font-semibold text-white shadow-md transition-colors duration-200">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Clear previous errors
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
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        // Store token in localStorage
                        localStorage.setItem('access_token', data.access_token);
                        localStorage.setItem('user_role', data.role);
                        
                        // Redirect based on role
                        if (data.role === 'kpri admin') {
                            window.location.href = '/admin/dashboard';
                        } else if (data.role === 'admin shop') {
                            window.location.href = '/admin/shop-dashboard';
                        } else {
                            window.location.href = '/admin';
                        }
                    } else if (data.error) {
                        errorContainer.classList.remove('hidden');
                        const errorItem = document.createElement('li');
                        errorItem.textContent = data.error;
                        errorList.appendChild(errorItem);
                    } else if (data.errors) {
                        errorContainer.classList.remove('hidden');
                        Object.keys(data.errors).forEach(key => {
                            const errorItem = document.createElement('li');
                            errorItem.textContent = data.errors[key][0];
                            errorList.appendChild(errorItem);
                        });
                    }
                })
                .catch(error => {
                    errorContainer.classList.remove('hidden');
                    const errorItem = document.createElement('li');
                    errorItem.textContent = 'An error occurred while trying to login. Please try again later.';
                    errorList.appendChild(errorItem);
                    console.error('Login Error Details:', error);
                    
                    // Add more detailed debugging to console
                    fetch('/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            username: username,
                            password: password
                        })
                    })
                    .then(response => {
                        console.log('Auth Status:', response.status);
                        console.log('Auth Headers:', Object.fromEntries(response.headers.entries()));
                        return response.text();
                    })
                    .then(text => {
                        try {
                            const data = JSON.parse(text);
                            console.log('Auth Response Data:', data);
                        } catch (e) {
                            console.log('Auth Raw Response:', text);
                        }
                    })
                    .catch(e => console.error('Debug request failed:', e));
                });
            });
        });
    </script>
</body>
</html> 