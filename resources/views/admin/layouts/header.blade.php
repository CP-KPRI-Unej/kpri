<div class="fixed top-4 left-4 z-50" x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
    <!-- Floating hamburger menu button -->
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="p-3 rounded-full shadow-lg bg-orange-500 dark:bg-orange-600 text-white hover:bg-orange-600 dark:hover:bg-orange-700 transition-all duration-200 transform hover:scale-105 lg:hidden">
        <i class="bi bi-list text-xl"></i>
    </button>
</div>

