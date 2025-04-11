<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Facades\Image;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return json_encode(Ingredient::withCount("recipes")->get());
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
        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'image' => ['required', File::image()],
        ]);
        $image = $request->file('image');
        $ingredient = Ingredient::create([
            "name" => Str::title($request->input("name")),
            "image_url" => "",
            "category" => strtolower(Str::squish($request->input("category"))),
        ]);
        $ingredient->image_url = '/uploads/ingredients/' .  Str::slug($ingredient->name) . "-" . $ingredient->id . "." . $image->getClientOriginalExtension();
        $new_image = Image::make($image->getRealPath());
        if ($new_image->width() > $new_image->height()) {
            $new_image->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
            });
            $new_image->save(storage_path('app' . $ingredient->image_url));
            $new_image->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $new_image->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $new_image->save(storage_path('app' . $ingredient->image_url));
            $new_image->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $new_image->save(storage_path('app/uploads/small/ingredient-' .  Str::slug($ingredient->name) . "-" . $ingredient->id . "." . $image->getClientOriginalExtension()));
        $ingredient->save();
        return redirect()->route("admin")->withFragment("#/ingredients/");
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->name = Str::title($request->input("name"));
        $ingredient->category = strtolower(Str::squish($request->input("category")));
        if ($request->hasFile("image")) {
            $request->validate(["image" => [File::image()]]);
            $image = $request->file('image');
            $new_image = Image::make($image->getRealPath());
            $ingredient->image_url = "/uploads/ingredient-" . Str::slug($ingredient->name) . "-" . $ingredient->id . "." . $image->getClientOriginalExtension();
            if ($new_image->width() > $new_image->height()) {
                $new_image->resize(null, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $new_image->save(storage_path('app' . $ingredient->image_url));
                $new_image->resize(null, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $new_image->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $new_image->save(storage_path('app' . $ingredient->image_url));
                $new_image->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $new_image->save(storage_path('app/uploads/small/ingredient-' .  Str::slug($ingredient->name) . "-" . $ingredient->id . "." . $image->getClientOriginalExtension()));
        }
        $ingredient->save();
        return redirect()->route("admin")->withFragment("#/ingredients");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::withCount("recipes")->find($id);
        if ($ingredient->recipes_count === 0) {
            Storage::delete($ingredient->image_url);
            Storage::delete(str_replace("/uploads/", "/uploads/small/", $ingredient->image_url));
            $ingredient->delete();
        }
        return redirect()->route("admin")->withFragment("#/ingredients");
    }
}
