@extends('admin.layouts.app')

@section('title', 'Shop Dashboard')

@section('content')

<div class="container px-4 mx-auto">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Shop Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Statistik produk dan toko KPRI</p>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="stats-container">
        <!-- Stat cards will be loaded here via JS -->
        <div class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-xl h-32"></div>
        <div class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-xl h-32"></div>
        <div class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-xl h-32"></div>
        <div class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded-xl h-32"></div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Statistik Penjualan Tahunan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jumlah penjualan per bulan pada tahun {{ date('Y') }}</p>
            </div>
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <select id="chartYear" class="bg-transparent border-0 text-gray-700 dark:text-gray-300 font-medium rounded focus:outline-none text-sm">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                </select>
            </div>
        </div>
        <div class="p-6">
            <canvas id="salesChart" height="250"></canvas>
        </div>
    </div>

    <!-- Latest Products Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Produk Terbaru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar produk toko KPRI</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto" id="products-table-container">
                <div class="animate-pulse">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-full mb-4"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-full mb-4"></div>
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-full mb-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }

        // Fetch dashboard statistics
        fetch('/api/shop/dashboard/stats', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    // Unauthorized, redirect to login
                    window.location.href = '/admin/login';
                    return null;
                }
                throw new Error('Failed to fetch dashboard stats');
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;
            
            const statsContainer = document.getElementById('stats-container');
            statsContainer.innerHTML = `
                <!-- Total Products -->
                <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div class="text-white">
                            <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Produk</p>
                            <div class="flex items-center">
                                <h3 class="text-3xl font-bold">${numberWithCommas(data.totalProducts)}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock -->
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="text-white">
                            <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Stok Habis</p>
                            <div class="flex items-center">
                                <h3 class="text-3xl font-bold">${numberWithCommas(data.outOfStock)}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-white">
                            <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Pendapatan</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold">Rp${numberWithCommas(data.totalRevenue)}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Promos -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="text-white">
                            <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Promo Aktif</p>
                            <div class="flex items-center">
                                <h3 class="text-3xl font-bold">${numberWithCommas(data.activePromos)}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });

        // Fetch products
        fetch('/api/shop/dashboard/products', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch products');
            }
            return response.json();
        })
        .then(products => {
            const productsTableContainer = document.getElementById('products-table-container');
            
            let tableHTML = `
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            `;
            
            if (products && products.length > 0) {
                products.forEach(product => {
                    tableHTML += `
                        <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">${product.id_produk}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${product.nama_produk}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${product.kategori}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp${numberWithCommas(product.harga_produk)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${product.stok_produk}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${product.stok_produk > 0 
                                    ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>' 
                                    : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Stok Habis</span>'
                                }
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableHTML += `
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td colspan="6" class="px-6 py-4 text-center">Belum ada data produk</td>
                    </tr>
                `;
            }
            
            tableHTML += `
                    </tbody>
                </table>
            `;
            
            productsTableContainer.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });

        // Fetch chart data
        fetch('/api/shop/dashboard/chart-data', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch chart data');
            }
            return response.json();
        })
        .then(chartData => {
            initChart(chartData);
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        });

        function initChart(chartData) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            
            // Format the data for Chart.js
            const data = [];
            for (let i = 1; i <= 12; i++) {
                data.push(chartData[i] || 0);
            }
            
            // Calculate the maximum value to set a good scale
            const maxSales = Math.max(...data);
            const suggestedMax = maxSales + Math.ceil(maxSales * 0.2); // Add 20% for better visualization
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Jumlah Penjualan',
                        data: data,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.6)', // emerald-500 with opacity
                        ],
                        borderColor: [
                            'rgba(5, 150, 105, 1)', // emerald-600
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            padding: 12,
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            },
                            bodySpacing: 6,
                            caretSize: 6,
                            cornerRadius: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: suggestedMax,
                            grid: {
                                display: true,
                                color: 'rgba(156, 163, 175, 0.1)' // gray-400 with low opacity
                            },
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    });
</script>
@endpush 