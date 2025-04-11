<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','image_url','content','tags','slug'];
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::title($value),
        );
    }
    protected function tags(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => explode(",", strtolower($value)),
        );
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, "articles_categories");
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function relatedArticles($limit = 6)
    {
        // Get current article's ID
        $currentArticleId = $this->id;

        // Get all articles except the current one
        $articles = self::where('id', '!=', $currentArticleId)->get();

        // Get current article's categories and tags
        $currentCategories = $this->categories()->pluck('categories.id');
        $currentTags = $this->tags;

        // Loop through each article and calculate common categories and tags count
        $articlesWithCounts = $articles->map(function ($article) use ($currentCategories, $currentTags) {
            $relatedCategories = $article->categories()->pluck('categories.id');
            $relatedTags = $article->tags;

            $commonCategoriesCount = count(array_intersect($currentCategories->toArray(), $relatedCategories->toArray()));
            $commonTagsCount = count(array_intersect($currentTags, $relatedTags));

            $article->count_common_categories = $commonCategoriesCount;
            $article->count_common_tags = $commonTagsCount;

            return $article;
        });

        $sortedArticles = $articlesWithCounts
            ->sortByDesc('count_common_categories')
            ->sortByDesc('count_common_tags')
            ->take($limit);

        return $sortedArticles;
    }
}
