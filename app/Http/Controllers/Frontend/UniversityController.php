<?php
namespace App\Http\Controllers\Frontend;
use App\Models\Tabs;

use App\Models\Image;
use App\Models\Unipage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UniversityController extends Controller
{
    public function index($universitySlug)
   {
 
    $university = Unipage::where("slug", $universitySlug)->first();
 
    // where('unipage_id' == $university->id)
    // $tabs = Tabs::all();
    if ($university) {
        $uniPageNum = $university->id;
    $myName = "virendrta";
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
    return view("frontend.university.index", compact('university', 'myName', 'tabs', 'image'));
   }
}
