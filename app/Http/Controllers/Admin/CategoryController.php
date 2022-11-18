<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\UploadImage;

class CategoryController extends Controller
{
    use UploadImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        setMenuStatus("category", "view");
        $categories = Category::get();
        $title = "Category - View";
        $data = compact('title', 'categories');
        return view("admin.category.view")->with($data);
    }

    public function trash()
    {
        // session(["menu" => "category", "sub-menu" => "trash"]);
        setMenuStatus("category", "trash");
        $categories = Category::onlyTrashed()->get();
        $title = "Category - Trash";
        $data = compact('title', 'categories');
        return view("admin.category.trash")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // session(["menu" => "category", "sub-menu" => "create"]);
        setMenuStatus("category", "create");
        $title = "Cateogry - Create";
        $data = compact('title');
        return view("admin.category.add")->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // server side validation
        $request->validate(
            [
                'name' => [
                    'required',
                    'max:100',
                    'unique:categories'
                ],
                'slug' => "required|max:100|unique:categories",
                'icon' => "required|image",
                'banner' => "required|image",
            ],
            [
                'name.required' => "The category name cannot be empty",
            ]
        );

        $icon = $request->file('icon');
        $banner = $request->file('banner');
        // ----------------
        DB::beginTransaction();
        try {
            $iconResp = $this->uploadImage(
                $icon,
                config('constants.storePath') . config('constants.category.icon_image')
            );
            if ($iconResp['_status']) {
                $iconName = $iconResp['_name'];
            } else {
                return redirect()->back()->withInput()->withErrors("Unable to add category. Internal server error");
            }
            $bannerResp = $this->uploadImage(
                $banner,
                config('constants.storePath') . config('constants.category.banner_image')
            );
            if ($bannerResp['_status']) {
                $bannerName = $bannerResp['_name'];
            } else {
                return redirect()->back()->withInput()->withErrors("Unable to add category. Internal server error");
            }
            // insert
            $category = new Category();
            $category->name = $request['name'];
            $category->slug = $request['slug'];
            $category->icon = $iconName;
            $category->banner = $bannerName;
            $category->description = $request['description'];
            $category->meta_title = $request['meta_title'];
            $category->meta_keywords = $request['meta_keywords'];
            $category->meta_description = $request['meta_description'];
            $category->save();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }
        if (is_null($category)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add category. Internal server error");
        } else {
            return redirect()->route('admin.category.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        setMenuStatus("category", "");
        $data = [
            'category' => $category,
            'title' => "Category - Edit"
        ];
        return view('admin.category.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'max:100',
                    "unique:categories,name,$category->id"
                    // unique:table_name,col_name,except
                ],
                'slug' => "required|max:100|unique:categories,slug,$category->id",
                'icon' => "image",
                'banner' => "image",
            ],
            [
                'name.required' => "The category name cannot be empty",
            ]
        );
        if ($request->file('icon') == "") {
            $iconName = $category->icon;
        } else {
            $icon = $request->file('icon');
            $iconName = getRandomFileName($icon->getClientOriginalName());
            $iconPath = public_path("images/category/icons");
            $icon->move($iconPath, $iconName);
        }

        if ($request->file('banner') == "") {
            $bannerName = $category->banner;
        } else {
            $banner = $request->file('banner');
            $bannerName = getRandomFileName($banner->getClientOriginalName());
            $bannerPath = public_path("images/category/banners");
            $banner->move($bannerPath, $bannerName);
        }
        DB::beginTransaction();
        try {
            $category->name = $request['name'];
            $category->slug = $request['slug'];
            $category->icon = $iconName;
            $category->banner = $bannerName;
            $category->description = $request['description'];
            $category->meta_title = $request['meta_title'];
            $category->meta_keywords = $request['meta_keywords'];
            $category->meta_description = $request['meta_description'];
            $category->save();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }
        if (is_null($category)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add category. Internal server error");
        } else {
            return redirect()->route('admin.category.index')->with('success', 'Category updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $category->delete();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }

        if (is_null($category)) {
            return redirect()->back()->withErrors('Unable to delete the data');
        } else {
            return redirect()->back()->with('success', 'Data deleted successfully');
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);
        DB::beginTransaction();
        try {
            $category->restore();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }

        if (is_null($category)) {
            return redirect()->back()->withErrors('Unable to delete the data');
        } else {
            return redirect()->back()->with('success', 'Data restored successfully');
        }
    }

    public function forceDestroy($category)
    {
        DB::beginTransaction();
        try {
            $category = Category::withTrashed()->find($category);
            if (is_null($category)) {
                return abort(404);
            }
            $icon = public_path('images/category/icons') . "/$category->icon";
            $banner = public_path('images/category/banners') . "/$category->banner";
            $category->forceDelete();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }

        if (is_null($category)) {
            return redirect()->back()->withErrors('Unable to delete the data');
        } else {
            unlink($icon);
            unlink($banner);
            return redirect()->back()->with('success', 'Data deleted successfully');
        }
    }

    public function checkCategoryName($name, $id = null)
    {
        if (is_null($id)) {
            $data = Category::where('name', $name)->first();
        } else {
            $data = Category::where('name', $name)->where('id', "!=", $id)->first();
        }
        if (is_null($data)) {
            // test pass
            return response()->json(['status' => 1]);
        } else {
            // test fail
            return response()->json(['status' => 0]);
        }
    }

    public function toggleStatus(Category $category)
    {
        if ($category->status == 1) {
            $newStatus = 0;
        } else {
            $newStatus = 1;
        }
        DB::beginTransaction();
        try {
            $category->status = $newStatus;
            $category->save();
            DB::commit();
        } catch (Exception $err) {
            $category = null;
            DB::rollBack();
        }
        if (is_null($category)) {
            return redirect()->back()->withErrors('Unable to change the status');
        } else {
            return redirect()->back()->with('success', 'Status changed successfully');
        }
    }
}
