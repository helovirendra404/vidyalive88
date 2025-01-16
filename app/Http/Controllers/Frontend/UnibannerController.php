<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Unibanner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UnibannerController extends Controller
{
    
 
    public function index($slug) {
        $banner = Unibanner::all();
        if ($banner) {
            $str = Str::class;
            // $posts = $banner->posts()->with(["banner", "user"])->where("status", true)->orderBy("id", "DESC")->paginate(10);
            // return view("frontend.course.universitylogo", compact("banner"));
            return view("frontend.course.index", compact("banner"));
            return $banner;
        }
        return abort(404);
    }
}
