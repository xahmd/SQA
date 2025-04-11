<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SaverController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use App\Models\Instruction;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
https://claudiastable.com/wp-json/wp/v2/wprm_recipe?wprm_course=18&per_page=100
*/

Route::name("auth.")->controller(UserController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get("/login", 'login')->name("login");
        Route::post("/login", 'postLogin');
        Route::get("/register", 'register')->name("register");
        Route::post("/register", 'store');
    });
    Route::middleware('auth')->group(function () {
        Route::post("/logout", 'logout')->name("logout");
        Route::get("/profile", 'profile')->name("profile");
        Route::post("/profile", 'update');
    });
});
Route::name("comments.")->controller(CommentController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::post("/comment/{type}/{id}", 'store')->name("store");
    });
});

Route::name("recipes.")->prefix("recipes")->controller(RecipeController::class)->group(function () {
    Route::get('/', "index")->name("index");
    Route::get('/{slug}', "show")->name("show");
    Route::get('/category/{category}', "index_category")->name("index_category");
    Route::get('/tag/{tag}', "index_tag")->name("index_tag");
});
Route::name("articles.")->prefix("articles")->controller(ArticleController::class)->group(function () {
    Route::get('/', "index")->name("index");
    Route::get('/{slug}', "show")->name("show");
    Route::get('/category/{category}', "index_category")->name("index_category");
    Route::get('/tag/{tag}', "index_tag")->name("index_tag");
});

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name("home");
    Route::get('/search', 'search')->name("search");
    Route::get('/categories', 'categories')->name("categories");
});


Route::post('/f_admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/f_admin', [AdminController::class, 'index'])->name('admin');
Route::post('/f_admin', [AdminController::class, 'login']);

Route::get('/uploads/{url}', [StorageController::class, 'uploads']);
Route::get('/ckeditor/{url}', [StorageController::class, 'ckeditor']);

Route::post('/settings', [AdminController::class, 'settings']);

Route::prefix('api')->middleware("auth:admin")->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'get']);
    });
    
    Route::prefix("articles_categories")->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index_')->name("index");
        Route::post('/', 'store_');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy_');
    })->name("articles_categories.");

    Route::prefix("recipes_categories")->controller(CategoryController::class)->group(function () {
        Route::get('/', '_index')->name("index");
        Route::post('/', '_store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', '_destroy');
    })->name("recipes_categories.");

    Route::prefix('recipes')->group(function () {
        Route::controller(RecipeController::class)->group(function () {
            Route::delete('/{id}', 'destroy');
            Route::get('/{id}', 'get');
            Route::put('/{id}', 'update');
            Route::get('/', 'index_api')->name("index");
            Route::post('/', 'store');
        });
    })->name("recipes.");
    Route::prefix("ingredients")->controller(IngredientController::class)->group(function () {
        Route::get('/', 'index')->name("index");
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    })->name("ingredients.");
    Route::prefix("articles")->group(function () {
        Route::controller(ArticleController::class)->group(function () {
            Route::delete('/{id}', 'destroy');
            Route::get('/{id}', 'get');
            Route::put('/{id}', 'update');
            Route::get('/', 'index_api')->name("index");
            Route::post('/', 'store');
        });
    })->name("articles.");
    Route::prefix("ckeditor")->group(function () {
        Route::post('/upload', [StorageController::class, 'ckeditor_upload']);
    });
    Route::prefix("users")->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name("index");
    })->name("users.");
    Route::prefix("save")->controller(SaverController::class)->group(function () {
        Route::post('/', 'save')->name("save");
    });
    Route::prefix("rating")->controller(SaverController::class)->group(function () {
        Route::middleware("auth")->group(function () {
            Route::post('/', 'rating')->name("rating");
        });
    });
    Route::prefix("dashboard")->controller(AdminController::class)->group(function () {
        Route::get("/", "dashboard")->name("dashboard");
    });
})->name('api.');

Route::get('/test', function () {
    exit(0);
    for ($i = 1; $i <= 6; $i++) {
        $article = new Article();
        $article->title = Str::title(fake()->sentence());
        $article->slug = Str::slug($article->title);
        if (Article::where('slug', $article->slug)->exists()) {
            $count = 1;
            while (Article::where('slug', "$article->slug-$count")->exists()) {
                $count += 1;
            }
            $article->slug = $article->slug - $count;
        }
        $article->image_url = "/uploads/article-$article->slug.jpg";
        $article->tags = implode(",", fake()->words());
        $article->content = "";
        $article->save();
    }
    exit(0);
    $recipes = Recipe::paginate(5);
    foreach ($recipes as $recipe) {
        $url = "https://source.unsplash.com/collection/1424340/1920x1080?sig=" . $recipe->id;
        file_put_contents("temp.jpg", file_get_contents($url));
        shell_exec("ffmpeg -i temp.jpg -vf scale=1080:-1 ../storage/app" . $recipe->image_url);
    }
    unlink("temp.jpg");
    exit(0);
    $json = json_decode(file_get_contents("../app/Http/Controllers/recipes.json"));
    foreach ($json as $rec) {
        $recipe = Recipe::create([
            "title" => \Illuminate\Support\Str::title($rec->title),
            "slug" => \Illuminate\Support\Str::slug($rec->title),
            "description" => $rec->description,
            "cooking_time" => $rec->cooking_time,
            "difficulty_level" => $rec->difficulty_level,
            "cooking_method" => $rec->cooking_method,
            "serving_size" => $rec->serving_size,
            "image_url" => '',
            "tags" => implode(",", $rec->tags),
        ]);
        foreach ($rec->ingredients as $ingredient_id) {
            \Illuminate\Support\Facades\DB::table("recipes_ingredients")->insert([
                "ingredient_id" => $ingredient_id,
                "recipe_id" => $recipe->id
            ]);
        }
        foreach ($rec->categories as $category_id) {
            \Illuminate\Support\Facades\DB::table("recipes_categories")->insert([
                "category_id" => $category_id,
                "recipe_id" => $recipe->id
            ]);
        }
        $recipe->image_url = "/uploads/recipe-" . $recipe->slug . "-" . $recipe->id . ".jpg";
        $recipe->save();
    }
});
