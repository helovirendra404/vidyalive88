<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Category;
use App\Models\Unibanner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{

   public function index($categorySlug, $pageSlug)
   {
       // Fetch the category based on the slug

       $unibanners = Unibanner::all();
       $category = Category::where('slug', $categorySlug)->firstOrFail();

       // Fetch the page based on the category and page slug
       $page = Page::where('slug', $pageSlug)
           ->where('category_id', $category->id)
           ->firstOrFail();

       // Pass data to the view
       return view('frontend.course.index', compact('category', 'page','unibanners'));
   }
   public function showCategoryPage($categorySlug, $pageSlug)
   {
       // Fetch the category by slug
       $category = Category::where('slug', $categorySlug)->firstOrFail();
       $unibanners = Unibanner::all();
       // Fetch the page by slug and ensure it belongs to the fetched category
       $page = Page::where('slug', $pageSlug)
           ->where('category_id', $category->id)
           ->firstOrFail();
       // Fetch all pages for the sidebar
       $pages = $category->pages;
// return view('frontend.course.index', compact('category', 'categories'));
       return view('frontend.course.index', compact('category', 'page', 'pages','unibanners'));
   }


}
