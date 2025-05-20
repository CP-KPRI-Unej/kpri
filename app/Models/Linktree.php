<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linktree extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'linktree';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
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
        'user_id',
        'title',
        'logo',
        'bio',
    ];
    
    /**
     * Get the user that owns the linktree page.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
    
    /**
     * Get the links for this linktree page.
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'page_id', 'id')->orderBy('position', 'asc');
    }
} 