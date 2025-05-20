<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'layanan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_layanan';

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
        'id_jenis_layanan',
        'judul_layanan',
        'deskripsi_layanan',
    ];

    /**
     * Get the service type that owns this service.
     */
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'id_jenis_layanan', 'id_jenis_layanan');
    }
} 