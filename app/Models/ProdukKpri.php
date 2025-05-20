<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKpri extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produk_kpri';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_produk';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_produk',
        'gambar_produk',
        'id_kategori',
        'harga_produk',
        'stok_produk',
        'deskripsi_produk',
    ];

    /**
     * Get the kategori that owns the product.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the promos for the product.
     */
    public function promos()
    {
        return $this->belongsToMany(PromoKpri::class, 'produk_promo', 'id_produk', 'id_promo');
    }
} 