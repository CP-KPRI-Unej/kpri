<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeKepengurusan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periode_kepengurusan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_periode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the struktur pengurus associated with this periode.
     */
    public function strukturPengurus()
    {
        return $this->hasMany(StrukturKepengurusan::class, 'id_periode', 'id_periode');
    }
}
