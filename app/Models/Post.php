<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'type',           // text, photo, video
        'media_path',
        'status',         // draft, scheduled, posted
        'scheduled_at',   // nullable timestamp
    ];

    /**
     * Relationships
     */

    // Each post may be scheduled for multiple groups
    public function schedules()
    {
        return $this->hasMany(PostSchedule::class);
    }

    // Each post may have multiple report entries (one per group)
    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }

    /**
     * Accessors
     */

    public function getMediaUrlAttribute(): ?string
    {
        return $this->media_path
            ? asset('storage/' . $this->media_path)
            : null;
    }

    /**
     * Query Scopes
     */

    // Draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scheduled posts
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Already posted
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }
}
