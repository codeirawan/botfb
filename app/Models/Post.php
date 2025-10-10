<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'type',           // text, photo, video
        'media_path',
        'status',         // draft, scheduled, posted
        'scheduled_at',   // nullable timestamp
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Relationships
     */

    // A post can have many schedules (for repeat posting)
    public function schedules()
    {
        return $this->hasMany(PostSchedule::class);
    }

    // A post can have many posting reports (one per group)
    public function reports()
    {
        return $this->hasMany(PostReport::class);
    }

    // A post can belong to many groups
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_post', 'post_id', 'group_id')
            ->withTimestamps();
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
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }
}
