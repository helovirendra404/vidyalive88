<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::withCount(["posts" => function($q) {
            $q->withTrashed();
        }])->orderBy("title", "ASC")->paginate(20);
        return view("dashboard.category.index", compact("categories"));
    }

    public function create() {
        return view("dashboard.category.add");
    }

    public function store(Request $request) {
        $validated = $request->validate([
            "title" => ["required", "string", "max:150"],
            "slug" => ["required", "max:150", "unique:categories,slug"],
            "duration" => ["required", "string", "max:150"],
            "college" => ["required","string", "max:150"],
            "sort_description" => ["nullable", "string"],
            "description" => ["nullable", "string"],           
            "image" => ["nullable", "image"],
            "status" => ["required", Rule::in(["0", "1"])],
            "is_top_course" => [Rule::in(["0", "1"])],
            
        ]);
        if (Arr::has($validated, "image")) {
            $image = $request->file("image");
            $imageName = md5(time().rand(11111, 99999)).".".$image->extension();
            $image->move(public_path("uploads/category"), $imageName);
        }
        Category::create([
            "title" => $validated["title"],
            "slug" => $validated["slug"],
            "duration" => $validated["duration"],
            "college" => $validated["college"],
            "sort_description" => Arr::has($validated, "sort_description") ? $validated["sort_description"] : null,
            "description" => Arr::has($validated, "description") ? $validated["description"] : null,
            "image" => Arr::has($validated, "image") ? $imageName : null,
            "status" => $validated["status"],
            "is_top_course" => Arr::has($validated, "is_top_course"),
        ]);
        return redirect()->route("dashboard.categories.index")->with("success", "Category created!");
    }

    
    
  

    public function edit(string $id) {
        $category = Category::find($id);
        if ($category) {
            return view("dashboard.category.edit", compact("category"));
        }
        return back()->withErrors("Category not exists!");
    }

    public function update(Request $request, string $id) {
        $category = Category::find($id);
        if ($category) {
            $validated = $request->validate([
                "title" => ["required", "string", "max:150"],
                "slug" => ["required", "max:150", Rule::unique("categories", "slug")->ignore($id)],
                "duration" => ["required", "string", "max:150"],
                "college" => ["required", "string", "max:150"],
                "sort_description" => ["nullable", "string"],
                "description" => ["nullable", "string"],
                "image" => ["nullable", "image"],
                "status" => ["required", Rule::in(["0", "1"])],
                "is_top_course" => ["nullable", Rule::in(["0", "1"])],
            ]);
            $category->title = $validated["title"];
            $category->slug = $validated["slug"];
            $category->duration = $validated["duration"];
            $category->is_top_course = Arr::has($validated, "is_top_course");
            $category->college = $validated["college"];
            $category->sort_description = Arr::has($validated, "sort_description") ? $validated["sort_description"] : null;
            $category->description = Arr::has($validated, "description") ? $validated["description"] : null;
            $category->status = $validated["status"];
            if (Arr::has($validated, "image")) {
                $image = $request->file("image");
                $imageName = md5(time().rand(11111, 99999)).".".$image->extension();
                $image->move(public_path("uploads/category"), $imageName);
                if ($category->image) {
                    if (File::exists(public_path("uploads/category/".$category->image))) {
                        File::delete(public_path("uploads/category/".$category->image));
                    }
                }
                $category->image = $imageName;
            }
            $category->save();
            return redirect()->route("dashboard.categories.index")->with("success", "Category updated!");
        }
        return back()->withErrors("Category not exists!");
    }

    public function destroy(string $id) {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return back()->with("success", "Category deleted!");
        }
        return back()->withErrors("Category not exists!");
    }

    public function restore($id) {
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            $category->restore();
            return back()->with("success", "Category restored!");
        }
        return back()->withErrors("Category not exists!");
    }

    public function status($id) {
        $category = Category::find($id);
        if ($category) {
            $category->status = $category->status ? "0" : "1";
            $category->save();
            $alert = $category->status ? "Category Activated!" : "Category Inactivated!";
            return back()->with("success", $alert);
        }
        return back()->withErrors("Category not exists!");
    }

    public function trashed() {
        $categories = Category::onlyTrashed()->withCount(["posts" => function($q) {
            $q->withTrashed();
        }])->orderBy("title", "ASC")->paginate(20);
        return view("dashboard.category.trashed", compact("categories"));
    }

    public function delete($id) {
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            if (File::exists(public_path("uploads/category/".$category->image))) {
                File::delete(public_path("uploads/category/".$category->image));
            }
            $category->posts()->forceDelete();
            $category->forceDelete();
            return back()->with("success", "Category deleted!");
        }
        return back()->withErrors("Category not exists!");
    }
}
