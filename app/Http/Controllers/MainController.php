<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $recipes = Recipe::orderByDesc('id')->paginate(8);
        $articles = Article::orderByDesc('id')->paginate(6);
        $featured_recipe = Recipe::inRandomOrder()->first();
        //$popular_recipes = Recipe::inRandomOrder()->take(10)->get(['title', 'image_url', 'slug']);
        $popular_recipes = DB::table("recipes")->where("deleted_at", null)->leftJoinSub(DB::table("views")->where("views.viewable_table", "=", "recipes"), "views", function (JoinClause $join) {
            $join->on('views.viewable_id', '=', 'recipes.id');
        })->orderByDesc("views.views_count")->orderByDesc("recipes.id")->take(10)->get(['title', 'image_url', 'slug']);
        return view('index', compact("categories", "recipes", "articles", "featured_recipe", "popular_recipes"));
    }
    public function search()
    {
        $q = request("q", "");
        $category = request("category", "");
        $recipes = Recipe::where(function ($query) use ($q) {
            $query->where(DB::raw("lower(title)"), "like", "%$q%")
                ->orWhere(DB::raw("lower(description)"), "like", "%$q%");
        });
        if ($category !== "") $recipes = $recipes->whereRelation('categories', 'slug', $category);
        $recipes = $recipes->paginate(8)->withQueryString();
        return view("recipes.search")->with("recipes", $recipes)->with("search_message", "Search Result for \"$q\"" . ($category !== "" ? " in the Category \"" . Str::title($category) . "\"" : ""));
    }
    public function categories()
    {
        return view("pages.categories")->with("categories", Category::orderBy('label')->get());
    }
}
