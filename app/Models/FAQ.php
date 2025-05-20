<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    
    protected $table = 'FAQ';
    protected $primaryKey = 'id_faq';
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'judul',
        'deskripsi'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
