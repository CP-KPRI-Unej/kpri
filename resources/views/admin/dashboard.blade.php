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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Visits -->
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
                        <h3 class="text-3xl font-bold">{{ number_format($totalVisitors) }}</h3>
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

        <!-- Today's Visits -->
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
                        <h3 class="text-3xl font-bold">{{ number_format($todayVisitors) }}</h3>
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

        <!-- This Month's Visits -->
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
                        <h3 class="text-3xl font-bold">{{ number_format($monthVisitors) }}</h3>
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
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jumlah kunjungan per bulan pada tahun {{ date('Y') }}</p>
            </div>
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <select id="chartYear" class="bg-transparent border-0 text-gray-700 dark:text-gray-300 font-medium rounded focus:outline-none text-sm">
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
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
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentVisitors as $visitor)
                        <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visitor['ip_address'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visitor['page_visited'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span title="{{ $visitor['visited_at'] }}">{{ $visitor['time_ago'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-gray-700 dark:text-gray-300">
                            <td colspan="3" class="px-6 py-4 text-center">Belum ada data kunjungan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hidden inputs for chart data -->
@foreach($chartData as $month => $count)
<input type="hidden" id="month-{{ $month }}" value="{{ $count }}">
@endforeach
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitorChart').getContext('2d');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        
        // Get data from hidden inputs
        const data = [];
        for (let i = 1; i <= 12; i++) {
            const input = document.getElementById('month-' + i);
            data.push(input ? parseInt(input.value) : 0);
        }
        
        // Calculate the maximum value to set a good scale
        const maxVisitors = Math.max(...data);
        const suggestedMax = maxVisitors + Math.ceil(maxVisitors * 0.2); // Add 20% for better visualization
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: data,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.6)', // blue-500 with opacity
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)', // blue-600
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
    });
</script>
@endpush 