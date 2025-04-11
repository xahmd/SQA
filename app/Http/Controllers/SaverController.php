<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class SaverController extends Controller
{
    private static function get($table, $id): Builder
    {
        return DB::table("saved")->where("savable_table", $table)->where("savable_id", $id)->where("user_id", auth()->user()->id);
    }
    function save(Request $request)
    {
        if (auth()->check()) {
            $check = self::get($request->input("table"), $request->input("id"));
            if ($check->exists()) {
                $check->delete();
                $saved = "unsaved";
            } else {
                DB::table("saved")->insert([
                    "savable_table" => $request->input("table"),
                    "savable_id" => $request->input("id"),
                    "saved_at" => date('Y-m-d'),
                    "user_id" => auth()->user()->id
                ]);
                $saved = "saved";
            }
            return response()->json([
                "saved" => $saved
            ]);
        }
        return response()->json([
            "type" => "error"
        ]);
    }
    public static function is_saved($table, $id): bool
    {
        return self::get($table, $id)->exists();
    }
    public function rating(Request $request)
    {
        $recipe = Recipe::findOrFail($request->input("recipe_id"));
        $rating = DB::table("rating")->where([
            'user_id' => auth()->user()->id,
            'recipe_id' => $recipe->id,
        ]);
        if ($rating->exists()) {
            $rating->update([
                'value' => intval($request->rating)
            ]);
        } else DB::table("rating")->insert([
            'value' => intval($request->rating),
            'user_id' => auth()->user()->id,
            'recipe_id' => $recipe->id,
        ]);
        return response()->json([
            "type" => "success",
            "message" => "The recipe was rated successfuly",
        ]);
    }
    public static function get_rating($recipe_id)
    {
        $rating = DB::table("rating")->where([
            'recipe_id' => $recipe_id
        ]);
        if ($rating->exists()) {
            return DB::table('rating')
                ->where('recipe_id', $recipe_id)
                ->avg('value');
        } else return -1;
    }
    private static function isRatedByUser($recipe_id): bool
    {
        return DB::table("rating")->where([
            'recipe_id' => $recipe_id,
            'user_id' => auth()->user()->id
        ])->exists();
    }
    public static function getRatingByUser($recipe_id): int
    {
        return self::isRatedByUser($recipe_id) ? DB::table("rating")->where([
            'recipe_id' => $recipe_id,
            'user_id' => auth()->user()->id
        ])->first()->value : -1;
    }
}
