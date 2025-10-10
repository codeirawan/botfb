<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\PostSchedule
 *
 * @property int $id
 * @property int $post_id
 * @property int $group_id
 * @property string|null $scheduled_at
 * @property string $status  // pending, processing, posted, failed
 * @property string|null $error_message
 */
class PostSchedule extends Model
{
    use HasFactory;

    protected $table = 'post_schedules';

    protected $fillable = [
        'post_id',
        'group_id',
        'scheduled_at',
        'status',
        'error_message',
    ];

    protected $dates = ['scheduled_at'];

    /**
     * Relationships
     */

    // Each schedule belongs to one post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Each schedule targets one Facebook group
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Accessors
     */

    public function getFormattedDateAttribute(): ?string
    {
        return $this->scheduled_at
            ? Carbon::parse($this->scheduled_at)->format('d M Y H:i')
            : null;
    }

    /**
     * Scopes
     */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }
}
