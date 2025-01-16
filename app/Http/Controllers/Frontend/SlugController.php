<?php
namespace App\Http\Controllers\Frontend;
use App\Models\Tabs;
use App\Models\Image;
use App\Models\Unipage;
use App\Models\Category;
use App\Models\Unibanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SlugController extends Controller
{
public function handle($slug)
{
    // Check if the slug corresponds to a category
    $unibanners = Unibanner::all();
    // Fetch category by slug
    $category = Category::where('slug', $slug)->where('status', true)->first();
    if ($category) {
        // Return the category view
        return view('frontend.category.index', compact('category','unibanners'));
    }
    // Check if the slug corresponds to a university
    $university = Unipage::where('slug', $slug)->first();
    if ($university) {
        // Return the university view

        // ==============================================

        $university = Unipage::where("slug", $slug)->first();
 
        // where('unipage_id' == $university->id)
        // $tabs = Tabs::all();
        if ($university) {
            $uniPageNum = $university->id;
            $tabs = Tabs::where('unipage_id', function ($query) use ($uniPageNum) {
                $query->select('id')
                      ->from('unipages')
                      ->where('id', $uniPageNum);
            })->get();
    
            $image = Image::where('unipage_id', function ($query) use ($uniPageNum) {
                $query->select('id')
                      ->from('unipages')
                      ->where('id', $uniPageNum);
            })->get();
      
            // @dd($tabs);
        } else {
            // Handle the case where no university is found
            dd("University not found");
        }
        // @dd($image);
        // return view("frontend.university.index", compact('university', 'myName', 'tabs', 'image'));
       
        // ==============================================
       
        return view('frontend.university.index', compact('university', 'tabs', 'image'));
    }
    // If neither category nor university is found, show a 404 page
    abort(404);
}
}