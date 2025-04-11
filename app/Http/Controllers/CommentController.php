<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $type, $id)
    {
        if ($type === 'article') {
            $commentable = Article::findOrFail($id);
        } elseif ($type === 'recipe') {
            $commentable = Recipe::findOrFail($id);
        } else {
            // Handle invalid commentable type here (e.g., redirect or error response)
            //return response()->json(['error' => 'Invalid commentable type'], 400);
            return back();
        }
        $comment = $commentable->comments()->create([
            'body' => $request->input("body"),
            'user_id' => auth()->user()->id
        ]);
        $plural = \Illuminate\Support\Str::plural("$type");
        return response()->redirectToRoute("$plural.show", ["slug" => $commentable->slug]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
