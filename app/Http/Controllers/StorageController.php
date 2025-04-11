<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function uploads(Request $request, string $url)
    {
        if($request->has("small")) $url = "small/$url";
        if (!Storage::exists("uploads/$url"))
            return abort(404);
        return response(File::get(Storage::path("uploads/" . $url)))
            ->header("Content-Type", File::mimeType(Storage::path("uploads/" . $url)))
            ->header("Content-Length", File::size(Storage::path("uploads/" . $url)));
    }
    public function ckeditor(string $url)
    {
        if (!Storage::exists("ckeditor/" . $url))
            return abort(404);
        return response(File::get(Storage::path("ckeditor/" . $url)))
            ->header("Content-Type", File::mimeType(Storage::path("ckeditor/" . $url)))
            ->header("Content-Length", File::size(Storage::path("ckeditor/" . $url)));
    }
    public function ckeditor_upload(Request $request)
    {
        return response()->json([
            'url' => '/' . $request->file('upload')->storeAs("ckeditor", Carbon::now()->getTimestamp() . '.' . $request->file('upload')->getClientOriginalExtension())
        ]);
    }
}
