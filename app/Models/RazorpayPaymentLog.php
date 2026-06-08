<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpayPaymentLog extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_type',
        'payment_id',
        'order_id',
        'entity_type',
        'entity_id',
        'status',
        'data',
        'user_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the payment log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the related entity based on entity_type
     */
    public function entity()
    {
        if (!$this->entity_type || !$this->entity_id) {
            return null;
        }

        $className = 'App\\Models\\' . ucfirst($this->entity_type);
        
        if (!class_exists($className)) {
            return null;
        }

        return $className::find($this->entity_id);
    }
    
    /**
     * Scope a query to only include logs for a specific entity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $entityType
     * @param int $entityId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEntity($query, $entityType, $entityId)
    {
        return $query->where('entity_type', $entityType)
                     ->where('entity_id', $entityId);
    }
    
    /**
     * Scope a query to only include logs with a specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope a query to only include logs for successful payments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
    
    /**
     * Scope a query to only include logs for failed payments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
} 