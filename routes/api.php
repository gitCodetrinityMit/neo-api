<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\CategorysController;
use App\Http\Controllers\User\ProductsController;
use App\Http\Controllers\User\OrdersController;
use App\Http\Controllers\WhistlistController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Login Route
Route::post('/signup-user', [AuthController::class, 'signupUser'])->name('user.signup');
Route::post('/signin-user',[AuthController::class, 'signinUser'])->name('user.signin');
Route::get('unauthorized', function () {
    return response()->json(['error' => 'Access Denied!!!'], 401);
})->name('unauthorized');

/*
|--------------------------------------------------------------------------
| Admin Route
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    // User Route
    Route::get('/user-list', [AuthController::class, 'userList'])->name('users');
    Route::get('/user-list/{id}', [AuthController::class, 'userGet'])->name('users.get');
    Route::post('/user-update', [AuthController::class, 'updateUser'])->name('user.update');
    Route::post('/remove-user/{id}', [AuthController::class, 'removeUser'])->name('user.remove');
    Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('user.forgotpassword');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    // Category Route
    Route::get('/category', [CategoryController::class, 'listCategory'])->name('category.list');
    Route::post('/category', [CategoryController::class,'addCategory'])->name('category.add');
    Route::get('/category/{id}', [CategoryController::class, 'editCategory'])->name('category.edit');
    Route::post('/category/{id}', [CategoryController::class, 'updateCategory'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->name('category.delete');

    // Product Route
    Route::get('/product', [ProductController::class, 'listProduct'])->name('product.list');
    Route::post('/product', [ProductController::class, 'addProduct'])->name('product.add');
    Route::get('/product/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::delete('/product/{id}',[ProductController::class, 'deleteProduct'])->name('product.delete');
    Route::post('/product/{id}', [ProductController::class, 'updateProduct'])->name('product.update');

    //Order Route
    Route::get('/order', [OrderController::class, 'listOrder'])->name('order.list');
    Route::get('/single-order/{id}', [OrderController::class, 'singleOrder'])->name('single.order.list');
});

/*
|--------------------------------------------------------------------------
| Fronted Api
|--------------------------------------------------------------------------
*/
// Product Listing Route
Route::get('/product-list', [ProductsController::class, 'productList'])->name('product-list');

// Category Listing Route
Route::get('/category-list', [CategorysController::class, 'categoryList'])->name('category-list');

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    // Wishlist Route
    Route::get('/wishlist', [WhistlistController::class, 'index'])->name('wishlist.get');
    Route::post('/add-wishlist', [WhistlistController::class, 'addWishlistProduct'])->name('product.add-wishlist');
    Route::post('/remove-wishlist', [WhistlistController::class, 'removeWishlistProduct'])->name('product.remove-wishlist');

    // Add To Cart Route
    Route::get('/cart', [CartController::class, 'index'])->name('cart.list');
    Route::post('/add-cart', [CartController::class, 'addProductCart'])->name('add.cart');
    Route::post('/remove-cart-item', [CartController::class, 'removeCartProduct'])->name('remove.cart.item');
    Route::post('/update-cart', [CartController::class, 'updateCartItem'])->name('cart.increment');

    //Order 
    Route::post('/create-order', [OrdersController::class, 'createOrder'])->name('order.cerate');
    Route::get('/order-list', [OrdersController::class, 'orderList'])->name('orders.list');
    Route::get('/single-order-show/{id}', [OrdersController::class, 'singleOrderShow'])->name('single.order.list');
    Route::post('/update-order-status/{id}',[OrdersController::class, 'updateOrderStatus'])->name('order-status.update');
});
