<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\UploadImage;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use UploadImage;
    public function index()
    {
        setMenuStatus("product", "view");
        $products = Product::get();
        $title = "Product - View";
        $data = compact('title', 'products');
        return view("admin.product.view")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        setMenuStatus("product", "create");
        $title = "Product - Create";
        $categories = Category::where('status', 1)->get();
        $data = compact('title', 'categories');
        return view("admin.product.add")->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'max:100',
                    'unique:products'
                ],
                'slug' => "required|max:100|unique:products",
                'sku' => "required|max:100|unique:products",
                'ingredient' => 'required',
                'dose' => 'required',
                'uses' => 'required',
                'main_image' => "required|image",
                'other_images' => "required",
                'category' => 'required|gt:0'
            ],
            [
                'category.gt' => 'Please select a category'
            ]
        );
        $main_image = $request->file('main_image');

        $mainImageResp = $this->uploadImage($main_image, config('constants.storePath') . config('constants.product.main_image'));
        if ($mainImageResp['_status']) {
            $mainImageName = $mainImageResp['_name'];
        } else {
            return redirect()->back()->withErrors('Unable to add product, Internal server error');
        }
        // insert query
        DB::beginTransaction();

        try {
            $product = new Product();
            $product->name = $request['name'];
            $product->category_id = $request['category'];
            $product->slug = $request['slug'];
            $product->sku = $request['sku'];
            $product->ingredients = $request['ingredient'];
            $product->uses = $request['uses'];
            $product->doses = $request['dose'];
            $product->short_description = $request['short_description'];
            $product->long_description = $request['long_description'];
            $product->gst = $request['gst'];
            $product->main_image = $mainImageName;
            $product->meta_title = $request['meta_title'];
            $product->meta_description = $request['meta_description'];
            $product->meta_keywords = $request['meta_keywords'] ?? "";
            $product->save();
            $otherImges = $request->other_images; //array
            foreach ($otherImges as $otherImage) {
                $otherImageResp = $this->uploadImage($otherImage, config('constants.storePath') . config('constants.product.other_image'));
                if ($otherImageResp['_status']) {
                    $imageName = $otherImageResp['_name'];
                    $productImage = new ProductImage();
                    $productImage->image_name = $imageName;
                    $productImage->product_id  = $product->product_id;
                    $productImage->save();
                } else {
                    goto error;
                }
            }
            DB::commit();
        } catch (Exception $err) {
            error:
            $product = null;
            DB::rollBack();
        }

        if (is_null($product)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add product. Internal server error");
        } else {
            return redirect()->route('admin.product.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $productData = Product::with('product_image')->find($product);
        return response()->json(
            [
                'data' => $productData ? $productData->toArray() : [],
                'status' => 1
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $product = Product::with('product_image')->find($product);
        setMenuStatus("product", "edit");
        $title = "Product - Edit";
        $categories = Category::where('status', 1)->get();
        $data = compact('title', 'product', 'categories');
        return view("admin.product.edit")->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        p($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function destroyImage(ProductImage $image)
    {
        DB::beginTransaction();
        try {
            $image->delete();
            DB::commit();
        } catch (Exception $err) {
            $image = null;
            DB::rollBack();
        }
        if (is_null($image)) {
            return response()->json(
                [
                    'msg' => 'Unable to delete the image',
                    'status' => 0
                ]
            );
        } else {
            return response()->json(
                [
                    'msg' => 'Image deleted',
                    'status' => 1
                ]
            );
        }
    }
    public function checkProductName($name, $id = null)
    {
        if (is_null($id)) {
            $data = Product::where('name', $name)->first();
        } else {
            $data = Product::where('name', $name)->where('product_id', "!=", $id)->first();
        }
        if (is_null($data)) {
            // test pass
            return response()->json(['status' => 1]);
        } else {
            // test fail
            return response()->json(['status' => 0]);
        }
    }
    public function checkProductSku($sku, $id = null)
    {
        if (is_null($id)) {
            $data = Product::where('sku', $sku)->first();
        } else {
            $data = Product::where('sku', $sku)->where('product_id', "!=", $id)->first();
        }
        if (is_null($data)) {
            // test pass
            return response()->json(['status' => 1]);
        } else {
            // test fail
            return response()->json(['status' => 0]);
        }
    }

    public function toggle($type, Product $product)
    {
        // 1: Status 2: Featured 3: Seasonal 4: Best selling
        switch ($type) {
            case 1:
                $updateVal = $product->status == 1 ? 0 : 1;
                break;
            case 2:
                $updateVal = $product->featured == 1 ? 0 : 1;
                break;
            case 3:
                $updateVal = $product->seasonal == 1 ? 0 : 1;
                break;
            case 4:
                $updateVal = $product->most_selling == 1 ? 0 : 1;
                break;
            default:
                return response()->json([
                    'msg' => 'Internal server error',
                    'status' => 0
                ]);
                break;
        }
        DB::beginTransaction();
        try {
            switch ($type) {
                case 1:
                    $product->status = $updateVal;
                    break;
                case 2:
                    $product->featured = $updateVal;
                    break;
                case 3:
                    $product->seasonal = $updateVal;
                    break;
                case 4:
                    $product->most_selling = $updateVal;
                    break;
                default:
                    return response()->json([
                        'msg' => 'Internal server error',
                        'status' => 0
                    ]);
                    break;
            }
            $product->save();
            DB::commit();
        } catch (Exception $err) {
            $product = null;
            DB::rollBack();
        }
        if (is_null($product)) {
            return response()->json([
                'msg' => 'Unable to change the status',
                'status' => 0
            ]);
        } else {
            return response()->json([
                'msg' => 'Update successfully',
                'status' => 1
            ]);
        }
    }
}
