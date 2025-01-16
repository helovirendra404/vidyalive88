<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Exam;
use App\Models\Post;
use App\Models\Banner;
use App\Models\Unipage;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function show()
    {

    }
    public function index() {
        $recentposts =  Post::select('categories.*', 'posts.*', 'posts.title as name')
        ->join('categories', 'posts.category_id', '=', 'categories.id')
        ->where('posts.status', true)
        ->orderBy('posts.id', 'DESC')
        ->paginate(10);
        $homecategories = Category::selectRaw('*')->distinct()
        ->withCount(["posts" => function ($q) {$q->withTrashed();}])
        ->orderBy("title")
        ->paginate(40);
        $course = Category::selectRaw('*')->distinct()
        ->orderBy("title")->limit(9)
        ->get();
        // $exam = Exam::whereStatus(true)     
        // ->limit(10)
        // ->get();
        // $exams = Exam::orderBy('title')->paginate(40);
      
        $exam= Exam::all();
        $location= Location::all();
        $banners=Banner::select('banner.*');
        $featuredposts = Post::with(["category", "user"])->where("status", true)->where("is_featured", true)->orderBy("id", "DESC")->limit(10)->get();
        // $categories = Category::with('posts')->where("status", true)->orderBy("title", "ASC")->limit(10)->get();
        $categories =  Category::select('categories.*', 'posts.title as name')->join('posts', 'categories.id', '=', 'posts.category_id')->orderBy("title", "ASC")->limit(10)->get();
        $cateTabs = Category::all();
        $banners = Banner::where("status", true)->orderBy("title", "ASC")->limit(10)->get();
        $university=Unipage::all();   
        $uniTabs = Unipage::limit(6)->get();      
        return view("frontend.home.index", compact("recentposts", "location",  "featuredposts", "categories","banners","homecategories","exam","course",'university', 'cateTabs','uniTabs'));
  
          
     
    }
   
}
