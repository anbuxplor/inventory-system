<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoryItem extends Model
{
    use HasFactory;

    /**
     * Get the category associated with the respective row.
     */
    public function info(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
