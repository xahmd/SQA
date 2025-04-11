<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "label", "slug", "description", "image_url", "for"
    ];
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, "recipes_categories");
    }
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, "articles_categories");
    }
}
