<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\ArtikelDetailController;
use App\Http\Controllers\Api\StrukturController;
use App\Http\Controllers\Api\DownloadItemController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\FAQController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Artikel API Routes
Route::get('/articles', [ArtikelController::class, 'index']);
Route::get('/articles/{id}', [ArtikelDetailController::class, 'show']);
Route::post('/articles/{id}/comments', [ArtikelDetailController::class, 'storeComment']);

// Struktur Kepengurusan API Routes
Route::get('/struktur', [StrukturController::class, 'index']);
Route::get('/struktur/{id}', [StrukturController::class, 'show']);

// Download Items API Routes
Route::get('/downloads', [DownloadItemController::class, 'index']);
Route::get('/downloads/{id}', [DownloadItemController::class, 'show']);

// Produk API Routes
Route::get('/products', [ProdukController::class, 'index']);
Route::get('/products/{id}', [ProdukController::class, 'show']);
Route::get('/product-categories', [ProdukController::class, 'categories']);

// Promo API Routes
Route::get('/promos', [PromoController::class, 'index']);
Route::get('/promos/{id}', [PromoController::class, 'show']);

// Layanan API Routes
Route::get('/service-types', [LayananController::class, 'getJenisLayanan']);
Route::get('/service-types/{id}', [LayananController::class, 'getJenisLayananById']);
Route::get('/services/{id}', [LayananController::class, 'getLayananById']);

// Galeri API Routes
Route::get('/gallery', [GaleriController::class, 'index']);
Route::get('/gallery/{id}', [GaleriController::class, 'show']);

// FAQ API Routes
Route::get('/faqs', [FAQController::class, 'index']);
Route::get('/faqs/{id}', [FAQController::class, 'show']);
