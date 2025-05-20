<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_status';

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
        'nama_status',
    ];

    /**
     * Get the gallery items with this status.
     */
    public function galeri_fotos()
    {
        return $this->hasMany(GaleriFoto::class, 'id_status', 'id_status');
    }

    /**
     * Get the articles with this status.
     */
    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'id_status', 'id_status');
    }
} 