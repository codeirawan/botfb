<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\PostReport
 *
 * @property int $id
 * @property int $post_id
 * @property int $group_id
 * @property string $status   // approved, pending, rejected
 * @property string|null $remarks
 * @property string|null $posted_at
 */
class PostReport extends Model
{
    use HasFactory;

    protected $table = 'post_reports';

    protected $fillable = [
        'post_id',
        'group_id',
        'status',
        'remarks',
        'posted_at',
    ];

    protected $dates = ['posted_at'];

    /**
     * Relationships
     */

    // The post this report belongs to
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // The group where the post was sent
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Accessors
     */
    public function getPostedAtFormattedAttribute(): ?string
    {
        return $this->posted_at
            ? Carbon::parse($this->posted_at)->format('d M Y H:i')
            : null;
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
