<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Api\ArtikelController;
use App\Http\Controllers\User\Api\ArtikelDetailController;
use App\Http\Controllers\User\Api\StrukturController;
use App\Http\Controllers\User\Api\DownloadItemController;
use App\Http\Controllers\User\Api\ProdukController;
use App\Http\Controllers\User\Api\PromoController;
use App\Http\Controllers\User\Api\LayananController;
use App\Http\Controllers\User\Api\GaleriController;
use App\Http\Controllers\User\Api\FAQController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardStatsController;
use App\Http\Controllers\User\Api\ProductSearchController;
use App\Http\Controllers\Api\ShopDashboardController;
use App\Http\Controllers\Api\LinktreeController;
use App\Http\Controllers\Api\AdminHeroBerandaController;
use App\Http\Controllers\Api\AdminDownloadItemController;
use App\Http\Controllers\Api\AdminProductController;
use App\Http\Controllers\Api\AdminPromotionController;
use App\Http\Controllers\Api\AdminLayananController;
use App\Http\Controllers\Api\PublicLinktreeController;
use App\Http\Controllers\Api\AdminGalleryController;
use App\Http\Controllers\Api\AdminFaqController;
use App\Http\Controllers\Api\AdminSettingsController;
use App\Http\Controllers\Api\AdminNotificationController;
use App\Http\Controllers\Api\TokoApiController;
use App\Http\Controllers\Api\PushSubscriptionController;
use App\Http\Controllers\Api\PublicNotificationController;



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

