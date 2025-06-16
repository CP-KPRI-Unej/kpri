<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_layanan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_jenis_layanan';

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
        'nama_layanan',
    ];

    /**
     * Get the services associated with this service type.
     */
    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'id_jenis_layanan', 'id_jenis_layanan');
    }
} 