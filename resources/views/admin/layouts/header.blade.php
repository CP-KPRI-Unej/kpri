<div class="sticky top-0 z-10 bg-white dark:bg-gray-800 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Left side with hamburger menu -->
        <div class="flex items-center">
            <button 
                @click="sidebarOpen = !sidebarOpen"
                class="p-2 mr-2 rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 lg:hidden">
                <i class="bi bi-list text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">@yield('title', 'Admin Panel')</h1>
        </div>

        <!-- Right side with theme toggle and more -->
        <div class="flex items-center">
            <!-- Theme toggle -->
            <button
                id="theme-toggle"
                type="button"
                class="p-2 mr-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
                title="Toggle dark mode"
            >
                <i class="bi bi-sun text-lg dark:hidden"></i>
                <i class="bi bi-moon text-lg hidden dark:inline"></i>
            </button>
            
            <!-- Profile dropdown (if needed) -->
        </div>
    </div>
</div>

<script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggleBtn = document.getElementById('theme-toggle');
        
        themeToggleBtn.addEventListener('click', function() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            localStorage.setItem('darkMode', !isDarkMode);
            
            if (!isDarkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    });
</script> 