<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Models\Admin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test',function(){
//     $pass = 12345678;
//     $hashPass = Hash::make($pass);
//     echo "<br>";
//     echo $hashPass;
//     echo "<br>";
//     var_dump(Hash::check($pass,$hashPass));
// });

Route::get('/clear-cache', function () {
    Artisan::call("config:cache");
    return redirect('/');
});

Route::get('/storage-link', function () {
    Artisan::call("storage:link");
    return redirect('/');
});

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes Group
Route::prefix('/admin')->group(
    function () {
        Route::middleware('if_admin')->group(
            function () {
                Route::get('/login', [AdminController::class, 'index']);
                Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
                Route::get('/forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgotpassword');
                Route::post('/forgot-password', [AdminController::class, 'getForgotPasswordCode'])->name('admin.forgotpassword');
                Route::get('/reset-password/{key}', [AdminController::class, 'resetPassword'])->name('admin.resetPassword');
                Route::post('/update-password', [AdminController::class, 'updatePassword'])->name('admin.updatePassword');
            }
        );
        Route::get('/logout', function () {
            session()->forget(['admin_id', 'admin_name']);
            return redirect('/admin/login');
        })->name('admin.logout');
        Route::middleware('is_admin')->group(
            function () {
                // Admin Registration
                Route::middleware('super_admin')->group(
                    function () {
                        Route::get('/register', [AdminController::class, 'register'])->name("admin.register");
                        Route::get('/get-admins', [AdminController::class, 'getAdmins'])->name("admin.getAdmins");
                        Route::get('/check-email/{email}', [AdminController::class, 'checkEmail']);
                        Route::post('/register', [AdminController::class, 'store'])->name("admin.register");
                    }
                );
                // ------------------


                Route::get("/", [IndexController::class, 'dashboard'])->name('admin.dashboard');
                // Category Routes
                Route::prefix('/category')->group(
                    function () {
                        Route::get("/", [CategoryController::class, 'index'])->name('admin.category.index');
                        Route::get("/trash", [CategoryController::class, 'trash'])->name('admin.category.trash');
                        Route::get("/create", [CategoryController::class, 'create'])->name('admin.category.create');
                        Route::post("/store", [CategoryController::class, 'store'])->name('admin.category.store');
                        Route::get("/edit/{category}", [CategoryController::class, 'edit'])->name('admin.category.edit');
                        Route::post("/update/{category}", [CategoryController::class, 'update'])->name('admin.category.update');
                        Route::get("/check-name/{name}/{id?}", [CategoryController::class, 'checkCategoryName']);
                        Route::get('/delete/{category}', [CategoryController::class, 'destroy'])->name('admin.category.delete');
                        Route::get('/restore/{id}', [CategoryController::class, 'restore'])->name('admin.category.restore');
                        Route::get('/force-delete/{category}', [CategoryController::class, 'forceDestroy'])->name('admin.category.forceDelete');
                        Route::get('/toggle-status/{category}', [CategoryController::class, 'toggleStatus'])->name('admin.category.toggleStatus');
                    }
                );
                // ---------------
                // Products Routes
                Route::prefix('/product')->group(
                    function () {
                        Route::get("/", [ProductController::class, 'index'])->name('admin.product.index');
                        Route::get("/trash", [ProductController::class, 'trash'])->name('admin.product.trash');
                        Route::get("/create", [ProductController::class, 'create'])->name('admin.product.create');
                        Route::post("/store", [ProductController::class, 'store'])->name('admin.product.store');
                        Route::get("/edit/{product}", [ProductController::class, 'edit'])->name('admin.product.edit');
                        Route::post("/update/{product}", [ProductController::class, 'update'])->name('admin.product.update');
                        Route::get("/check-name/{name}/{id?}", [ProductController::class, 'checkProductName']);
                        Route::get("/check-sku/{sku}/{id?}", [ProductController::class, 'checkProductSku']);
                        Route::get('/delete/{product}', [ProductController::class, 'destroy'])->name('admin.product.delete');
                        Route::get('/delete-image/{image}', [ProductController::class, 'destroyImage'])->name('admin.product.deleteImage');
                        Route::get('/restore/{id}', [ProductController::class, 'restore'])->name('admin.product.restore');
                        Route::get('/force-delete/{product}', [ProductController::class, 'forceDestroy'])->name('admin.product.forceDelete');
                        Route::get('/toggle/{type}/{product}', [ProductController::class, 'toggle'])->name('admin.product.toggle');
                        Route::get("/{product}", [ProductController::class, 'show'])->name('admin.product.show');

                        Route::prefix('/variant')->group(
                            function () {
                                Route::get("/{product_id}", [ProductVariantController::class, 'index'])->name('admin.product.variant.index');
                                Route::get("/create/{product_id}", [ProductVariantController::class, 'create'])->name('admin.product.variant.create');
                                Route::post("/store/{product_id}", [ProductVariantController::class, 'store'])->name('admin.product.variant.store');
                                Route::get("/delete/{productVariant}", [ProductVariantController::class, 'destroy'])->name('admin.product.variant.delete');
                                Route::get("/edit/{productVariant}", [ProductVariantController::class, 'edit'])->name('admin.product.variant.edit');
                                Route::post("/update/{productVariant}", [ProductVariantController::class, 'update'])->name('admin.product.variant.update');
                                Route::get("/toggle-stock/{variant}", [ProductVariantController::class, 'toggleStock'])->name('admin.product.variant.toggleStock');
                                Route::get("/view-old-price/{variant}", [ProductVariantController::class, 'viewOldPrice'])->name('admin.product.variant.viewOldPrice');
                            }
                        );
                    }
                );
                // ---------------
            }
        );
    }
);
// ------------------