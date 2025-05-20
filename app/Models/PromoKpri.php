<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoKpri extends Model
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
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tgl_start' => 'date',
        'tgl_end' => 'date',
    ];

    /**
     * Get the user that owns the promotion.
     */
    public function user()
    {
        return $this->belongsTo(UserKPRI::class, 'id_user', 'id_user');
    }

    /**
     * Get the products for the promotion.
     */
    public function produks()
    {
        return $this->belongsToMany(ProdukKpri::class, 'produk_promo', 'id_promo', 'id_produk');
    }

    /**
     * Get formatted discount value.
     */
    public function getFormattedDiskonAttribute()
    {
        if ($this->tipe_diskon === 'persen') {
            return $this->nilai_diskon . '%';
        } else {
            return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
        }
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'aktif':
                return 'badge bg-success';
            case 'nonaktif':
                return 'badge bg-warning text-dark';
            case 'berakhir':
                return 'badge bg-secondary';
            default:
                return 'badge bg-secondary';
        }
    }
} 