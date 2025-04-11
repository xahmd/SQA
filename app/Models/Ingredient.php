<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'image_url', 'category'
    ];
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, "recipes_ingredients");
    }
}
