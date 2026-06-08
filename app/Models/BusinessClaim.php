<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'proof_description',
        'document_path',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Get the business associated with the claim.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the user (vendor) who made the claim.
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who processed the claim.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope a query to only include pending claims.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved claims.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected claims.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
