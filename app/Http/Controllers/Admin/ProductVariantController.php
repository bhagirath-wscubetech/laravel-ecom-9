<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product_id)
    {
        setMenuStatus("product", "");
        $title = "Product - Variant";
        $product = Product::with(
            [
                'product_variant' => function ($q) {
                    $q->orderBy('size', 'asc');
                }
            ]
        )->find($product_id);
        $data = compact('title', 'product');
        return view('admin.product.variant.view')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($product_id)
    {
        setMenuStatus("product-variant", "create");
        $title = "Product - Create";
        $product = Product::find($product_id);
        $data = compact('title', 'product');
        return view("admin.product.variant.add")->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $product_id)
    {
        $request['product_id'] = $product_id;
        DB::beginTransaction();
        try {
            $variant = ProductVariant::create($request->all());
            DB::commit();
        } catch (Exception $err) {
            $variant = null;
            DB::rollBack();
        }
        if (is_null($variant)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add variant. Internal server error");
        } else {
            return redirect()->route('admin.product.variant.index', ['product_id' => $product_id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVariant $productVariant)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVariant $productVariant)
    {
        setMenuStatus("product-variant", "create");
        $title = "Product Variant - Edit";
        $data = compact('title', 'productVariant');
        return view("admin.product.variant.edit")->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        DB::beginTransaction();
        try {
            $variant = $productVariant->update($request->all());
            DB::commit();
        } catch (Exception $err) {
            $variant = null;
            DB::rollBack();
        }
        if (is_null($variant)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add variant. Internal server error");
        } else {
            return redirect()->route('admin.product.variant.index', ['product_id' => $productVariant->product_id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductVariant  $productVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariant $productVariant)
    {
        DB::beginTransaction();
        try {
            $productVariant->delete();
            DB::commit();
        } catch (Exception $err) {
            $productVariant = null;
            DB::rollBack();
        }

        if (is_null($productVariant)) {
            return redirect()->back()->withErrors('Unable to delete the data');
        } else {
            return redirect()->back()->with('success', 'Data deleted successfully');
        }
    }

    public function toggleStock(ProductVariant $variant)
    {
        DB::beginTransaction();
        try {
            if ($variant->stock == 1) {
                $variant->stock = 0;
            } else {
                $variant->stock = 1;
            }
            $variant->save();
            DB::commit();
        } catch (Exception $err) {
            $variant = null;
            DB::rollBack();
        }
        if (is_null($variant)) {
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
