<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'push_notifications';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'message',
        'icon',
        'image',
        'target_url',
        'scheduled_at',
        'is_sent',
        'user_kpri_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_sent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user that created the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_kpri_id', 'id_user');
    }
    
    /**
     * Scope a query to only include sent notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }
    
    /**
     * Scope a query to only include scheduled notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('is_sent', false);
    }
    
    /**
     * Scope a query to only include notifications scheduled for the future.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '>', now());
    }
    
    /**
     * Scope a query to only include notifications that should be sent now.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where('is_sent', false)
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '<=', now());
    }
} 