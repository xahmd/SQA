<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($for)
    {
        return response()->json(Category::where('for', $for)->withCount($for)->get());
    }
    public function index_()
    {
        return $this->index('articles');
    }
    public function _index()
    {
        return $this->index('recipes');
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
    public function store(Request $request, $for)
    {
        $validated = $request->validate([
            'label' => 'required',
            'description' => 'required',
            'image' => ['required', File::image()],
        ]);
        $image = $request->file('image');
        $category = Category::create([
            "label" => Str::title($request->input("label")),
            "slug" => Str::slug($request->input("label")),
            "description" => Str::squish($request->input("description")),
            "image_url" => "",
            "for" => $for
        ]);
        $category->image_url = "/" . $image->storeAs("uploads", "category-$for-" . $category->slug . "-" . $category->id . "." . $image->getClientOriginalExtension());
        $category->save();
        return redirect()->route("admin")->withFragment("#/categories/" . $for);
    }
    public function _store(Request $request)
    {
        return $this->store($request, "recipes");
    }
    public function store_(Request $request)
    {
        return $this->store($request, "articles");
    }
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $category = Category::find($id);
        $category->label = Str::title($request->input("label"));
        $category->slug = Str::slug($request->input("label"));
        $category->description = Str::squish($request->input("description"));
        if ($request->hasFile("image")) {
            $request->validate(["image" => [File::image()]]);
            $image = $request->file('image');   
            $category->image_url = "/" . $image->storeAs("uploads", "category-" . $category->for . "-" . $category->slug . "-" . $category->id . "." . $image->getClientOriginalExtension());
        }
        $category->save();
        return redirect()->route("admin")->withFragment("#/categories/" . $category->for);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, $for)
    {
        $category = Category::withCount($for)->find($id);
        if ($category[$for . '_count'] === 0) {
            Storage::delete($category->image_url);
            $category->delete();
        }
        return redirect()->route("admin")->withFragment("#/categories/" . $for);
    }
    public function destroy_(int $id)
    {
        return $this->destroy($id, "articles");
    }
    public function _destroy(int $id)
    {
        return $this->destroy($id, "recipes");
    }
}
