@extends('admin.layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Pengaturan Akun</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi dan keamanan akun Anda</p>
        </div>
    </div>

    <!-- Alerts -->
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="success-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-success')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
        <span class="block sm:inline" id="error-message"></span>
        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="hideAlert('alert-error')">
            <span class="sr-only">Close</span>
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px" id="settingsTabs" role="tablist">
            <li class="mr-2" role="presentation">
                <button class="inline-block py-3 px-4 text-sm font-medium text-center text-orange-600 rounded-t-lg border-b-2 border-orange-600 active dark:text-orange-500 dark:border-orange-500" 
                    id="profile-tab" 
                    data-tab="profile-content" 
                    type="button" 
                    role="tab" 
                    aria-controls="profile" 
                    aria-selected="true">
                    <i class="bi bi-person mr-1"></i> Profil
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button class="inline-block py-3 px-4 text-sm font-medium text-center text-gray-500 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                    id="security-tab" 
                    data-tab="security-content" 
                    type="button" 
                    role="tab" 
                    aria-controls="security" 
                    aria-selected="false">
                    <i class="bi bi-shield-lock mr-1"></i> Keamanan
                </button>
            </li>
            <li role="presentation">
                <button class="inline-block py-3 px-4 text-sm font-medium text-center text-gray-500 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300" 
                    id="appearance-tab" 
                    data-tab="appearance-content" 
                    type="button" 
                    role="tab" 
                    aria-controls="appearance" 
                    aria-selected="false">
                    <i class="bi bi-palette mr-1"></i> Tampilan
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div id="settingsTabContent">
        <!-- Profile Tab Content -->
        <div id="profile-content" class="block" role="tabpanel" aria-labelledby="profile-tab">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Informasi Profil</h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui: <span id="last-updated">-</span></span>
                </div>
                <div class="p-6" id="profile-section">
                    <div class="animate-pulse" id="profile-loading">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-6"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-6"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
                    </div>
                    
                    <form id="profile-form" class="hidden space-y-6">
                        <!-- Nama User -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="nama_user" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Lengkap
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nama yang akan ditampilkan di dashboard.</p>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" id="nama_user" name="nama_user" 
                                    class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="nama_user_error"></p>
                            </div>
                        </div>
                        
                        <!-- Username -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Username
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Digunakan untuk login ke sistem.</p>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" id="username" name="username" 
                                    class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="username_error"></p>
                            </div>
                        </div>
                        
                        <!-- Role (Display Only) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Role
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Level akses pengguna.</p>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" id="role" name="role" readonly
                                    class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 cursor-not-allowed">
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="resetProfile" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                                    Reset
                                </button>
                                <button type="button" id="saveProfile" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                                    <span class="flex items-center">
                                        <i class="bi bi-save mr-1"></i> Simpan Perubahan
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Tab Content -->
        <div id="security-content" class="hidden" role="tabpanel" aria-labelledby="security-tab">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Ubah Password</h2>
                </div>
                <div class="p-6">
                    <form id="password-form" class="space-y-6">
                        <!-- Current Password -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password Saat Ini
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan password saat ini untuk verifikasi.</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" 
                                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required>
                                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 dark:text-gray-400" data-target="current_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="current_password_error"></p>
                            </div>
                        </div>
                        
                        <!-- New Password -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password Baru
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal 8 karakter.</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <input type="password" id="new_password" name="new_password" 
                                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required>
                                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 dark:text-gray-400" data-target="new_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2" id="password-strength">
                                    <div class="w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div id="password-strength-bar" class="h-full bg-gray-500" style="width: 0%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="password-strength-text">Kekuatan password: -</p>
                                </div>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="new_password_error"></p>
                            </div>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Konfirmasi Password
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ulangi password baru.</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                        class="w-full px-3 py-2 border rounded-md border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-900 text-gray-900 dark:text-gray-100" required>
                                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 dark:text-gray-400" data-target="new_password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="new_password_confirmation_error"></p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                            <div class="flex justify-end">
                                <button type="button" id="savePassword" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                                    <span class="flex items-center">
                                        <i class="bi bi-shield-lock mr-1"></i> Perbarui Password
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Appearance Tab Content -->
        <div id="appearance-content" class="hidden" role="tabpanel" aria-labelledby="appearance-tab">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Pengaturan Tampilan</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dark Mode Toggle -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Mode Gelap</h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Pilih tema tampilan yang sesuai dengan preferensi Anda.</p>
                                </div>
                                <div class="relative">
                                    <input type="checkbox" id="darkModeToggle" class="hidden">
                                    <label for="darkModeToggle" class="cursor-pointer">
                                        <div id="darkModeTrack" class="w-14 h-7 bg-gray-300 dark:bg-gray-600 rounded-full transition-colors duration-300"></div>
                                        <div id="darkModeThumb" class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transform transition-transform duration-300"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tab functionality
        initTabs();
        
        // Set up event handlers for password visibility toggles
        initPasswordToggles();
        
        // Set up password strength meter
        initPasswordStrengthMeter();
        
        // Initialize dark mode
        initDarkMode();
        
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            fetchUserProfile();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Add event listeners
        document.getElementById('saveProfile').addEventListener('click', updateProfile);
        document.getElementById('savePassword').addEventListener('click', updatePassword);
        document.getElementById('resetProfile').addEventListener('click', resetProfileForm);
        
        // Help button
        document.getElementById('btn-bantuan').addEventListener('click', showHelpModal);
        
        // Get client info
        getClientInfo();
    });
    
    /**
     * Initialize tabs functionality
     */
    function initTabs() {
        const tabs = document.querySelectorAll('[data-tab]');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Hide all tab contents
                document.querySelectorAll('#settingsTabContent > div').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                const targetId = tab.getAttribute('data-tab');
                document.getElementById(targetId).classList.remove('hidden');
                
                // Update active state
                tabs.forEach(t => {
                    t.classList.remove('text-orange-600', 'border-orange-600', 'dark:text-orange-500', 'dark:border-orange-500');
                    t.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                });
                
                tab.classList.remove('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                tab.classList.add('text-orange-600', 'border-orange-600', 'dark:text-orange-500', 'dark:border-orange-500');
            });
        });
    }
    
    /**
     * Initialize password toggle functionality
     */
    function initPasswordToggles() {
        const toggles = document.querySelectorAll('.password-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const targetId = toggle.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = toggle.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    }
    
    /**
     * Initialize password strength meter
     */
    function initPasswordStrengthMeter() {
        const passwordInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');
        
        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            const strength = calculatePasswordStrength(password);
            
            // Update strength bar
            strengthBar.style.width = strength.percentage + '%';
            strengthBar.className = 'h-full ' + strength.colorClass;
            
            // Update strength text
            strengthText.textContent = 'Kekuatan password: ' + strength.label;
        });
    }
    
    /**
     * Calculate password strength
     */
    function calculatePasswordStrength(password) {
        if (!password) {
            return { percentage: 0, label: '-', colorClass: 'bg-gray-500' };
        }
        
        let strength = 0;
        let checks = 0;
        
        // Length check
        if (password.length >= 8) {
            strength += 25;
            checks++;
        }
        
        // Uppercase check
        if (/[A-Z]/.test(password)) {
            strength += 25;
            checks++;
        }
        
        // Lowercase check
        if (/[a-z]/.test(password)) {
            strength += 25;
            checks++;
        }
        
        // Numbers check
        if (/[0-9]/.test(password)) {
            strength += 25;
            checks++;
        }
        
        // Special chars check
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 25;
            checks++;
        }
        
        // Cap at 100%
        strength = Math.min(100, strength);
        
        // Determine color and label
        let colorClass, label;
        
        if (strength < 25) {
            colorClass = 'bg-red-500';
            label = 'Sangat Lemah';
        } else if (strength < 50) {
            colorClass = 'bg-orange-500';
            label = 'Lemah';
        } else if (strength < 75) {
            colorClass = 'bg-yellow-500';
            label = 'Sedang';
        } else {
            colorClass = 'bg-green-500';
            label = 'Kuat';
        }
        
        return {
            percentage: strength,
            label: label,
            colorClass: colorClass
        };
    }
    
    /**
     * Check authentication status
     */
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    fetchUserProfile();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    /**
     * Fetch user profile data
     */
    function fetchUserProfile() {
        // Determine API path based on role
        let apiPath = isShopAdmin() ? '/api/shop/settings/profile' : '/api/admin/settings/profile';
        
        axios.get(apiPath)
            .then(response => {
                if (response.data.status === 'success') {
                    populateProfileForm(response.data.data);
                    document.getElementById('profile-loading').classList.add('hidden');
                    document.getElementById('profile-form').classList.remove('hidden');
                    
                    // Format date to display
                    const now = new Date();
                    document.getElementById('last-updated').textContent = now.toLocaleDateString('id-ID', {
                        day: 'numeric', 
                        month: 'short', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } else {
                    showAlert('error', 'Failed to load profile data');
                }
            })
            .catch(error => {
                console.error('Error fetching profile:', error);
                showAlert('error', 'Error loading profile: ' + (error.response?.data?.message || error.message));
                
                // Check if unauthorized and redirect to login
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    /**
     * Populate profile form with data
     */
    function populateProfileForm(data) {
        // Cache original data for reset functionality
        window.originalProfileData = data;
        
        // Populate form fields
        document.getElementById('nama_user').value = data.nama;
        document.getElementById('username').value = data.username;
        document.getElementById('role').value = data.role;
    }
    
    /**
     * Reset profile form to original values
     */
    function resetProfileForm() {
        if (window.originalProfileData) {
            populateProfileForm(window.originalProfileData);
            showAlert('success', 'Form berhasil direset ke data awal');
        }
    }
    
    /**
     * Update profile information
     */
    function updateProfile() {
        // Clear previous errors
        clearErrors();
        
        // Get form data
        const nama_user = document.getElementById('nama_user').value;
        const username = document.getElementById('username').value;
        
        // Validate form data
        let isValid = true;
        
        if (!nama_user.trim()) {
            document.getElementById('nama_user_error').textContent = 'Nama lengkap tidak boleh kosong';
            document.getElementById('nama_user_error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!username.trim()) {
            document.getElementById('username_error').textContent = 'Username tidak boleh kosong';
            document.getElementById('username_error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!isValid) {
            showAlert('error', 'Harap periksa kembali formulir yang Anda isi');
            return;
        }
        
        // Determine API path based on role
        let apiPath = isShopAdmin() ? '/api/shop/settings/profile' : '/api/admin/settings/profile';
        
        // Show loading state
        const saveBtn = document.getElementById('saveProfile');
        const originalBtnContent = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="flex items-center"><i class="bi bi-hourglass-split animate-pulse mr-1"></i> Menyimpan...</span>';
        saveBtn.disabled = true;
        
        axios.put(apiPath, {
            nama_user: nama_user,
            username: username
        })
        .then(response => {
            if (response.data.status === 'success') {
                showAlert('success', 'Profil berhasil diperbarui');
                populateProfileForm(response.data.data);
                
                // Update last updated timestamp
                const now = new Date();
                document.getElementById('last-updated').textContent = now.toLocaleDateString('id-ID', {
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } else {
                showAlert('error', 'Gagal memperbarui profil');
            }
            saveBtn.innerHTML = originalBtnContent;
            saveBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error updating profile:', error);
            saveBtn.innerHTML = originalBtnContent;
            saveBtn.disabled = false;
            
            // Check if unauthorized and redirect to login
            if (error.response && error.response.status === 401) {
                window.location.href = '/admin/login';
                return;
            }
            
            // Handle validation errors
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        const errorElement = document.getElementById(`${field}_error`);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    }
                }
                showAlert('error', 'Harap periksa kembali formulir yang Anda isi');
            } else {
                showAlert('error', 'Error updating profile: ' + (error.response?.data?.message || error.message));
            }
        });
    }
    
    /**
     * Update password
     */
    function updatePassword() {
        // Clear previous errors
        clearErrors();
        
        // Get form data
        const current_password = document.getElementById('current_password').value;
        const new_password = document.getElementById('new_password').value;
        const new_password_confirmation = document.getElementById('new_password_confirmation').value;
        
        // Validate form data
        let isValid = true;
        
        if (!current_password) {
            document.getElementById('current_password_error').textContent = 'Password saat ini tidak boleh kosong';
            document.getElementById('current_password_error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!new_password) {
            document.getElementById('new_password_error').textContent = 'Password baru tidak boleh kosong';
            document.getElementById('new_password_error').classList.remove('hidden');
            isValid = false;
        } else if (new_password.length < 8) {
            document.getElementById('new_password_error').textContent = 'Password baru minimal 8 karakter';
            document.getElementById('new_password_error').classList.remove('hidden');
            isValid = false;
        }
        
        if (new_password !== new_password_confirmation) {
            document.getElementById('new_password_confirmation_error').textContent = 'Konfirmasi password tidak sama';
            document.getElementById('new_password_confirmation_error').classList.remove('hidden');
            isValid = false;
        }
        
        if (!isValid) {
            showAlert('error', 'Harap periksa kembali formulir yang Anda isi');
            return;
        }
        
        // Determine API path based on role
        let apiPath = isShopAdmin() ? '/api/shop/settings/password' : '/api/admin/settings/password';
        
        // Show loading state
        const saveBtn = document.getElementById('savePassword');
        const originalBtnContent = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="flex items-center"><i class="bi bi-hourglass-split animate-pulse mr-1"></i> Menyimpan...</span>';
        saveBtn.disabled = true;
        
        axios.put(apiPath, {
            current_password: current_password,
            new_password: new_password,
            new_password_confirmation: new_password_confirmation
        })
        .then(response => {
            if (response.data.status === 'success') {
                showAlert('success', 'Password berhasil diperbarui');
                document.getElementById('password-form').reset();
                document.getElementById('password-strength-bar').style.width = '0%';
                document.getElementById('password-strength-bar').className = 'h-full bg-gray-500';
                document.getElementById('password-strength-text').textContent = 'Kekuatan password: -';
            } else {
                showAlert('error', 'Gagal memperbarui password');
            }
            saveBtn.innerHTML = originalBtnContent;
            saveBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error updating password:', error);
            saveBtn.innerHTML = originalBtnContent;
            saveBtn.disabled = false;
            
            // Check if unauthorized and redirect to login
            if (error.response && error.response.status === 401) {
                window.location.href = '/admin/login';
                return;
            }
            
            // Handle validation errors
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                
                if (error.response.data.message === 'Current password is incorrect') {
                    document.getElementById('current_password_error').textContent = 'Password saat ini tidak sesuai';
                    document.getElementById('current_password_error').classList.remove('hidden');
                    showAlert('error', 'Password saat ini tidak sesuai');
                    return;
                }
                
                for (const field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        let errorField = field;
                        if (field === 'new_password' || field === 'new_password_confirmation') {
                            errorField = field;
                        }
                        
                        const errorElement = document.getElementById(`${errorField}_error`);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    }
                }
                showAlert('error', 'Harap periksa kembali formulir yang Anda isi');
            } else {
                showAlert('error', 'Error updating password: ' + (error.response?.data?.message || error.message));
            }
        });
    }
    
    /**
     * Get client information
     */
    function getClientInfo() {
        // Set a default last login time (this would normally come from the server)
        const now = new Date();
        document.getElementById('last-login').textContent = now.toLocaleDateString('id-ID', {
            day: 'numeric', 
            month: 'short', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Get IP address (placeholder)
        document.getElementById('ip-address').textContent = '127.0.0.1';
    }
    
    /**
     * Show help modal
     */
    function showHelpModal() {
        showAlert('success', 'Bantuan pengaturan akun akan ditampilkan di sini');
    }
    
    /**
     * Helper function to check if user is shop admin based on URL
     */
    function isShopAdmin() {
        return window.location.href.includes('/admin/shop') || 
               window.location.pathname.startsWith('/admin/shop');
    }
    
    /**
     * Clear all error messages
     */
    function clearErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }
    
    /**
     * Show alert messages
     */
    function showAlert(type, message) {
        const alertElement = document.getElementById(`alert-${type}`);
        const messageElement = document.getElementById(`${type}-message`);
        
        messageElement.textContent = message;
        alertElement.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            hideAlert(`alert-${type}`);
        }, 5000);
    }
    
    /**
     * Hide alert messages
     */
    function hideAlert(elementId) {
        document.getElementById(elementId).classList.add('hidden');
    }
    
    /**
     * Initialize dark mode functionality
     */
    function initDarkMode() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeTrack = document.getElementById('darkModeTrack');
        const darkModeThumb = document.getElementById('darkModeThumb');
        
        // Set initial state based on localStorage or system preference
        const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                         (localStorage.getItem('darkMode') === null && 
                          window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        // Update UI based on current mode
        updateDarkModeUI(isDarkMode);
        
        // Apply dark mode to document if active
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        }
        
        // Add event listener for toggle
        darkModeToggle.addEventListener('change', function() {
            const isDarkMode = this.checked;
            toggleDarkMode(isDarkMode);
        });
    }
    
    /**
     * Update dark mode UI elements
     */
    function updateDarkModeUI(isDarkMode) {
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeTrack = document.getElementById('darkModeTrack');
        const darkModeThumb = document.getElementById('darkModeThumb');
        
        // Update checkbox state
        darkModeToggle.checked = isDarkMode;
        
        // Update track color
        if (isDarkMode) {
            darkModeTrack.classList.add('bg-orange-500');
            darkModeTrack.classList.remove('bg-gray-300');
            darkModeThumb.classList.add('translate-x-7');
        } else {
            darkModeTrack.classList.remove('bg-orange-500');
            darkModeTrack.classList.add('bg-gray-300');
            darkModeThumb.classList.remove('translate-x-7');
        }
    }
    
    /**
     * Toggle dark mode
     */
    function toggleDarkMode(forceDarkMode = null) {
        const isDarkMode = forceDarkMode !== null ? forceDarkMode : 
                          localStorage.getItem('darkMode') !== 'true';
        
        // Update localStorage
        localStorage.setItem('darkMode', isDarkMode ? 'true' : 'false');
        
        // Update UI
        updateDarkModeUI(isDarkMode);
        
        // Apply to document
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Show confirmation
        showAlert('success', isDarkMode ? 'Mode gelap diaktifkan' : 'Mode gelap dinonaktifkan');
    }
</script>
@endpush 
