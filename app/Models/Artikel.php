<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artikel';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_artikel';

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
        'nama_artikel',
        'deskripsi_artikel',
        'tgl_rilis',
        'tags_artikel'
    ];

    /**
     * Get the status that owns the article.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    /**
     * Get the user that owns the article.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the comments for the article.
     */
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_artikel', 'id_artikel');
    }

    /**
     * Get the images for the article.
     */
    public function images()
    {
        return $this->hasMany(ArtikelImage::class, 'id_artikel', 'id_artikel');
    }
} 