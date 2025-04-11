<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ViewerController extends Controller
{
    static public function view_website()
    {
        $viewed = Session::get("viewed", []);
        if (in_array("*", $viewed)) return;
        $viewer = DB::table("views")->where([
            "viewable_table" => "*",
            "viewed_at" => date('Y-m-d'),
        ])->first();
        if (!$viewer) {
            $viewer = DB::table("views")->find(DB::table("views")->insertGetId([
                "viewable_table" => "*",
                "viewed_at" => date('Y-m-d'),
            ]));
        }
        DB::table("views")->where('id', $viewer->id)->update([
            "views_count"=> $viewer->views_count + 1
        ]);
        array_push($viewed, "*");
        Session::put("viewed", $viewed);
    }
    static public function view_content($id, $table)
    {
        self::view_website();
        $viewed = Session::get("viewed", []);
        if (isset($viewed[$table])) {
            if (in_array($id, $viewed[$table])) return;
        } else $viewed[$table] = [];
        $viewer = DB::table("views")->where([
            "viewable_id" => $id,
            "viewable_table" => $table,
            "viewed_at" => date('Y-m-d'),
        ])->first();
        if (!$viewer) {
            $viewer = DB::table("views")->find(DB::table("views")->insertGetId([
                "viewable_id" => $id,
                "viewable_table" => $table,
                "viewed_at" => date('Y-m-d'),
            ]));
        }
        DB::table("views")->where('id', $viewer->id)->update([
            "views_count"=> $viewer->views_count + 1
        ]);
        array_push($viewed[$table], $id);
        Session::put("viewed", $viewed);
    }
    static public function get($id = NULL, $table = "*")
    {
        if (!$id && $table === '*') {
            $viewer =  DB::table("views")->where([
                "viewable_table" => "*",
            ])->first();
        }
        $viewer = DB::table("views")->where([
            "viewable_id" => $id,
            "viewable_table" => $table,
        ])->first();
        return $viewer ? $viewer->views_count : 0;
    }
    static public function viewAndGet($id = NULL, $table = "*")
    {
        if (!$id && $table === '*') {
            self::view_website();
        } else {
            self::view_content($id, $table);
        }
        return self::get($id, $table);
    }
}
