<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notice extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'type', 'attachment',
        'visible_to_roles', 'visible_to_classes', 'is_published',
        'publish_date', 'expire_date', 'view_count',
    ];

    protected $casts = [
        'visible_to_roles' => 'array',
        'visible_to_classes' => 'array',
        'is_published' => 'boolean',
        'publish_date' => 'datetime',
        'expire_date' => 'datetime',
        'view_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}