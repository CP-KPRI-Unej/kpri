<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokoController;



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

// Admin Authentication Routes
Route::get('/admin/login', function () {
    return view('auth.login');
})->name('admin.login');

// Admin Dashboard Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/shop-dashboard', function() {
        return view('admin.shop.dashboard');
    })->name('admin.shop.dashboard');

    // Download routes
    Route::get('/download', function() {
        return view('admin.download.index');
    })->name('admin.download.index');

    Route::get('/download/create', function() {
        return view('admin.download.create');
    })->name('admin.download.create');

    Route::get('/download/{id}/edit', function($id) {
        return view('admin.download.edit', ['id' => $id]);
    })->name('admin.download.edit');

    // Artikel routes
    Route::get('/artikel', function() {
        return view('admin.artikel.index');
    })->name('admin.artikel.index');

    Route::get('/artikel/create', function() {
        return view('admin.artikel.create');
    })->name('admin.artikel.create');

    Route::get('/artikel/{id}/edit', function($id) {
        return view('admin.artikel.edit', ['id' => $id]);
    })->name('admin.artikel.edit');

    Route::get('/artikel/{id}/komentar', function($id) {
        return view('admin.artikel.komentar.index');
    })->name('admin.artikel.komentar.index');

    // Struktur Kepengurusan routes
    Route::get('/struktur', function() {
        return view('admin.struktur.index');
    })->name('admin.struktur.index');

    Route::get('/struktur/create', function() {
        return view('admin.struktur.create');
    })->name('admin.struktur.create');

    Route::get('/struktur/{id}/edit', function($id) {
        return view('admin.struktur.edit', ['id' => $id]);
    })->name('admin.struktur.edit');

    // Linktree routes
    Route::get('/linktree', function() {
        return view('admin.linktree.index');
    })->name('admin.linktree.index');

    // Kategori Produk routes
    Route::get('/kategori', function() {
        return view('admin.kategori.index');
    })->name('admin.kategori.index');

    Route::get('/kategori/create', function() {
        return view('admin.kategori.create');
    })->name('admin.kategori.create');

    Route::get('/kategori/{id}/edit', function($id) {
        return view('admin.kategori.edit', ['id' => $id]);
    })->name('admin.kategori.edit');


    // Product routes
    Route::get('/produk', function() {
        return view('admin.produk.index');
    })->name('admin.produk.index');

    Route::get('/produk/create', function() {
        return view('admin.produk.create');
    })->name('admin.produk.create');

    Route::get('/produk/{id}/edit', function($id) {
        return view('admin.produk.edit', ['id' => $id]);
    })->name('admin.produk.edit');

    // Promotion routes
    Route::get('/promo', function() {
        return view('admin.promo.index');
    })->name('admin.promo.index');

    Route::get('/promo/create', function() {
        return view('admin.promo.create');
    })->name('admin.promo.create');

    Route::get('/promo/edit/{id}', function($id) {
        return view('admin.promo.edit', ['id' => $id]);
    })->name('admin.promo.edit');

    // Layanan (Service) routes
    Route::get('/layanan/{id_jenis_layanan}', function($id_jenis_layanan) {
        return view('admin.layanan.index', ['jenis_layanan_id' => $id_jenis_layanan]);
    })->name('admin.layanan.index');

    Route::get('/layanan/edit/{id}', function($id) {
        return view('admin.layanan.edit', ['id' => $id]);
    })->name('admin.layanan.edit');

    // Gallery routes
    Route::get('/galeri', function() {
        return view('admin.galeri.index');
    })->name('admin.galeri.index');

    Route::get('/galeri/create', function() {
        return view('admin.galeri.create');
    })->name('admin.galeri.create');

    Route::get('/galeri/{id}/edit', function($id) {
        return view('admin.galeri.edit', ['id' => $id]);
    })->name('admin.galeri.edit');

    // FAQ routes
    Route::get('/faq', function() {
        return view('admin.faq.index');
    })->name('admin.faq.index');

    Route::get('/faq/create', function() {
        return view('admin.faq.create');
    })->name('admin.faq.create');

    Route::get('/faq/{id}/edit', function($id) {
        return view('admin.faq.edit', ['id' => $id]);
    })->name('admin.faq.edit');

    // Hero Banner routes
    Route::get('/hero-banners', function() {
        return view('admin.hero-banners.index');
    })->name('admin.hero-banners.index');

    Route::get('/hero-banners/create', function() {
        return view('admin.hero-banners.create');
    })->name('admin.hero-banners.create');

    Route::get('/hero-banners/{id}/edit', function($id) {
        return view('admin.hero-banners.edit', ['id' => $id]);
    })->name('admin.hero-banners.edit');

    // Settings routes
    Route::get('/settings', function() {
        return view('admin.settings.index');
    })->name('admin.settings.index');

    // Notification routes
    Route::get('/notifications', function() {
        return view('admin.notifications.index');
    })->name('admin.notifications.index');

    // Alias untuk /notification tanpa 's' agar tetap berfungsi
    Route::get('/notification', function() {
        return view('admin.notifications.index');
    })->name('admin.notification.index');

    Route::get('/notifications/create', function() {
        return view('admin.notifications.create');
    })->name('admin.notifications.create');

    Route::get('/notifications/{id}/edit', function($id) {
        return view('admin.notifications.edit', ['id' => $id]);
    })->name('admin.notifications.edit');
});

Route::get("beranda", function () {
    return view("comprof.beranda");
})->name('beranda');
Route::get("tentang-kami", function () {
    return view("comprof.profil");
})->name('tentang-kami');
Route::get("gerai-layanan", function () {
    return view("comprof.gerai-layanan");
})->name('gerai-layanan');
Route::get("unit-simpan-pinjam", function () {
    return view("comprof.unit-simpan-pinjam");
})->name('unit-simpan-pinjam');
Route::get("unit-jasa", function () {
    return view("comprof.unit-jasa");
})->name('unit-jasa');
Route::get("unit-toko", function () {
    return view("comprof.unit-toko");
})->name('unit-toko');
Route::get("info-anggota", function () {
    return view("comprof.beranda");
})->name('members');
Route::get('/artikel/{id}', function ($id) {
    return view('article.artikel-detail', ['id' => $id]);
})->name('articles.show');
Route::get('/artikel', function () {
    return view('article.artikel');
})->name('articles.all');

// Toko (Shop) routes
Route::get('/toko', function() {
    return view('toko.index-spa');
})->name('toko.index');

Route::get('/toko/produk/{id}', function($id) {
    return view('toko.show-spa', ['id' => $id]);
})->name('toko.show');

// Serve a static view for linktree that will consume the API via JavaScript
Route::get('/', function () {
    return view('linktree.static');
});

// Support for linktree with specific ID
Route::get('/l/{id}', function ($id) {
    return view('linktree.static', ['id' => $id]);
});


