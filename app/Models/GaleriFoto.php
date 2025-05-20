<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galeri_foto';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_galeri';

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
        'id_status',
        'id_user',
        'nama_galeri',
        'gambar_galeri',
        'tgl_upload',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tgl_upload' => 'date',
    ];

    /**
     * Get the status that owns the gallery photo.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    /**
     * Get the user that owns the gallery photo.
     */
    public function user()
    {
        return $this->belongsTo(UserKPRI::class, 'id_user', 'id_user');
    }
} 