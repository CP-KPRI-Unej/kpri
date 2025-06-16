<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShopDashboardController extends Controller
{
    public function getStats()
    {
        // In a real application, fetch these from your database
        $stats = [
            'totalProducts' => 150,
            'outOfStock' => 12,
            'totalRevenue' => 15000000,
            'activePromos' => 5
        ];

        return response()->json($stats);
    }

    public function getRecentProducts()
    {
        // Sample data - in production, fetch from your database
        $products = [
            [
                'id_produk' => 1,
                'nama_produk' => 'Produk A',
                'harga_produk' => 150000,
                'stok_produk' => 25,
                'kategori' => 'Elektronik'
            ],
            [
                'id_produk' => 2,
                'nama_produk' => 'Produk B',
                'harga_produk' => 250000,
                'stok_produk' => 10,
                'kategori' => 'Makanan'
            ],
            [
                'id_produk' => 3,
                'nama_produk' => 'Produk C',
                'harga_produk' => 50000,
                'stok_produk' => 0,
                'kategori' => 'ATK'
            ]
        ];

        return response()->json($products);
    }

    public function getChartData()
    {
        // Sample data for chart - in production, aggregate from database
        $chartData = [
            1 => 15, 2 => 20, 3 => 25, 4 => 30, 
            5 => 20, 6 => 35, 7 => 40, 8 => 30,
            9 => 45, 10 => 50, 11 => 55, 12 => 60
        ];

        return response()->json($chartData);
    }
} 