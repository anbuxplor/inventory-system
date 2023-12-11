<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    /**
     * Get the category that owns the item.
     */
    public function category(): HasMany
    {
        return $this->hasMany(CategoryItem::class, 'item_id', 'id');
    }
}
