<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galeri_foto';

    /**
     * The primary key associated with the table.
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
     * Get the user that owns the gallery.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the status of the gallery.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
} 
 
 
 
 
 
 
 
 
 
 
 