<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Models\FacebookAccount
 *
 * @property int $id
 * @property string $name
 * @property string $fb_user_id
 * @property string $access_token
 * @property string|null $app_id
 * @property string|null $app_secret
 * @property string|null $token_expires_at
 */
class FacebookAccount extends Model
{
    use HasFactory;

    protected $table = 'facebook_accounts';

    protected $fillable = [
        'name',
        'fb_user_id',
        'access_token',
        'app_id',
        'app_secret',
        'token_expires_at',
    ];

    protected $dates = ['token_expires_at'];

    /**
     * Relationships
     */

    // This account owns many groups
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Accessors
     */

    public function getIsTokenExpiredAttribute(): bool
    {
        return $this->token_expires_at
            ? Carbon::now()->greaterThan(Carbon::parse($this->token_expires_at))
            : false;
    }

    /**
     * Scopes
     */

    public function scopeActive($query)
    {
        return $query->whereNull('token_expires_at')
            ->orWhere('token_expires_at', '>', now());
    }
}
