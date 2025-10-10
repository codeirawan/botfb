<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationships
     */

    // A category has many groups
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Scopes
     */

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%");
    }
}
