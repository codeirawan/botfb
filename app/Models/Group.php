<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * Table name.
     */
    protected $table = 'groups';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'category_id',
        'facebook_account_id',
        'fb_group_id',
        'name',
        'privacy',   // public, closed, secret
        'active',    // boolean
    ];

    /**
     * Relationships
     */

    // Each group belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Each group belongs to a Facebook account (user)
    public function facebookAccount()
    {
        return $this->belongsTo(FacebookAccount::class);
    }

    // A group may have multiple scheduled posts
    public function schedules()
    {
        return $this->hasMany(PostSchedule::class);
    }

    // A group may have multiple posting reports
    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }

    /**
     * Scopes
     */

    // Active groups only
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Filter by category
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Accessors
     */

    public function getStatusTextAttribute()
    {
        return $this->active ? 'Active' : 'Inactive';
    }
}
