<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori_produk';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_kategori';

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
        'kategori',
    ];

    /**
     * Get the products for the category.
     */
    public function produks()
    {
        return $this->hasMany(ProdukKpri::class, 'id_kategori', 'id_kategori');
    }
} 