// Authentication Routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Admin Routes - KPRI Admin only
Route::group(['middleware' => ['auth:api', 'role:kpri admin'], 'prefix' => 'admin'], function () {
    // Protected routes for KPRI admin
    // Gallery routes
    Route::get('gallery', [AdminGalleryController::class, 'index']);
    Route::post('gallery', [AdminGalleryController::class, 'store']);
    Route::get('gallery/{id}', [AdminGalleryController::class, 'show']);
    Route::post('gallery/{id}', [AdminGalleryController::class, 'update']);
    Route::delete('gallery/{id}', [AdminGalleryController::class, 'destroy']);
    Route::get('gallery-statuses', [AdminGalleryController::class, 'getStatuses']);
    
    // FAQ routes
    Route::get('faqs', [AdminFaqController::class, 'index']);
    Route::post('faqs', [AdminFaqController::class, 'store']);
    Route::get('faqs/{id}', [AdminFaqController::class, 'show']);
    Route::post('faqs/{id}', [AdminFaqController::class, 'update']);
    Route::delete('faqs/{id}', [AdminFaqController::class, 'destroy']);

  // Dashboard Statistics
    Route::get('dashboard/summary', [DashboardStatsController::class, 'getSummaryStats']);
    Route::get('dashboard/recent-visitors/{limit?}', [DashboardStatsController::class, 'getRecentVisitors']);
    Route::get('dashboard/monthly-chart/{year?}', [DashboardStatsController::class, 'getMonthlyChartData']);
    Route::get('dashboard/top-pages/{limit?}', [DashboardStatsController::class, 'getTopPages']);
    Route::get('dashboard/daily-trend/{days?}', [DashboardStatsController::class, 'getDailyTrend']);
    
    // Download Items management
    Route::get('downloads', [AdminDownloadItemController::class, 'index']);
    Route::post('downloads', [AdminDownloadItemController::class, 'store']);
    Route::post('downloads/update-order', [AdminDownloadItemController::class, 'updateOrder']);
    Route::get('downloads/{id}', [AdminDownloadItemController::class, 'show']);
    Route::post('downloads/{id}', [AdminDownloadItemController::class, 'update']);
    Route::delete('downloads/{id}', [AdminDownloadItemController::class, 'destroy']);
    
    // Articles management
    Route::get('articles', [App\Http\Controllers\API\ArtikelController::class, 'index']);
    Route::post('articles', [App\Http\Controllers\API\ArtikelController::class, 'storeArticle']);
    Route::get('articles/{id}', [App\Http\Controllers\API\ArtikelController::class, 'getArticle']);
    Route::put('articles/{id}', [App\Http\Controllers\API\ArtikelController::class, 'updateArticle']);
    Route::post('articles/{id}', [App\Http\Controllers\API\ArtikelController::class, 'updateArticle']);
    Route::get('articles/{id}/comments', [App\Http\Controllers\API\ArtikelController::class, 'getComments']);
    Route::post('comments/{id}/status', [App\Http\Controllers\API\ArtikelController::class, 'updateCommentStatus']);
    Route::delete('comments/{id}', [App\Http\Controllers\API\ArtikelController::class, 'deleteComment']);
    Route::delete('articles/{id}', [App\Http\Controllers\API\ArtikelController::class, 'deleteArticle']);
    Route::get('article-statuses', [App\Http\Controllers\API\ArtikelController::class, 'getStatuses']);
    
    // Struktur Kepengurusan management
    Route::get('struktur', [App\Http\Controllers\API\StrukturController::class, 'index']);
    Route::get('struktur/{id}', [App\Http\Controllers\API\StrukturController::class, 'show']);
    Route::get('jabatan', [App\Http\Controllers\API\StrukturController::class, 'getJabatan']);
    Route::post('struktur', [App\Http\Controllers\API\StrukturController::class, 'store']);
    Route::put('struktur/{id}', [App\Http\Controllers\API\StrukturController::class, 'update']);
    Route::delete('struktur/{id}', [App\Http\Controllers\API\StrukturController::class, 'destroy']);
    
   
    // Linktree management
    Route::get('linktree', [LinktreeController::class, 'getLinktreeProfile']);
    Route::get('linktree/links', [LinktreeController::class, 'getLinks']);
    Route::post('linktree/profile', [LinktreeController::class, 'updateProfile']);
    Route::post('linktree/links', [LinktreeController::class, 'storeLink']);
    Route::put('linktree/links/{id}', [LinktreeController::class, 'updateLink']);
    Route::delete('linktree/links/{id}', [LinktreeController::class, 'deleteLink']);
    Route::post('linktree/links/positions', [LinktreeController::class, 'updatePositions']);
    
    // Category management
    Route::get('categories', [App\Http\Controllers\API\KategoriProdukController::class, 'index']);
    Route::get('categories/{id}', [App\Http\Controllers\API\KategoriProdukController::class, 'show']);
    Route::post('categories', [App\Http\Controllers\API\KategoriProdukController::class, 'store']);
    Route::put('categories/{id}', [App\Http\Controllers\API\KategoriProdukController::class, 'update']);
    Route::delete('categories/{id}', [App\Http\Controllers\API\KategoriProdukController::class, 'destroy']);
    
    // Product management
    Route::get('products', [AdminProductController::class, 'index']);
    Route::post('products', [AdminProductController::class, 'store']);
    Route::get('products/{id}', [AdminProductController::class, 'show']);
    Route::post('products/{id}', [AdminProductController::class, 'update']);
    Route::delete('products/{id}', [AdminProductController::class, 'destroy']);
    Route::get('product-categories', [AdminProductController::class, 'categories']);
    Route::post('products/{id}/promotions', [AdminProductController::class, 'addToPromotions']);
    Route::get('products/{id}/promotions', [AdminProductController::class, 'getPromotions']);
    
    // Promotion management
    Route::get('promotions', [AdminPromotionController::class, 'index']);
    Route::post('promotions', [AdminPromotionController::class, 'store']);
    Route::get('promotions/{id}', [AdminPromotionController::class, 'show']);
    Route::post('promotions/{id}', [AdminPromotionController::class, 'update']);
    Route::delete('promotions/{id}', [AdminPromotionController::class, 'destroy']);
    Route::get('available-products', [AdminPromotionController::class, 'getAvailableProducts']);
    Route::get('promotions/{id}/products', [AdminPromotionController::class, 'getPromotionProducts']);
    Route::post('promotions/{id}/products', [AdminPromotionController::class, 'addProducts']);
    Route::delete('promotions/{id}/products', [AdminPromotionController::class, 'removeProducts']);

    Route::get('hero-banners', [AdminHeroBerandaController::class, 'index']);
    Route::post('hero-banners', [AdminHeroBerandaController::class, 'store']);
    Route::get('hero-banners/{id}', [AdminHeroBerandaController::class, 'show']);
    Route::post('hero-banners/{id}', [AdminHeroBerandaController::class, 'update']);
    Route::delete('hero-banners/{id}', [AdminHeroBerandaController::class, 'destroy']);
    
    // Layanan (Service) management
    Route::get('layanan/jenis', [AdminLayananController::class, 'getJenisLayanan']);
    Route::get('layanan/jenis/{id}', [AdminLayananController::class, 'getJenisLayananById']);
    Route::get('layanan/detail/{id}', [AdminLayananController::class, 'show']);
    Route::get('layanan/{jenisLayananId?}', [AdminLayananController::class, 'index']);
    Route::post('layanan', [AdminLayananController::class, 'store']);
    Route::post('layanan/{id}', [AdminLayananController::class, 'update']);
    Route::delete('layanan/{id}', [AdminLayananController::class, 'destroy']);

    Route::get('settings/profile', [AdminSettingsController::class, 'getProfile']);
    Route::put('settings/profile', [AdminSettingsController::class, 'updateProfile']);
    Route::put('settings/password', [AdminSettingsController::class, 'updatePassword']);

    // Notification management
    Route::get('notifications', [AdminNotificationController::class, 'index']);
    Route::get('notifications/stats', [AdminNotificationController::class, 'stats']);
    Route::get('notifications/{id}', [AdminNotificationController::class, 'show']);
    Route::post('notifications', [AdminNotificationController::class, 'store']);
    Route::put('notifications/{id}', [AdminNotificationController::class, 'update']);
    Route::delete('notifications/{id}', [AdminNotificationController::class, 'destroy']);
    Route::post('notifications/{id}/send-now', [AdminNotificationController::class, 'sendNow']);
    Route::post('notifications/process-due', [AdminNotificationController::class, 'processDue']);
});

