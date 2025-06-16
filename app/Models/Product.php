<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
     * @var array<int, string>
     */
    protected $fillable = [
        'gambar_produk',
        'id_kategori',
        'nama_produk',
        'harga_produk',
        'stok_produk',
        'deskripsi_produk',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Get the promotions for the product.
     */
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'produk_promo', 'id_produk', 'id_promo');
    }
        /**
     * Scope for searching products by name or description.
     */
    public function scopeSearch($query, $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function($q) use ($term) {
            $q->where('nama_produk', 'like', "%{$term}%")
              ->orWhere('deskripsi_produk', 'like', "%{$term}%");
        });
    }

    /**
     * Scope for filtering by category.
     */
    public function scopeByCategory($query, $categories)
    {
        if (empty($categories)) {
            return $query;
        }

        return $query->whereIn('id_kategori', $categories);
    }

    /**
     * Scope for filtering by price range.
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('harga_produk', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('harga_produk', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope for in stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where('stok_produk', '>', 0);
    }

} 