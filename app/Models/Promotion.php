<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_kpri';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_promo';

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
        'id_user',
        'judul_promo',
        'tgl_start',
        'tgl_end',
        'tipe_diskon',
        'nilai_diskon',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tgl_start' => 'date',
        'tgl_end' => 'date',
        'nilai_diskon' => 'integer',
    ];

    /**
     * Get the user that owns the promotion.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the products associated with the promotion.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'produk_promo', 'id_promo', 'id_produk');
    }
} 