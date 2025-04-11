<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Instruction;
use App\Models\Recipe;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recipes.index', [
            "recipes" => Recipe::orderByDesc('id')->paginate(8)
        ]);
    }
    public function index_api()
    {
        return response()->json(Recipe::with("categories")->orderByDesc('id')->get());
    }
    public function index_category($category)
    {
        return view('recipes.search')->with("recipes", Recipe::whereRelation('categories', 'slug', $category)->orderByDesc('id')->paginate(8))->with("search_message", "Recipes in the Category '" . Str::title($category) . "'");
    }
    public function index_tag($tag)
    {
        return view('recipes.search')->with("recipes", Recipe::where(DB::raw("lower(tags)"), 'like', "%$tag%")->orderByDesc('id')->paginate(8))->with("search_message", "Recipes Tagged '" . Str::title($tag) . "'");;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $slug = Str::slug($request->input("title", ""));
        if (Recipe::where('slug', $slug)->exists()) {
            $count = 1;
            while (Recipe::where('slug', "$slug-$count")->exists()) {
                $count += 1;
            }
            $slug = "$slug-$count";
        }
        $recipe = Recipe::create([
            "title" => Str::title($request->input("title", "")),
            "slug" => $slug,
            "description" => trim($request->input("description", "")),
            "cooking_time" => intval($request->input("cooking_time", "15")),
            "difficulty_level" => Str::lower($request->input("difficulty_level", "medium")),
            "cooking_method" => Str::lower(trim($request->input("cooking_method", ""))),
            "serving_size" => intval($request->input("serving_size", "1")),
            "tags" => Str::lower(trim($request->input("tags", ""))),
            "image_url" => '',
        ]);
        foreach (explode(",", $request->input("categories")) as $id) {
            if (($category = Category::find($id)) && $category->for === "recipes")
                DB::table("recipes_categories")->insert([
                    "category_id" => $id,
                    "recipe_id" => $recipe->id
                ]);
        }
        foreach (explode(",", $request->input("ingredients")) as $id) {
            DB::table("recipes_ingredients")->insert([
                "ingredient_id" => $id,
                "recipe_id" => $recipe->id
            ]);
        }
        DB::table("recipes_contents")->insert([
            "recipe_id" => $recipe->id,
            "content" => (new HTMLPurifier(HTMLPurifier_Config::createDefault()))->purify($request->input("content"))
        ]);
        $instructions = request("instructions");
        foreach ($instructions as $key => $instruction) {
            Instruction::create([
                "recipe_id" => $recipe->id,
                "content" => $instruction,
            ]);
        }
        $recipe->image_url = "/" . $request->file('image')->storeAs("uploads", "recipe-" . $recipe->slug . "-" . $recipe->id . "." . $request->file('image')->getClientOriginalExtension());
        $recipe->save();
        return redirect()->route("admin")->withFragment("#/recipes");
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        if (Recipe::where('slug', $slug)->exists()) {
            $recipe = Recipe::where('slug', $slug)->first();
            return view('recipes.show', [
                'recipe' => $recipe,
                'views' => ViewerController::viewAndGet($recipe->id, 'recipes'),
                'rating' => SaverController::get_rating($recipe->id),
            ]);
        }
        return response()->redirectTo('/recipes');
    }

    public function get($id)
    {
        if ($recipe = Recipe::find($id)) {
            $recipe->categories = DB::table('recipes_categories')->where('recipe_id', $id)->select(['category_id'])->get()->map(function ($obj) {
                return $obj->category_id;
            });
            $recipe->append('content');
            $recipe->load(relations: ['ingredients', 'instructions']);

            return response()->json($recipe);
        }
        return response()->json(null);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $recipe = Recipe::find($id);
        $slug = Str::slug($request->input("title", ""));
        if ($slug != $recipe->slug) if (Recipe::where('slug', $slug)->exists()) {
            $count = 1;
            while (Recipe::where('slug', "$slug-$count")->exists()) {
                $count += 1;
            }
            $slug = "$slug-$count";
        }
        $recipe->title = Str::title($request->input("title", ""));
        $recipe->slug = $slug;
        $recipe->description = trim($request->input("description", ""));
        $recipe->cooking_time = intval($request->input("cooking_time", "15"));
        $recipe->difficulty_level = Str::lower($request->input("difficulty_level", "medium"));
        $recipe->cooking_method = Str::lower(trim($request->input("cooking_method", "")));
        $recipe->serving_size = intval($request->input("serving_size", "1"));
        $recipe->tags = Str::lower(trim($request->input("tags", "")));
        DB::table("recipes_categories")->where("recipe_id", $recipe->id)->delete();
        foreach (explode(",", $request->input("categories")) as $id) {
            if (($category = Category::find($id)) && $category->for === "recipes")
                DB::table("recipes_categories")->insert([
                    "category_id" => $id,
                    "recipe_id" => $recipe->id
                ]);
        }
        DB::table("recipes_ingredients")->where("recipe_id", $recipe->id)->delete();
        foreach (explode(",", $request->input("ingredients")) as $id) {
            DB::table("recipes_ingredients")->insert([
                "ingredient_id" => $id,
                "recipe_id" => $recipe->id
            ]);
        }
        DB::table("recipes_contents")->where("recipe_id", $recipe->id)->delete();
        DB::table("recipes_contents")->insert([
            "recipe_id" => $recipe->id,
            "content" => (new HTMLPurifier(HTMLPurifier_Config::createDefault()))->purify($request->input("content"))
        ]);
        $instructions = request("instructions");
        if ($instructions) foreach ($instructions as $key => $instruction) {
            Instruction::create([
                "recipe_id" => $recipe->id,
                "content" => $instruction,
            ]);
        }
        if ($request->hasFile('image')) {
            Storage::delete($recipe->image_url);
            $recipe->image_url = "/" . $request->file('image')->storeAs("uploads", "recipe-" . $recipe->slug . "-" . $recipe->id . "." . $request->file('image')->getClientOriginalExtension());
        }
        $recipe->save();
        return redirect()->route("admin")->withFragment("#/recipes");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $recipe = Recipe::find($id);
        DB::table("recipes_ingredients")->where("recipe_id", $recipe->id)->delete();
        DB::table("recipes_categories")->where("recipe_id", $recipe->id)->delete();
        DB::table("recipes_contents")->where("recipe_id", $recipe->id)->delete();
        DB::table("rating")->where("recipe_id", $recipe->id)->delete();
        DB::table("comments")->where("commentable_id", $recipe->id)->where("commentable_type", "recipe")->delete();
        DB::table("saved")->where("savable_id", $recipe->id)->where("savable_table", "recipes")->delete();
        Storage::delete($recipe->image_url);
        $recipe->instructions()->delete();
        return response()->json(["success" => $recipe->delete()]);
    }
}
