<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpriTable extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_layanan', function (Blueprint $table) {
            $table->increments('id_jenis_layanan');
            $table->string('nama_layanan', 120);
        });

        Schema::create('layanan', function (Blueprint $table) {
            $table->increments('id_layanan');
            $table->unsignedInteger('id_jenis_layanan');
            $table->string('judul_layanan', 120);
            $table->text('deskripsi_layanan')->nullable();
            $table->string('gambar')->nullable(); 
        
            $table->foreign('id_jenis_layanan')->references('id_jenis_layanan')->on('jenis_layanan');
        });
        
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id_status');
            $table->string('nama_status', 20);
        });

        Schema::create('role', function (Blueprint $table) {
            $table->increments('id_role');
            $table->string('nama_role', 30)->nullable();
        });

        Schema::create('user_KPRI', function (Blueprint $table) {
            $table->increments('id_user');
            $table->unsignedInteger('id_role');
            $table->string('nama_user', 100)->unique();
            $table->string('username', 20);
            $table->text('password');

            $table->foreign('id_role')->references('id_role')->on('role');
        });

        
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('target_url')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->unsignedInteger('user_kpri_id')->nullable();
            $table->foreign('user_kpri_id')->references('id_user')->on('user_KPRI')->onDelete('set null');
            $table->timestamps();
        });
        
        Schema::create('push_subscriptions_guest', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint')->unique();
            $table->json('keys'); // lebih tepat pakai JSON
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
        
         Schema::create('linktree', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title', 100);
            $table->string('logo', 255)->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id_user')->on('user_KPRI')->onDelete('cascade');
        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->string('title', 100);
            $table->string('url', 255);
            $table->integer('position');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('page_id')->references('id')->on('linktree')->onDelete('cascade');
        });
        
        Schema::create('artikel', function (Blueprint $table) {
            $table->increments('id_artikel');
            $table->unsignedInteger('id_user');
            $table->string('nama_artikel', 120);
            $table->text('deskripsi_artikel');
            $table->date('tgl_rilis');
            $table->string('tags_artikel', 255);
            

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
        
            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
        });
        
        
        Schema::create('artikel_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_artikel');
            $table->string('gambar', 255); // path/nama file gambarnya
            $table->timestamps();
            $table->foreign('id_artikel')->references('id_artikel')->on('artikel')->onDelete('cascade');
        });

        Schema::create('komentar', function (Blueprint $table) {
            $table->increments('id_komentar');
            $table->unsignedInteger('id_artikel');
            $table->unsignedInteger('parent_id')->nullable(); // Menyimpan ID komentar induk
            $table->string('nama_pengomentar', 100); 
            $table->string('isi_komentar', 255);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); 
        
            $table->foreign('id_artikel')->references('id_artikel')->on('artikel')->onDelete('cascade');
            $table->foreign('parent_id')->references('id_komentar')->on('komentar')->onDelete('cascade'); // Relasi self-referencing
            $table->timestamps(); 
        });
        
        

        Schema::create('jabatan', function (Blueprint $table) {
            $table->increments('id_jabatan');
            $table->string('nama_jabatan', 20);
        });

        Schema::create('periode_kepengurusan', function (Blueprint $table) {
            $table->increments('id_periode');
            $table->string('nama_periode', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('struktur_kepengurusan', function (Blueprint $table) {
            $table->increments('id_pengurus');
            $table->unsignedInteger('id_jabatan');
            $table->unsignedInteger('id_periode');
            $table->string('nama_pengurus', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan');
            $table->foreign('id_periode')->references('id_periode')->on('periode_kepengurusan');
        });

        Schema::create('form_kpri', function (Blueprint $table) {
            $table->increments('id_form');
            $table->unsignedInteger('id_user');
            $table->string('nama_form', 120);
            $table->text('file_form');
            $table->date('tgl_upload');

            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
        });

        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->string('kategori', 30);
        });

        Schema::create('produk_kpri', function (Blueprint $table) {
            $table->bigIncrements('id_produk'); // Auto-incrementing BIGINT primary key
            $table->string('gambar_produk')->nullable();
            $table->unsignedInteger('id_kategori');
            $table->string('nama_produk', 120);
            $table->integer('harga_produk');
            $table->integer('stok_produk');
            $table->text('deskripsi_produk')->nullable();
        
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_produk');
        });
        
        Schema::create('promo_kpri', function (Blueprint $table) {
            $table->bigIncrements('id_promo'); // Auto-incrementing BIGINT primary key
            $table->unsignedInteger('id_user');
            $table->string('judul_promo', 120);
            $table->date('tgl_start');
            $table->date('tgl_end');
        
            $table->enum('tipe_diskon', ['persen', 'nominal']);
            $table->integer('nilai_diskon'); 
            $table->enum('status', ['aktif', 'nonaktif', 'berakhir'])->default('aktif'); 
        
            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
        });
        
        
        Schema::create('produk_promo', function (Blueprint $table) {
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_promo');
        
            $table->primary(['id_produk', 'id_promo']);
            $table->foreign('id_produk')->references('id_produk')->on('produk_kpri')->cascadeOnDelete();
            $table->foreign('id_promo')->references('id_promo')->on('promo_kpri')->cascadeOnDelete();
        });
        

        Schema::create('galeri_foto', function (Blueprint $table) {
            $table->increments('id_galeri');
            $table->unsignedInteger('id_status');
            $table->unsignedInteger('id_user');
            $table->string('nama_galeri', 30);
            $table->string('gambar_galeri', 100);
            $table->date('tgl_upload');

            $table->foreign('id_status')->references('id_status')->on('status');
            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
        });

        Schema::create('FAQ', function (Blueprint $table) {
            $table->increments('id_faq');
            $table->unsignedInteger('id_user');
            $table->string('judul', 120);
            $table->text('deskripsi');
            $table->unsignedInteger('id_status')->default(1);

            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
            $table->foreign('id_status')->references('id_status')->on('status');
        });

        Schema::create('hero_beranda', function (Blueprint $table) {
            $table->increments('id_hero');
            $table->unsignedInteger('id_user');
            $table->string('judul', 120);
            $table->text('deskripsi');
            $table->string('gambar', 100);
            $table->string('url', 255);
            $table->unsignedInteger('id_status')->default(1);

            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
            $table->foreign('id_status')->references('id_status')->on('status');
        });

        Schema::create('log_perubahan', function (Blueprint $table) {
            $table->increments('id_log');
            $table->unsignedInteger('id_user');
            $table->string('nama_tabel', 50);
            $table->integer('id_data')->nullable();
            $table->string('aksi', 10)->nullable();
            $table->timestamp('tgl_log')->nullable();
            $table->text('data_lama')->nullable();
            $table->text('data_baru')->nullable();

            $table->foreign('id_user')->references('id_user')->on('user_KPRI');
        });
        
        Schema::create('download_item', function (Blueprint $table) {
            $table->increments('id_download_item');
            $table->unsignedInteger('id_user');
            $table->string('nama_item', 120);
            $table->string('path_file', 255);
            $table->unsignedInteger('id_status');
            $table->date('tgl_upload');
            $table->integer('urutan')->default(0);
        
            $table->foreign('id_user')->references('id_user')->on('user_KPRI')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('status');
        });

        Schema::create('visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_visited')->default('/');
            $table->timestamp('visited_at')->useCurrent();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('log_perubahan');
        Schema::dropIfExists('push_notifications');
        Schema::dropIfExists('push_subscriptions_guest');
        Schema::dropIfExists('push_notification_logs');
        Schema::dropIfExists('hero_beranda');
        Schema::dropIfExists('FAQ');
        Schema::dropIfExists('galeri_foto');
        Schema::dropIfExists('produk_promo');
        Schema::dropIfExists('promo_kpri');
        Schema::dropIfExists('produk_kpri');
        Schema::dropIfExists('kategori_produk');
        Schema::dropIfExists('form_kpri');
        Schema::dropIfExists('struktur_kepengurusan');
        Schema::dropIfExists('periode_kepengurusan');
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('komentar');
        Schema::dropIfExists('artikel_images');
        Schema::dropIfExists('artikel');
        Schema::dropIfExists('user_KPRI');
        Schema::dropIfExists('role');
        Schema::dropIfExists('status');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('jenis_layanan');
        Schema::dropIfExists('download_item');
        Schema::dropIfExists('visitor_stats');
    }
}
