<?php
namespace App\Http\Controllers\Frontend;
use App\Models\Category;
use App\Models\Unibanner;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
class CategoryController extends Controller
{
    public function index($slug)
    {
        // Fetch all banners
        $unibanners = Unibanner::all();
        // Fetch category by slug
        $category = Category::where('slug', $slug)->where('status', true)->first();
        if ($category) {
            // Fetch posts with pagination
            $posts = $category->posts()
                ->with(['category', 'user'])
                ->where('status', true)
                ->orderByDesc('id')
                ->paginate(10);
            // Pass data to the view
            return view('frontend.category.index', [
                'category' => $category,
                'posts' => $posts,
                'unibanners' => $unibanners,
            ]);
        }
        // If category not found, return 404
        abort(404);
    }
    public function showCategoryPages($categorySlug)
    {
        // Fetch all banners
        $unibanners = Unibanner::all();
        // Fetch category with its pages
        $category = Category::where('slug', $categorySlug)
            ->with('pages')
            ->firstOrFail();
        $categories = Category::all();
        // Pass data to the view
        return view('frontend.category.index', [
            'category' => $category,
            'categories' => $categories,
            'unibanners' => $unibanners,
        ]);
    }
}