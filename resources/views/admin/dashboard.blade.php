@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container px-4 mx-auto">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Statistik kunjungan website linktree KPRI</p>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" id="summary-stats">
        <!-- Total Visits - Loading State -->
        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="text-white">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Kunjungan</p>
                    <div class="flex items-center">
                        <h3 class="text-3xl font-bold" id="total-visitors">
                            <div class="animate-pulse h-8 w-24 bg-white bg-opacity-30 rounded"></div>
                        </h3>
                        <span class="flex items-center ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            Semua waktu
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Visits - Loading State -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-white">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Kunjungan Hari Ini</p>
                    <div class="flex items-center">
                        <h3 class="text-3xl font-bold" id="today-visitors">
                            <div class="animate-pulse h-8 w-16 bg-white bg-opacity-30 rounded"></div>
                        </h3>
                        <span class="flex items-center ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            Hari ini
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month's Visits - Loading State -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-6 flex items-center">
                <div class="rounded-full bg-white bg-opacity-30 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="text-white">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Kunjungan Bulan Ini</p>
                    <div class="flex items-center">
                        <h3 class="text-3xl font-bold" id="month-visitors">
                            <div class="animate-pulse h-8 w-20 bg-white bg-opacity-30 rounded"></div>
                        </h3>
                        <span class="flex items-center ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            Bulan ini
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Annual Visitor Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Statistik Pengunjung Tahunan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jumlah kunjungan per bulan pada tahun <span id="chart-year"></span></p>
            </div>
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <select id="chartYear" class="bg-transparent border-0 text-gray-700 dark:text-gray-300 font-medium rounded focus:outline-none text-sm">
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
        </div>
        <div class="p-6">
            <canvas id="visitorChart" height="250"></canvas>
        </div>
    </div>

    <!-- Latest Visitors Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Aktivitas Pengunjung Terbaru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">10 pengunjung terakhir yang mengakses linktree</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Halaman</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="recent-visitors-table" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr id="loading-row">
                            <td colspan="3" class="px-6 py-4 text-center">
                                <div class="animate-pulse flex justify-center">
                                    <div class="h-4 w-4 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                    <div class="h-4 w-4 bg-gray-300 dark:bg-gray-600 rounded-full mx-1"></div>
                                    <div class="h-4 w-4 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Most Visited Pages Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Halaman Paling Populer</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Halaman yang paling banyak dikunjungi</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Halaman</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody id="top-pages-table" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr id="loading-pages-row">
                            <td colspan="2" class="px-6 py-4 text-center">
                                <div class="animate-pulse flex justify-center">
                                    <div class="h-4 w-4 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                    <div class="h-4 w-4 bg-gray-300 dark:bg-gray-600 rounded-full mx-1"></div>
                                    <div class="h-4 w-4 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let visitorChart = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Set up axios defaults
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // Set JWT token from localStorage if available
        const token = localStorage.getItem('access_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            fetchDashboardData();
        } else {
            // If no token in localStorage, try to get it from the login process
            checkAuthentication();
        }
        
        // Add CSRF token to all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        
        // Set up year selector
        document.getElementById('chartYear').addEventListener('change', function() {
            fetchMonthlyChartData(this.value);
        });
        
        // Set current year as default
        const currentYear = new Date().getFullYear();
        document.getElementById('chartYear').value = currentYear;
        document.getElementById('chart-year').textContent = currentYear;
    });
    
    // Check authentication status
    function checkAuthentication() {
        axios.get('/api/auth/me')
            .then(response => {
                const token = response.data.access_token;
                if (token) {
                    localStorage.setItem('access_token', token);
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    fetchDashboardData();
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                if (error.response && error.response.status === 401) {
                    window.location.href = '/admin/login';
                }
            });
    }
    
    // Fetch all dashboard data
    function fetchDashboardData() {
        // Determine API path based on role
        let basePath = isShopAdmin() ? '/api/shop/dashboard/' : '/api/admin/dashboard/';
        
        // Fetch summary statistics
        fetchSummaryStats(basePath);
        
        // Fetch monthly chart data for current year
        const currentYear = new Date().getFullYear();
        fetchMonthlyChartData(currentYear, basePath);
        
        // Fetch recent visitors
        fetchRecentVisitors(basePath);
        
        // Fetch top pages
        fetchTopPages(basePath);
    }
    
    // Fetch summary statistics
    function fetchSummaryStats(basePath = '/api/admin/dashboard/') {
        axios.get(basePath + 'summary')
            .then(response => {
                if (response.data.status === 'success') {
                    const stats = response.data.data;
                    
                    // Update the DOM
                    document.getElementById('total-visitors').textContent = formatNumber(stats.totalVisitors);
                    document.getElementById('today-visitors').textContent = formatNumber(stats.todayVisitors);
                    document.getElementById('month-visitors').textContent = formatNumber(stats.monthVisitors);
                }
            })
            .catch(error => {
                console.error('Error fetching summary stats:', error);
                handleApiError(error);
            });
    }
    
    // Fetch monthly chart data
    function fetchMonthlyChartData(year, basePath = '/api/admin/dashboard/') {
        // Show loading indicator
        const chartContainer = document.getElementById('visitorChart').parentNode;
        chartContainer.innerHTML = `
            <div class="flex justify-center items-center h-64">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
            </div>
        `;
        
        axios.get(`${basePath}monthly-chart/${year}`)
            .then(response => {
                if (response.data.status === 'success') {
                    // Restore canvas
                    chartContainer.innerHTML = '<canvas id="visitorChart" height="250"></canvas>';
                    
                    const chartData = response.data.data.chartData;
                    console.log("API Response:", response.data);
                    console.log("Chart Data:", chartData);
                    
                    // Render chart with the new data
                    renderVisitorChart(chartData);
                    document.getElementById('chart-year').textContent = year;
                } else {
                    chartContainer.innerHTML = `
                        <div class="flex justify-center items-center h-64">
                            <p class="text-red-500">Failed to load chart data</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching monthly chart data:', error);
                chartContainer.innerHTML = `
                    <div class="flex justify-center items-center h-64">
                        <p class="text-red-500">Error: ${error.message || 'Failed to fetch data'}</p>
                    </div>
                `;
                handleApiError(error);
            });
        }
        
    // Render visitor chart
    function renderVisitorChart(chartData) {
        try {
            const canvas = document.getElementById('visitorChart');
            if (!canvas) {
                console.error('Canvas element not found!');
                return;
            }
            
            const ctx = canvas.getContext('2d');
            
            // Destroy existing chart if it exists
            if (visitorChart) {
                visitorChart.destroy();
            }
            
            // Prepare data
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            
            // Convert object to array ensuring proper order
            const dataArray = [];
            for (let i = 1; i <= 12; i++) {
                dataArray.push(chartData[i] || 0);
            }
            
            console.log("Data for chart (array format):", dataArray);
            
            // Calculate max for y-axis
            const maxValue = Math.max(...dataArray);
            const suggestedMax = maxValue > 0 ? Math.ceil(maxValue * 1.2) : 10;
            
            // Create chart
            visitorChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                        data: dataArray,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(37, 99, 235, 1)',
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
                                color: 'rgba(156, 163, 175, 0.1)'
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
        } catch (error) {
            console.error("Error rendering chart:", error);
            const chartContainer = document.getElementById('visitorChart').parentNode;
            chartContainer.innerHTML = `
                <div class="flex flex-col justify-center items-center h-64">
                    <p class="text-red-500">Error rendering chart</p>
                    <p class="text-sm text-gray-500">${error.message}</p>
                </div>
            `;
        }
    }
    
    // Fetch recent visitors
    function fetchRecentVisitors(basePath = '/api/admin/dashboard/') {
        axios.get(basePath + 'recent-visitors/10')
            .then(response => {
                if (response.data.status === 'success') {
                    const visitors = response.data.data;
                    renderRecentVisitors(visitors);
                }
            })
            .catch(error => {
                console.error('Error fetching recent visitors:', error);
                handleApiError(error);
            });
    }
    
    // Render recent visitors table
    function renderRecentVisitors(visitors) {
        const tableBody = document.getElementById('recent-visitors-table');
        
        // Remove loading row
        const loadingRow = document.getElementById('loading-row');
        if (loadingRow) {
            loadingRow.remove();
        }
        
        if (visitors.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'text-gray-700 dark:text-gray-300';
            emptyRow.innerHTML = `
                <td colspan="3" class="px-6 py-4 text-center">Belum ada data kunjungan</td>
            `;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        // Add visitor rows
        visitors.forEach(visitor => {
            const row = document.createElement('tr');
            row.className = 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${visitor.ip_address || 'Unknown'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${visitor.page_visited || '/'}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span title="${visitor.visited_at}">${visitor.time_ago}</span>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // Fetch top pages
    function fetchTopPages(basePath = '/api/admin/dashboard/') {
        axios.get(basePath + 'top-pages/5')
            .then(response => {
                if (response.data.status === 'success') {
                    const pages = response.data.data;
                    renderTopPages(pages);
                }
            })
            .catch(error => {
                console.error('Error fetching top pages:', error);
                handleApiError(error);
            });
    }
    
    // Render top pages table
    function renderTopPages(pages) {
        const tableBody = document.getElementById('top-pages-table');
        
        // Remove loading row
        const loadingRow = document.getElementById('loading-pages-row');
        if (loadingRow) {
            loadingRow.remove();
        }
        
        if (pages.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'text-gray-700 dark:text-gray-300';
            emptyRow.innerHTML = `
                <td colspan="2" class="px-6 py-4 text-center">Belum ada data halaman</td>
            `;
            tableBody.appendChild(emptyRow);
            return;
        }
        
        // Add page rows
        pages.forEach(page => {
            const row = document.createElement('tr');
            row.className = 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4">${page.page_visited || '/'}</td>
                <td class="px-6 py-4">${formatNumber(page.visit_count)}</td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // Helper function to check if user is shop admin based on URL
    function isShopAdmin() {
        return window.location.href.includes('/admin/shop') || 
               window.location.pathname.startsWith('/admin/shop');
    }
    
    // Helper function to format numbers with commas
    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }
    
    // Handle API errors
    function handleApiError(error) {
        if (error.response && error.response.status === 401) {
            // Redirect to login page if unauthorized
            window.location.href = '/admin/login';
        }
    }
</script>
@endpush 