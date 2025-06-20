<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FAQ';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_faq';

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
        'judul',
        'deskripsi',
        'id_status',
    ];
    
    /**
     * Get the user that owns the FAQ.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    /**
     * Get the status of the FAQ.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }
}
