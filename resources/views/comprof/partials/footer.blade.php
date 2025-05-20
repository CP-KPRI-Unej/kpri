<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4">
                <div class="flex items-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="KPRI UNEJ Logo" class="h-16 w-16 mr-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">KPRI UNIVERSITAS JEMBER</h4>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-amber-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300">Jl. Sumatra 101 A, Sumbersari, Jember, Jawa
                                Timur</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-phone-alt text-amber-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300">(0331) 339933</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-envelope text-amber-500 mr-3"></i>
                            <a href="mailto:kpriunej@gmail.com"
                                class="text-gray-700 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400">kpriunej@gmail.com</a>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-building text-amber-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300">Badan Hukum 4388/BH/II '80</span>
                        </div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-calendar-alt text-amber-500 mr-3"></i>
                            <span class="text-gray-700 dark:text-gray-300">12 Februari 1980</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <div class="text-center">
            <p class="text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }} KPRI Universitas Jember. All Rights
                Reserved.</p>
        </div>
    </div>
</footer>
