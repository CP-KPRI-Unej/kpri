<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\StrukturController;
use App\Http\Controllers\Admin\KomentarController;
use App\Http\Controllers\ArtikelController as PublicArtikelController;
use App\Http\Controllers\Admin\DownloadItemController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\KategoriProdukController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\JenisLayananController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\LinktreeController as AdminLinktreeController;
use App\Http\Controllers\LinktreeController;
use App\Models\JenisLayanan;
use App\Http\Controllers\Admin\FAQController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// View composer for sharing pages data with all views
View::composer('admin.layouts.sidebar', function ($view) {
    $view->with('jenisLayanans', JenisLayanan::all());
});

Route::get("guest/home", function () {
    return view("comprof.home");
})->name('home');
Route::get("guest/tentang-kami", function () {
    return view("comprof.profile");
})->name('about');
Route::get("guest/gerai-layanan", function () {
    return view("comprof.home");
})->name('services');
Route::get("guest/unit-simpan-pinjam", function () {
    return view("comprof.home");
})->name('savings');
Route::get("guest/unit-jasa", function () {
    return view("comprof.home");
})->name('services-unit');
Route::get("guest/unit-toko", function () {
    return view("comprof.home");
})->name('store');
Route::get("guest/info-anggota", function () {
    return view("comprof.home");
})->name('members');

Route::get('/', [LinktreeController::class, 'index']);


// Authentication Routes
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('admin/login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Linktree routes
    Route::get('/linktree', [AdminLinktreeController::class, 'index'])->name('admin.linktree.index');
    Route::post('/linktree/profile', [AdminLinktreeController::class, 'updateProfile'])->name('admin.linktree.update-profile');
    Route::get('/linktree/logo', [AdminLinktreeController::class, 'getLogoUrl'])->name('admin.linktree.get-logo');
    Route::post('/linktree/link', [AdminLinktreeController::class, 'storeLink'])->name('admin.linktree.store-link');
    Route::put('/linktree/link/{id}', [AdminLinktreeController::class, 'updateLink'])->name('admin.linktree.update-link');
    Route::delete('/linktree/link/{id}', [AdminLinktreeController::class, 'deleteLink'])->name('admin.linktree.delete-link');
    Route::post('/linktree/link/positions', [AdminLinktreeController::class, 'updateLinkPositions'])->name('admin.linktree.update-positions');

    // Artikel routes
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('admin.artikel.index');
    Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('admin.artikel.create');
    Route::post('/artikel', [ArtikelController::class, 'store'])->name('admin.artikel.store');
    Route::get('/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('admin.artikel.edit');
    Route::put('/artikel/{id}', [ArtikelController::class, 'update'])->name('admin.artikel.update');
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy'])->name('admin.artikel.destroy');

    // Komentar routes
    Route::get('/artikel/{artikelId}/komentar/{status?}', [KomentarController::class, 'index'])->name('admin.artikel.komentar.index');
    Route::patch('/komentar/{id}/status', [KomentarController::class, 'updateStatus'])->name('admin.komentar.update-status');
    Route::delete('/komentar/{id}', [KomentarController::class, 'destroy'])->name('admin.komentar.destroy');

    // Struktur Kepengurusan routes
    Route::get('/struktur', [StrukturController::class, 'index'])->name('admin.struktur.index');
    Route::get('/struktur/create', [StrukturController::class, 'create'])->name('admin.struktur.create');
    Route::post('/struktur', [StrukturController::class, 'store'])->name('admin.struktur.store');
    Route::get('/struktur/{id}/edit', [StrukturController::class, 'edit'])->name('admin.struktur.edit');
    Route::put('/struktur/{id}', [StrukturController::class, 'update'])->name('admin.struktur.update');
    Route::delete('/struktur/{id}', [StrukturController::class, 'destroy'])->name('admin.struktur.destroy');



    // Download Item routes
    Route::get('/download', [DownloadItemController::class, 'index'])->name('admin.download.index');
    Route::get('/download/create', [DownloadItemController::class, 'create'])->name('admin.download.create');
    Route::post('/download', [DownloadItemController::class, 'store'])->name('admin.download.store');
    Route::get('/download/{id}/edit', [DownloadItemController::class, 'edit'])->name('admin.download.edit');
    Route::put('/download/{id}', [DownloadItemController::class, 'update'])->name('admin.download.update');
    Route::delete('/download/{id}', [DownloadItemController::class, 'destroy'])->name('admin.download.destroy');
    Route::post('/download/update-order', [DownloadItemController::class, 'updateOrder'])->name('admin.download.update-order');
    Route::get('/download/{id}', [DownloadItemController::class, 'download'])->name('admin.download.file');

    // Produk routes
    Route::get('/produk', [ProdukController::class, 'index'])->name('admin.produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

    // Kategori Produk routes
    Route::get('/kategori', [KategoriProdukController::class, 'index'])->name('admin.kategori.index');
    Route::get('/kategori/create', [KategoriProdukController::class, 'create'])->name('admin.kategori.create');
    Route::post('/kategori', [KategoriProdukController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriProdukController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/kategori/{id}', [KategoriProdukController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategori/{id}', [KategoriProdukController::class, 'destroy'])->name('admin.kategori.destroy');

    // Promo routes
    Route::get('/promo', [PromoController::class, 'index'])->name('admin.promo.index');
    Route::get('/promo/create', [PromoController::class, 'create'])->name('admin.promo.create');
    Route::post('/promo', [PromoController::class, 'store'])->name('admin.promo.store');
    Route::get('/promo/{id}/edit', [PromoController::class, 'edit'])->name('admin.promo.edit');
    Route::put('/promo/{id}', [PromoController::class, 'update'])->name('admin.promo.update');
    Route::patch('/promo/{id}/status', [PromoController::class, 'updateStatus'])->name('admin.promo.update-status');
    Route::delete('/promo/{id}', [PromoController::class, 'destroy'])->name('admin.promo.destroy');

    // Galeri Foto routes
    Route::get('/galeri', [GaleriController::class, 'index'])->name('admin.galeri.index');
    Route::get('/galeri/create', [GaleriController::class, 'create'])->name('admin.galeri.create');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::get('/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('admin.galeri.edit');
    Route::put('/galeri/{id}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');

    // Manajemen Halaman routes
    Route::get('/halaman', [JenisLayananController::class, 'index'])->name('admin.halaman.index');

    // Layanan routes
    Route::get('/halaman/{id_jenis_layanan}/layanan', [LayananController::class, 'index'])->name('admin.layanan.index');
    Route::get('/halaman/{id_jenis_layanan}/layanan/{id}/edit', [LayananController::class, 'edit'])->name('admin.layanan.edit');
    Route::put('/halaman/{id_jenis_layanan}/layanan/{id}', [LayananController::class, 'update'])->name('admin.layanan.update');

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('admin.settings.update-password');
    Route::post('/settings/theme', [SettingsController::class, 'saveTheme'])->name('admin.settings.save-theme');

    // FAQ routes
    Route::get('/faq', [FAQController::class, 'index'])->name('admin.faq.index');
    Route::get('/faq/create', [FAQController::class, 'create'])->name('admin.faq.create');
    Route::post('/faq', [FAQController::class, 'store'])->name('admin.faq.store');
    Route::get('/faq/{id}/edit', [FAQController::class, 'edit'])->name('admin.faq.edit');
    Route::put('/faq/{id}', [FAQController::class, 'update'])->name('admin.faq.update');
    Route::delete('/faq/{id}', [FAQController::class, 'destroy'])->name('admin.faq.destroy');
});