// Admin Shop Routes - Admin Shop only
Route::group(['middleware' => ['auth:api', 'role:admin shop'], 'prefix' => 'shop'], function () {
    // Protected routes for admin shop
    Route::get('dashboard', function() {
        return response()->json(['message' => 'Shop Admin Dashboard']);
    });

    // Shop dashboard statistics
    Route::get('dashboard/stats', [ShopDashboardController::class, 'getStats']);
    Route::get('dashboard/products', [ShopDashboardController::class, 'getRecentProducts']);
    Route::get('dashboard/chart-data', [ShopDashboardController::class, 'getChartData']);
});

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
Route::get('/downloads/{id}/file', [DownloadItemController::class, 'download']);

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

Route::get('/linktree/{id?}', [PublicLinktreeController::class, 'getLinktree']);

// Toko (Shop) API Routes
Route::get('/shop/products', [TokoApiController::class, 'getProducts']);
Route::get('/shop/products/{id}', [TokoApiController::class, 'getProduct']);
Route::get('/shop/categories', [TokoApiController::class, 'getCategories']);
Route::get('/shop/promotions', [TokoApiController::class, 'getPromotions']);

// Push Notification Routes
Route::get('/push/key', [PushSubscriptionController::class, 'getPublicKey']);
Route::post('/push/subscribe', [PushSubscriptionController::class, 'store']);
Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy']);
Route::get('/push/notifications/recent', [PublicNotificationController::class, 'getRecent']);