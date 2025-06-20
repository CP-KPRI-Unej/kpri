<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturKepengurusan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'struktur_kepengurusan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_pengurus';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_jabatan',
        'id_periode',
        'nama_pengurus',
    ];

    /**
     * Get the jabatan that owns the pengurus.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    /**
     * Get the periode that owns the pengurus.
     */
    public function periode()
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'id_periode', 'id_periode');
    }
} 